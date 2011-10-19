<?php include_once "top.php"; ?>
<h3>Create a new user</h3>
<br />
<?php
if($_SESSION["logged_in"]==true){
    $avatarId = $_SESSION['selectedAvatarId'];
    header("location:avatar.php?avatar=" . $avatarId);
}
else {
echo "
<fieldset>
<form name='input' action='action/createuser.php' method='POST'>
<center><table>
    <tr><td>User:</td><td><input type='text' id='1' name='txtUsername' /></td></tr>
    <tr><td>Email:</td><td><input type='text' name='txtEmail' /></td></tr>
    <tr><td>Password:</td><td><input type='password' name='txtPassword' /></td></tr>
<table></center>
<br />
<table>
<tr><td><input type='submit' value='Submit' /></td>
<td><input type='button' value='Cancel' onclick='window.location.href=&quot;index.php&quot;'></td></tr>
</table>
</form>
</fieldset>
";
}
?>
<?php include_once "bottom.php"; ?>
