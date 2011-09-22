<?php include_once "top.php"; ?>
<h3>Create a new user:</h3>
<fieldset>
<form name="input" action="action/createuser.php" method="POST">
<table>
    <tr><td>User:</td><td><input type="text" name="txtUsername" /></td></tr>
    <tr><td>Email:</td><td><input type="text" name="txtEmail" /></td></tr>
    <tr><td>Password:</td><td><input type="password" name="txtPassword" /></td></tr>
<table>
<input type="submit" value="Submit" />
</form>
</fieldset>
<?php include_once "bottom.php"; ?>
