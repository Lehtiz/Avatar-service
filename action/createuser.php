<?php include_once "top.php"; ?>

<?php 
include_once "dbconnect.php";

$username = strip_tags($_POST["txtUsername"]);
$email = strip_tags($_POST["txtEmail"]);
$password = strip_tags($_POST["txtPassword"]);


$query = "INSERT INTO user(username, useremail, userpassword) VALUES('$username', '$email', '$password')";

$result = mysql_query($query);
if ($result){
    header("location: ../avatar.php");
}
else{
    $error = mysql_error();
    print "$error <br/>";
    print "<a href='../newuser.php'>Back</a>";
}

?>

<?php include_once "bottom.php"; ?>
