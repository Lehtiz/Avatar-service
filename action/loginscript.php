<?php
session_start();
if($_SESSION["logged_in"]==true){
    header("location:../avatar.php");
}

include_once "dbconnect.php";
$username = mysql_real_escape_string(strip_tags($_POST["txtUsername"]));
$password = mysql_real_escape_string(strip_tags($_POST["txtPassword"]));

if(empty($username) || empty($password)){
    header("location:../index.php");
}
else{
    $query = "SELECT username, userpassword FROM user WHERE username = $username";
    $result = mysql_query($query);
    if(!$result){
        $error=mysql_error();
        print $error;
        mysql_close($dbConnection);
        exit;
    }

    $dataArray = mysql_fetch_assoc($result);
    $db_user_name = $dataArray[username];
    $db_user_password = $dataArray[userpassword];
    if (strcmp($username,$db_user_name)==0 && strcmp($password,$db_user_password)==0)
    {
        $_SESSION["logged_in"]=true;

        //if ($rememberMe==1)
        //{
        //    setcookie("username",$username,time() + 60*60*24*365);
        //    setcookie("password",$password,time() + 60*60*24*365);
        //    setcookie("rememberme",$rememberMe,time() + 60*60*24*365);
        //}
        header("location:../avatar.php");
    }
    else
    {
        header("location:../index.php");
    }
}

?>
