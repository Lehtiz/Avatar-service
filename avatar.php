<?php include_once "top.php"; ?>
<center>
<h3>Edit Identity & Avatar</h3>
<br />
<form method="post" action="modifyavatar.php">
<input type="radio" name="group1" value="option1" checked> Option1<br />
<input type="radio" name="group1" value="option2"> Option2<br />
<input type="radio" name="group1" value="option3"> Option3<br />
<br />
<input type="submit" value="Save changes" />
</form>
<?php
    if (isset($_SESSION["logged_in"])){
    print "<form method='link' action='action/logoutscript.php'>
    <input type='submit' value='Log out'>
    </form></center>";
    }
    else
    print "</center>";
?>
<?php include_once "bottom.php"; ?>
