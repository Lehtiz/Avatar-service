<?php include_once "top.php"; ?>
<center>
<h3>Welcome to realxtend avatar service!</h3>
<br />
<?php
if(isset($_SESSION["logged_in"])){
    print "<p>You are already logged in. You can either log out or continue modifying your <a href='avatar.php'>Avatar</a></p>";
    print "<form method='link' action='action/logoutscript.php'>
    <input type='submit' value='Log out'>
    </form></center>";
}
else {
echo "
<fieldset>
<form method='post' action='action/loginscript.php'>
<table>
<tr><td>Username:</td><td><input type='text' id='1' size='20' maxlength='25' name='txtUsername'></td></tr>
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
