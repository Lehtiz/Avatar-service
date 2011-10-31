<?php
session_start();
if(!$_SESSION["userId"]==1 && !$_SESSION["userName"]=="admin"){
    header("location: index.php");
}
include_once "dbconnect.php";
$selection = $_POST['mode'];

if($selection == 'addavatar'){ //add avatar
    $avatarname = $_POST['avatarname'];
    $avatarscale = $_POST['avatarscale'];

    $filename_model = $_FILES["avatarfile"]["name"][0];
    $filename_texture = $_FILES["avatarfile"]["name"][1];

    $filesize_model = $_FILES["avatarfile"]["size"][0];
    $filesize_texture = $_FILES["avatarfile"]["size"][1];

    $filesize_limit = 5000000;  //max file size 5mb

    $uploadDir = "../models/"; //mod for perm "www-data"
    $storageDir = $uploadDir . $avatarname . "/";
    $modelDir = $storageDir . "models/"; //write perm for apache
    $imageDir = $storageDir . "images/";
    
    //create directory
    mkdir($storageDir);
    mkdir($modelDir);
    mkdir($imageDir);

    $allowedExtensions_model = array("dae");
    $allowedExtensions_texture = array("png");

    function isAllowedExtension($fileName, $mode) {
        global $allowedExtensions_model;
        global $allowedExtensions_texture;

        if ($mode == 1){
            return in_array(end(explode(".", $fileName)), $allowedExtensions_model);
        }
        else if ($mode == 2){
            return in_array(end(explode(".", $fileName)), $allowedExtensions_texture);
        }
    }

    if (isAllowedExtension($filename_model, 1) && $filesize_model <= $filesize_limit){
        $tmpname = $_FILES["avatarfile"]["tmp_name"][0];

        if ($_FILES["avatarfile"]["error"][0] > 0){
            echo "Return Code: " . $_FILES["avatarfile"]["error"][0] . "<br />";
        }
        else{
            if (file_exists($modelDir . $filename_model)){
                echo $filename_model . " already exists";
            }
            else{
                move_uploaded_file($tmpname, $modelDir . $filename_model);
                echo "Stored in: " . $modelDir . $filename_model;

                $query = "INSERT INTO avatar(avatarName, avatarScale, avatarFile) VALUES('$avatarname', '$avatarscale', '$filename_model')";
                $result = mysql_query($query);
                if(!$result){
                    print mysql_error();
                    mysql_close($dbConnection);
                    exit;
                }
                else{
                    print "<br />Avatar model successfully added";
                }
            }
        }
    }
    else if (isAllowedExtension($filename_model, 1) && $filesize_model > $filesize_limit){
        print "Model file is too big to upload<br />";
    }
    else{
        print "Invalid model file<br />";
    }

    if (isAllowedExtension($filename_texture, 2) && $filesize_texture <= $filesize_limit){
        $tmpname = $_FILES["avatarfile"]["tmp_name"][1];

        if ($_FILES["avatarfile"]["error"][1] > 0){
            echo "Return Code: " . $_FILES["avatarfile"]["error"][1] . "<br />";
        }
        else{
            if (file_exists($imageDir . $filename_texture)){
                echo $filename_texture . " already exists";
            }
            else{
                move_uploaded_file($tmpname, $imageDir . $filename_texture);
                echo "<br />Stored in: " . $imageDir . $filename_texture;

                    print "<br />Avatar texture successfully added";
            }
        }
    }
    else if (isAllowedExtension($filename_texture, 2) && $filesize_texture > $filesize_limit){
        print "Texture file is too big to upload";
    }
    else{
        print "<br />Invalid texture file";
    }
    print "<br /><a href='../adminform.php'>Back</a>";
}
else if($selection == 'removeavatar'){
    $avatarid = $_POST['drbavatar'];
    if($avatarid != 0){
        //del useravatar links
        $query1 = "DELETE FROM useravatar WHERE avatarId='$avatarid'";
        $result1 = mysql_query($query1);
        if(!$result1){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        //del avatar files
        $query3 = "SELECT * FROM avatar WHERE avatarId='$avatarid'";
        $result3 = mysql_query($query3);
        if(!$result3){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        $avatarfile = mysql_fetch_assoc($result3);
        #$folder = "../models/" . $avatarfile['avatarName'] . "/models/";
        #print $folder . $avatarfile['avatarFile'];
        #unlink($folder . $avatarfile['avatarFile']);
        $modelfolder = "../models/" . $avatarfile['avatarName'] . "/";
        
        $d = $modelfolder . "models/";
        foreach(glob($d.'*.*') as $v){
            unlink($v);
        }
        $d2 = $modelfolder . "images/";
        foreach(glob($d2.'*.*') as $v2){
            unlink($v2);
        }
        rmdir($d);
        rmdir($d2);
        rmdir($modelfolder);        
        
        //check if texture with the same name exists
        //check for (avatarfile - suffix), delete this .png
        //del avatar from db
        $query2 = "DELETE FROM avatar WHERE avatarId='$avatarid'";
        $result2 = mysql_query($query2);
        if(!$result2){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        if($result1 && $result2){
            print "<br />operation successful. <a href='../adminform.php'>Back</a>";
        }   
    }
    else{
        print "<br />no avatar selected. <a href='../adminform.php'>Back</a>";
    }
}
else if($selection == 'user'){ //user stuff
    $action = $_POST['action'];
    $userid = $_POST['drbuser'];
    if($userid != 0){
        if($action == 'remove'){
            //del useravatar info
            $query1 = "DELETE FROM useravatar WHERE userId='$userid'";
            $result1 = mysql_query($query1);
            if(!$result1){
                print mysql_error();
                mysql_close($dbConnection);
                exit;
            }
            //del user
            $query2 = "DELETE FROM user WHERE userId='$userid'";
            $result2 = mysql_query($query2);
            if(!$result2){
                print mysql_error();
                mysql_close($dbConnection);
                exit;
            }
            if($result1 && $result2){
                print "<br />operation successful. <a href='../adminform.php'>Back</a>";
            }
        }
        if($action == 'edit'){
            $newname = mysql_real_escape_string(strip_tags($_POST['newname']));
            $newpass = crypt(mysql_real_escape_string(strip_tags($_POST['newpassword'])));
            
            $cbname = $_POST['chkname'];
            $cbpass = $_POST['chkpass'];
            if($cbname && !$cbpass){
                $query = "UPDATE user SET userName='$newname' WHERE userId='$userid'";
            }
            if(!$cbname && $cbpass){
                $query = "UPDATE user SET userPassword='$newpass' WHERE userId='$userid'";
            }
            if($cbname && $cbpass){
                $query = "UPDATE user SET userName='$newname', userPassword='$newpass' WHERE userId='$userid'";
            }
            if(!$cbname && !$cbpass){
                print "<br />no fields selected <a href='../adminform.php'>Back</a>";
            }
            
            $result = mysql_query($query);
            if(!$result){
                print mysql_error();
                mysql_close($dbConnection);
                exit;
            }
            else{
                print "<br />operation successful. <a href='../adminform.php'>Back</a>";
            }
        }
    }
}
?>
