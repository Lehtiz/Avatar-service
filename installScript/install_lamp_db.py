#!/usr/local/bin/python

## TODO: sudo passwd stuff
## meanwhile: RUN AS SUDO "sudo python install_lamp_db.py"
##

import os
import sys
import subprocess
import getpass
from optparse import OptionParser
import pexpect

###
sudopwd = "NOTSET"
passwordSet=False
MYSQL_ROOT_PWD = "fasd"
###



def main():
    if passwordSet:
        installPrograms()
    else:
        print("no password given")
    
    
def installPrograms():
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
    subprocess.call("sudo apt-get install debconf-utils",shell=True)
    subprocess.call("cat " + preseedFile + " | sudo debconf-set-selections", shell=True)
    subprocess.call("sudo apt-get -y install mysql-server", shell=True)
    
    #cleanup ops
    cleanUp(preseedFile)


def setupAvatarService():
    #presume tundra build already (TODO: add tundra build here)
    
    #mod apaches default www home (TODO: custom www home)
    subprocess.call("sudo chown -R " + getpass.getuser() + " /var/www", shell=True)
    
    #get Avatar-service sources
    WEB_ROOT = "/var/www"
    AVATAR_ROOT = WEB_ROOT + "avatar/"
    #check and 
    if os.path.exists(AVATAR_ROOT):
        shutil.move(AVATAR_ROOT, "backup/")
    subprocess.call("git clone git://github.com/Lehtiz/Avatar-service.git " + WEB_ROOT +"/avatar", shell=True)
    
    #setup database
    MYSQL_HOST = "localhost"
    MYSQL_USER = "root"
    MYSQL_USER_PWD = MYSQL_ROOT_PWD # TODO: create custom user ?
    DATABASE_FILE = "avatardb.sql" 
    
    #change cwd to /var/www/avatar
    #import db from file
    subprocess.call("mysql -h" + MYSQL_HOST + " -u" + MYSQL_USER +" -p" + MYSQL_USER_PWD + " < " + AVATAR_ROOT + DATABASE_FILE, shell=True)
    


def cleanUp(file):
    if os.path.isfile(file):
        os.remove(file)

if __name__ == "__main__":
    parser = OptionParser()
    parser.add_option("-p", "--password", dest="sudopwd")
    (options, args) = parser.parse_args()
    if options.sudopwd:
        sudopwd = options.sudopwd
        passwordSet=True
    main()





