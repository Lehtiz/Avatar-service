<?php include_once "top.php"; ?>
<h3>Welcome to realxtend avatar service!</h3>
<br />
<?php
if($_SESSION["logged_in"]==true){
    print "<p>You are already logged in.<br /> You can either <a href='action/logoutscript.php'>log out</a> or continue modifying your <a href='avatar.php'>Avatar</a></p>";
}
else {
echo "
<fieldset>
<form action='action/loginscript.php' method='POST'>
<center><table>
    <tr><td>Username:</td><td><input type='text' id='1' size='20' maxlength='25' name='txtUsername'></td></tr>
    <tr><td>Password:</td><td><input type='password' size='20' maxlength='25' name='txtPassword'></td></tr>
</table></center>
<br />
<input type='submit' value='Log in' />
</form>
</fieldset>
<br />
Don't have an account yet?&nbsp;<a href='newuser.php'>Register</a>
";
}
?>
<?php include_once "bottom.php"; ?>
