<?php include_once "../top.php"; ?>

<?php 
include_once "dbconnect.php";

$username = mysql_real_escape_string(strip_tags($_POST["txtUsername"]));
$email = mysql_real_escape_string(strip_tags($_POST["txtEmail"]));
$password = mysql_real_escape_string(strip_tags($_POST["txtPassword"]));

if(empty($username) || empty($email) || empty($password)){
    header("location:../newuser.php");
}
else{
    $query = "INSERT INTO user(username, useremail, userpassword) VALUES('$username', '$email', '$password')";

    $result = mysql_query($query);
    if ($result){
        $_SESSION["logged_in"]=true;
        header("location: ../avatar.php");
    }
    else{
        $error = mysql_error();
        print "$error <br/>";
        print "<a href='../newuser.php'>Back</a>";
    }
    
}
?>

<?php include_once "../bottom.php"; ?>
