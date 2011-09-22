<?php include_once "top.php"; ?>
<?php
if(isset($_SESSION["logged_in"])){
     print "Olet jo kirjautunut sisään, <a href='logoutscript.php'>Kirjaudu ulos</a> tai jatka <a href='admin.php'>Admin</a>.";

}
else {
echo "
<center>
<h3>Welcome to realxtend avatar service!</h3>
<br />
<fieldset>
<form method='post' action='action/loginscript.php'>
<table>
<tr><td>Username:</td><td><input type='text' size='20' maxlength='25' name='txtUsername'></td></tr>
<tr><td>Password:</td><td><input type='password' size='20' maxlength='25' name='txtPassword'></td></tr>
</table>
<br />
<input type='submit' value='Log in' />
</form>
</fieldset>
<br />
Don't have an account yet?&nbsp;<a href='newuser.php'>Register</a>
</center>
";
}
?>
<?php include_once "bottom.php"; ?>
