realXtend Tundra 1.0.8 compatible Avatar service
================================================

Avatar Service is a way for users to customize the appearance of their avatars using a simple web interface.


Installation using setup_ubuntu.py script
-----------------------------------------

- Prerequisites:
    - Tundra 1.0.8 (preferably with Collada support)

1. Configure realxtend installation directory
    - modify variable "rex" (line 20) in setup_ubuntu.py, default is ~/src/realxtend/

2. Set MySQL root password
    - modify variable "MYSQL_ROOT_PW" (line 32) in setup_ubuntu.py, default is "N73J"

3.1 (Optional) Set whether you want to use a custom mysql user for the service
    - modify variable "useRoot" (line 40) in setup_ubuntu.py, default is "False"

3.2 (Optional) Modify variables "MYSQL_USER" and "MYSQL_USER_PW" (lines 42 and 43) in setup_ubuntu.py

4. Run setup_ubuntu.py as "sudo", otherwise the script won't be executed

Manual installation
-------------------

- Prerequisites:
    - Tundra 1.0.8 (preferably with Collada support), git, nodejs, apache2, php5-mysql, libapache2-mod-php5

1. Copy folder "avatar-service" from naali/tools to your webroot (apache default is /var/www/)

2. Import database from avatardb.sql
    - Set mysql login details in avatar-service/action/dbconnect.php

3. Setup GLGE under avatar-service folder -> "avatar-service/glge" NOTE: Folder name must be "glge", not "GLGE" (git://github.com/supereggbert/GLGE.git)
    - Requires nodejs for building

4. Enable WebSocket for Tundra
    - Create a new file called "websocket.ini" in your naali/bin/pymodules/ folder
    - Copy and paste the following lines to "websocket.ini" and save it:

[websocketserver.NaaliWebsocketServer]
port=9999


Using Avatar service 
--------------------

(At the moment works only with Chrome 12 or earlier version)

- Launch Tundra server and load scene from avatar-service/scene/

- Launch Chromium-browser and go to http://localhost/avatar-service/

- Only admin can add/remove avatar models and remove/modify users 

