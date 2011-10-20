<?php
session_start();
if($_SESSION["logged_in"]==false){
    header("location: index.php");
}
include_once "action/dbconnect.php";


//fetch usernames
$query = "SELECT * FROM user";
$result = mysql_query($query);
if(!$result){
    print mysql_error();
    mysql_close($dbConnection);
    exit;
}
$dataArray = mysql_fetch_assoc($result);

print "
<html><body>
<h3>Add avatar</h3>
    <form action='action/admin.php' enctype='multipart/form-data' method='POST'><table>
    <input type='hidden' name='mode' value='1' />
    <tr><td>Name: </td><td><input type='text' name='avatarname' /></td></tr>
    <tr><td>Scale: </td><td><input type='text' name='avatarscale' /></td></tr>
    <tr><td>File: </td><td><input type='file' name='avatarfile' id='file' /></td></tr>
    <tr><td><input type='submit' value='Submit' /></td></tr>
    </table></form>
    
<h3>Moderate users</h3>
    <form action='action/admin.php' method='POST'><table>
    <input type='hidden' name='mode' value='2' />
    <tr><td><input type='radio' name='action' value='remove' checked /> Remove</td>
    <td><input type='radio' name='action' value='edit' /> Edit</td></tr>
    <tr><td>Select user: </td><td><select name='drbuser'>
    <option value='0'>Select user</option>
";
while ($dataArray = mysql_fetch_assoc($result)){
    if($dataArray['avatarId'] == $selected){
        print "<option value='$dataArray[userId]'>$dataArray[userName]</option>";
    }
}
print "
    </td></tr><tr><td colspan=3>Input and select info to update</td></tr>
    <tr><td><input type='checkbox' name='chkname' /> New name: </td><td><input type='text' name='newname' /></td></tr>
    <tr><td><input type='checkbox' name='chkpass' /> New password: </td><td><input type='text' name='newpassword' /></td></tr>
    <tr><td><input type='submit' value='Submit' /></td></tr>
    </table></form>
</body></html>
";
/*



*/

print "<a href='index.php'>Back</a> <a href='action/logoutscript.php'>Log out</a>";
?>
