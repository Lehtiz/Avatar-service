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

    $filesize_limit = 2000000;  //max file size 2mb

    //$uploadDir = "upload/"; //mod for perm "www-data"
    $storageDir = "../models/"; //write perm for apache

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
            if (file_exists($storageDir . $filename_model)){
                echo $filename_model . " already exists";
            }
            else{
                move_uploaded_file($tmpname, $storageDir . $filename_model);
                echo "Stored in: " . $storageDir . $filename_model;

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
            if (file_exists($storageDir . $filename_texture)){
                echo $filename_texture . " already exists";
            }
            else{
                move_uploaded_file($tmpname, $storageDir . $filename_texture);
                echo "<br />Stored in: " . $storageDir . $filename_texture;

                    print "<br />Avatar texture successfully added";
            }
        }
    }
    else if (isAllowedExtension($filename_texture, 2) && $filesize_texture > $filesize_limit){
        print "Texture file is too big to upload";
    }
    else{
        print "Invalid texture file";
    }
    print "<br /><a href='../adminform.php'>Back</a>";
}
else if($selection == 'removeavatar'){
    $avatarid = $_POST['drbavatar'];
    if($avatarid != 0){
        //del useravatar
        $query1 = "DELETE FROM useravatar WHERE avatarId='$avatarid'";
        $result1 = mysql_query($query1);
        if(!$result1){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        //del avatar
        $query2 = "DELETE FROM avatar WHERE avatarId='$avatarid'";
        $result2 = mysql_query($query2);
        if(!$result2){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        if($result1 && $result2){
            print "operation successful. <a href='../adminform.php'>Back</a>";
        }
        //del avatar files
    }
    else{
        print "no avatar selected. <a href='../adminform.php'>Back</a>";
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
                print "operation successful. <a href='../adminform.php'>Back</a>";
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
                print "no fields selected <a href='../adminform.php'>Back</a>";
            }
            
            $result = mysql_query($query);
            if(!$result){
                print mysql_error();
                mysql_close($dbConnection);
                exit;
            }
            else{
                print "operation successful. <a href='../adminform.php'>Back</a>";
            }
        }
    }
}
?>
