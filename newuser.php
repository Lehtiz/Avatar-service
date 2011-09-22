<?php include_once "top.php"; ?>
<center>
<h3>Create a new user</h3>
<br />
<?php
if(isset($_SESSION["logged_in"])){
    print "<p>You are already logged in. You must log out before you can create a new account</p>";
    print "<form method='link' action='action/logoutscript.php'>
    <input type='submit' value='Log out'>
    </form></center>";
}
else {
echo "
<fieldset>
<form name='input' action='action/createuser.php' method='POST'>
<table>
    <tr><td>User:</td><td><input type='text' id='1' name='txtUsername' /></td></tr>
    <tr><td>Email:</td><td><input type='text' name='txtEmail' /></td></tr>
    <tr><td>Password:</td><td><input type='password' name='txtPassword' /></td></tr>
<table>
<br />
<input type='submit' value='Submit' />
</form>
</fieldset>
</center>
";
}
?>
<?php include_once "bottom.php"; ?>
