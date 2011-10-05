#!/usr/local/bin/python

## TODO: sudo passwd stuff
## meanwhile: RUN AS SUDO "sudo python setup.py"
##

import os
import sys
import subprocess
import shutil
import getpass
from optparse import OptionParser
import pexpect
import fileinput

###
sudopw = "NOTSET"
passwordSet=False

#avatar service sources
GIT_SOURCE = "git://github.com/Lehtiz/Avatar-service.git"
# default apache webroot
WEB_ROOT = "/var/www/"
AVATAR_ROOT = WEB_ROOT + "avatar/"

MYSQL_HOST = "localhost"
#mysql root details set during install
MYSQL_ROOT = "root"
MYSQL_ROOT_PW = "N73J"

DATABASE_FILE = "avatardb.sql"
DB_NAME = "avatar"

###
# if useRoot is set to true, mysql root account will be used for database connections
# False creates a new user for the database, with the details below
useRoot = False

MYSQL_USER = "avatarservice"
MYSQL_USER_PW = "avatarpw123"
###


def main():
    if os.getenv("USER") == "root": #if ran with root
        # Installs apache2, php5, mysql and configures mysql root login details
        # in addition installs git and debconf-utils for a fully automated installation
        installPrograms()
        
        # Fetches avatar-service files from github and imports the database 
        setupAvatarService()
        
        if not useRoot:
            createMysqlUser(MYSQL_HOST, MYSQL_USER, MYSQL_USER_PW)
            updateMysqlConfig(MYSQL_HOST, MYSQL_USER, MYSQL_USER_PW)
        else:
            updateMysqlConfig(MYSQL_HOST, MYSQL_ROOT, MYSQL_ROOT_PW)
    else:
        print "Script needs to be run with sudo (apt-get install)"


"""
    if passwordSet:
        installPrograms()
        setupAvatarService()
    else:
        print("no password given")
"""


def installPrograms():

    #preq for mysql installation parameters, git sources
    subprocess.call("sudo apt-get -y install debconf-utils git", shell=True)
    
    #apache2, php
    subprocess.call("sudo apt-get -y install apache2 php5-mysql libapache2-mod-php5", shell=True)
    
    #MYSQL
    #create preseed file
    preseedFile = "mysql.preseed"
    
    #make sure file does not exist, remove if it does
    if os.path.isfile(preseedFile):
        os.remove(preseedFile)
        
    with open(preseedFile, 'a') as pre:
        pre.write("mysql-server-5.1 mysql-server/" + "root_password password " + MYSQL_ROOT_PW + "\n")
        pre.write("mysql-server-5.1 mysql-server/" + "root_password_again password " + MYSQL_ROOT_PW + "\n")
        pre.write("mysql-server-5.1 mysql-server/" + "start_on_boot boolean true")
    
    #install mysql using variables from preseed
    #pipe vars from file and set
    subprocess.call("cat " + preseedFile + " | sudo debconf-set-selections", shell=True)
    subprocess.call("sudo apt-get -y install mysql-server", shell=True)
    
    #cleanup tmp files
    cleanUp(preseedFile)
        
    #restart apache server
    subprocess.call("sudo /etc/init.d/apache2 restart", shell=True)


def setupAvatarService():
    #presume tundra build already (TODO: add tundra build here?)
    
    #mod apaches default www home (TODO: custom www home)
    subprocess.call("sudo chown -R " + getpass.getuser() + " " + WEB_ROOT, shell=True)

    #check folder dest and move to webroot/backup/ if exists 
    if os.path.exists(AVATAR_ROOT):
        shutil.move(AVATAR_ROOT, WEB_ROOT + "backup/")
        
    #get sources from github
    subprocess.call("git clone " + GIT_SOURCE + " " + AVATAR_ROOT, shell=True)
    
    #setup database, import from a file
    subprocess.call("mysql -h" + MYSQL_HOST + " -u" + MYSQL_ROOT + " -p" + MYSQL_ROOT_PW + " < " + AVATAR_ROOT + DATABASE_FILE, shell=True)
    
    #setup glge for rendering in browser
    setupGlge()
    # added jquery to repo
    #getJquery()


def cleanUp(file):
    if os.path.isfile(file):
        os.remove(file)


# Creates a custom user for the mysql server with the info configured above
def createMysqlUser(host, user, pw):
    createUserTmpFile = "dbuser.sql"
    if os.path.isfile(createUserTmpFile):
        os.remove(createUserTmpFile)
    with open(createUserTmpFile, 'a') as f:
        f.write("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON " + DB_NAME + ".* TO '" + user + "'@'" + host + "' IDENTIFIED BY '" + pw + "'")
    subprocess.call("mysql -h" + MYSQL_HOST + " -u" + MYSQL_ROOT +" -p" + MYSQL_ROOT_PW + " < " + createUserTmpFile, shell=True)
    cleanUp(createUserTmpFile)


# automatically updates action/dbconnect.php with the mysql user and password info provided above
def updateMysqlConfig(host, user, pw):
    dbConfigFileIn = "dbconnect.php"
    dbConfigFileOut = "tmp"
    
    identhost="<<HOST_NOT_SET>>"
    identuser="<<USER_NOT_SET>>"
    identpw="<<PW_NOT_SET>>"
    
    os.chdir(AVATAR_ROOT + "action/")
    
    with open(dbConfigFileIn, 'r') as feed:
        for line in feed:
            with open(dbConfigFileOut, 'a') as out:
                if identhost in line:
                    out.write(line.replace(identhost, host),)
                elif identuser in line:
                    out.write(line.replace(identuser, user),)
                elif identpw in line:
                    out.write(line.replace(identpw, pw),)
                else:
                    out.write(line)
    #replace base file with the configured dbconnect
    cleanUp(dbConfigFileIn)
    os.rename(dbConfigFileOut, dbConfigFileIn)


#get glge, build
def setupGlge():
    GLGE_SOURCE = "git://github.com/supereggbert/GLGE.git"
    GLGE_DIR = "glge/"
    os.chdir(AVATAR_ROOT)
    subprocess.call("git clone " + GLGE_SOURCE + " " + GLGE_DIR, shell=True)
    os.chdir(AVATAR_ROOT + GLGE_DIR)
    #submodules seems to need a github key to have been set at the computer so omitting these; docs and minifying
    #subprocess.call("git submodule init", shell=True)
    #subprocess.call("git submodule update", shell=True)
    #build
    subprocess.call("./build.js --without-documents", shell=True) #--without-documents


def getJquery():
    import urllib
    filename = "jquery-1.6.1.js"
    url = "http://code.jquery.com/" + filename
    os.chdir(AVATAR_ROOT + "js/")
    source = urllib.urlopen(url).read()
    with open(filename, 'w') as file:
        file.write(source)


if __name__ == "__main__":
    parser = OptionParser()
    parser.add_option("-p", "--password", dest="sudopw")
    (options, args) = parser.parse_args()
    if options.sudopw:
        sudopw = options.sudopw
        passwordSet=True
    main()
