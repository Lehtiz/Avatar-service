<?php
session_start();
if(!$_SESSION["userId"]==1 && !$_SESSION["userName"]=="admin"){
    header("location: index.php");
}
include_once "action/dbconnect.php";

print "
<html><body>

    <form action='action/admin.php' enctype='multipart/form-data' method='POST'>
    <fieldset><legend><h3>Add avatar:</h3></legend><table>
    <input type='hidden' name='mode' value='addavatar' />
    <tr><td>Name: </td><td><input type='text' name='avatarname' /></td></tr>
    <tr><td>Scale: </td><td><input type='text' name='avatarscale' /></td></tr>
    <tr><td>Model: </td><td><input type='file' name='avatarfile' id='file' /></td></tr>
    <tr><td>Texture: </td><td><input type='file' name='avatarfile' id='file' /></td></tr>
    <tr><td><input type='submit' value='Submit' /></td></tr>
    </table></fieldset></form>
";

print "

    <form action='action/admin.php' method='POST'>
    <fieldset><legend><h3>Remove avatar:</h3></legend><table>
    <input type='hidden' name='mode' value='removeavatar'>
    <tr><td><select name='drbavatar'>
    <option value='0'>Select avatar</option>
";
//fetch avatars
$queryavatar = "SELECT * FROM avatar";
$resultavatar = mysql_query($queryavatar);
if(!$resultavatar){
    print mysql_error();
    mysql_close($dbConnection);
    exit;
}
while ($avatarArray = mysql_fetch_assoc($resultavatar)){
    print "<option value='$avatarArray[avatarId]'>$avatarArray[avatarName]</option>";
}
print "
    </td><td><input type='submit' value='Submit' /></td></tr>
    </table></fieldset></form>
";

print " 

    <form action='action/admin.php' method='POST'>
    <fieldset><legend><h3>Moderate users:</h3></legend><table>
    <input type='hidden' name='mode' value='user' />
    <tr><td><input type='radio' name='action' value='remove' checked /> Remove</td>
    <td><input type='radio' name='action' value='edit' /> Edit</td></tr>
    <tr><td>Select user: </td><td><select name='drbuser'>
    <option value='0'>Select user</option>
";
//fetch usernames
$queryuser = "SELECT * FROM user";
$resultuser = mysql_query($queryuser);
if(!$resultuser){
    print mysql_error();
    mysql_close($dbConnection);
    exit;
}
while ($userArray = mysql_fetch_assoc($resultuser)){
    print "<option value='$userArray[userId]'>$userArray[userName]</option>";
}
print "
    </td></tr><tr><td colspan=3>Input and select info to update</td></tr>
    <tr><td><input type='checkbox' name='chkname' /> New name: </td><td><input type='text' name='newname' /></td></tr>
    <tr><td><input type='checkbox' name='chkpass' /> New password: </td><td><input type='text' name='newpassword' /></td></tr>
    <tr><td><input type='submit' value='Submit' /></td></tr>
    </table></fieldset></form>
</body></html>
";
/*



*/

print "<a href='index.php'>Back</a> <a href='action/logoutscript.php'>Log out</a>";
?>