<?php include_once "top.php"; ?>
<?php
    if ($_SESSION["logged_in"]==true){

    print "
    <center>
    <h3>Edit Identity & Avatar</h3>
    <br />
    <form method='post' action='modifyavatar.php'>
    <input type='radio' name='group1' value='option1' checked> Option1<br />
    <input type='radio' name='group1' value='option2'> Option2<br />
    <input type='radio' name='group1' value='option3'> Option3<br />
    <br />
    <input type='submit' value='Save changes' />
    </form>

    <form method='link' action='action/logoutscript.php'>
    <input type='submit' value='Log out'>
    </form>";
    }
    else
    print "<p>You must <a href='index.php'>log in</a> to enter this page</p>";
?>
<?php include_once "bottom.php"; ?>
