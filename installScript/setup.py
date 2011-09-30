#!/usr/local/bin/python

## TODO: sudo passwd stuff
## meanwhile: RUN AS SUDO "sudo python install_lamp_db.py"
##

import os
import sys
import subprocess
import shutil
import getpass
from optparse import OptionParser
import pexpect

###
sudopwd = "NOTSET"
passwordSet=False

#avatar service sources
GIT_SOURCE = "git://github.com/Lehtiz/Avatar-service.git"
WEB_ROOT = "/var/www/"
AVATAR_ROOT = WEB_ROOT + "avatar/"

#rootpw set during install
MYSQL_HOST = "localhost"
MYSQL_ROOT = "root"
MYSQL_ROOT_PWD = "N73J"

MYSQL_USER = "avatarservice"
MYSQL_USER_PWD = "avatarpw123" # TODO: create custom user ?

DATABASE_FILE = "avatardb.sql" 
###


def main():
    #installPrograms()
    #setupAvatarService()
    createMysqlUser()
"""
    if passwordSet:
        installPrograms()
        setupAvatarService()
    else:
        print("no password given")
"""
    
def installPrograms():

    #preq for mysql installation parameters, git sources
    subprocess.call("sudo apt-get install debconf-utils git",shell=True)
    
    #apache2, php
    subprocess.call("sudo apt-get -y install apache2 php5-mysql libapache2-mod-php5", shell=True)
    
    #MYSQL
    #create preseed file
    preseedFile = "mysql.preseed"
    
    #make sure file does not exist, remove if it does
    if os.path.isfile(preseedFile):
        os.remove(preseedFile)
        
    with open(preseedFile, 'a') as pre:
        pre.write("mysql-server-5.1 mysql-server/" + "root_password password " + MYSQL_ROOT_PWD + "\n")
        pre.write("mysql-server-5.1 mysql-server/" + "root_password_again password " + MYSQL_ROOT_PWD + "\n")
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
    subprocess.call("mysql -h" + MYSQL_HOST + " -u" + MYSQL_USER +" -p" + MYSQL_USER_PWD + " < " + AVATAR_ROOT + DATABASE_FILE, shell=True)


def cleanUp(file):
    if os.path.isfile(file):
        os.remove(file)


def createMysqlUser():
    DB_NAME = "avatar"
    createUserTmpFile = "dbuser.sql"
    if os.path.isfile(createUserTmpFile):
        os.remove(createUserTmpFile)
    with open(createUserTmpFile, 'a') as f:
        f.write("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON " + DB_NAME + ".* TO '" + MYSQL_USER + "'@'" + MYSQL_HOST + "' IDENTIFIED BY '" + MYSQL_USER_PWD + "'")
    subprocess.call("mysql -h" + MYSQL_HOST + " -u" + MYSQL_ROOT +" -p" + MYSQL_ROOT_PWD + " < " + createUserTmpFile, shell=True)
    cleanUp(createUserTmpFile)


if __name__ == "__main__":
    parser = OptionParser()
    parser.add_option("-p", "--password", dest="sudopwd")
    (options, args) = parser.parse_args()
    if options.sudopwd:
        sudopwd = options.sudopwd
        passwordSet=True
    main()
