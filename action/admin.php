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

    //$uploadDir = "upload/"; //mod for perm "www-data"
    $storageDir = "../models/"; //write perm for apache

    $allowedExtensions = array("dae", "png");

    function isAllowedExtension($fileName) {
        global $allowedExtensions;

        return in_array(end(explode(".", $fileName)), $allowedExtensions);
    }

    if (isAllowedExtension($_FILES["avatarfile"]["name"]))/* && ($_FILES["avatarfile"]["size"] < 20000))*/{
        $tmpname = $_FILES["avatarfile"]["tmp_name"];
        $filename = $_FILES["avatarfile"]["name"];

        if ($_FILES["avatarfile"]["error"] > 0){
            echo "Return Code: " . $_FILES["avatarfile"]["error"] . "<br />";
        }
        else{
            echo "Upload: " . $filename . "<br />";
            echo "Type: " . $_FILES["avatarfile"]["type"] . "<br />";
            echo "Size: " . ($_FILES["avatarfile"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $tmpname . "<br />";

            if (file_exists($storageDir . $filename)){
                echo $filename . " already exists. <br /><br /><a href='../adminform.php'>Back</a>";
            }
            else{
                move_uploaded_file($tmpname, $storageDir . $filename);
                echo "Stored in: " . $storageDir . $filename;

                $query = "INSERT INTO avatar(avatarName, avatarScale, avatarFile) VALUES('$avatarname', '$avatarscale', '$filename')";
                $result = mysql_query($query);
                if(!$result){
                    print mysql_error();
                    mysql_close($dbConnection);
                    exit;
                }
                else{
                    print "<br />Avatar successfully added.";
                    print "<br /><a href='../adminform.php'>Back</a>";
                }
            }
        }
        
    }else{
        print "Invalid file";
        print "<br /><a href='../adminform.php'>Back</a>";
    }
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
