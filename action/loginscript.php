<?php
session_start();

//$username = mysql_real_escape_string($_POST["txtUsername"]); //prevents messing with 's
//$password = mysql_real_escape_string($_POST["txtPassword"]);
$username = $_POST["txtUsername"];
$password = $_POST["txtPassword"];

////admin logon db
//$query = "SELECT user_name, user_password FROM `user` WHERE `user_name` = 'Admin'";
//$result = mysql_query($query);
//if(!$result){
//    $error=mysql_error();
//    print $error;
//    mysql_close($dbConnection);
//    exit;
//}
//$dataArray = mysql_fetch_assoc($result);
//
//$db_user_name = $dataArray[user_name];
//$db_user_password = $dataArray[user_password];
$db_user_name = "Admin";
$db_user_password = "Admin";
//admin logon
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

?>
