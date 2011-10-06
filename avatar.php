<?php include_once "top.php"; ?>
<?php include_once "js.php"; ?>
<?php
    if ($_SESSION["logged_in"]==true){
        print "
        <h3>Edit Identity & Avatar</h3>

        <div id='left'>
            <canvas id='graffa' width='600' height='338'>
                This text is displayed if your browser does not support HTML5 Canvas.
            </canvas>
        </div>
        <div id='right'>
            <br />
            <form method='post' action='action/saveavatarchanges.php'>
                <input type='radio' name='group1' value='option1' checked> Option1<br />
                <input type='radio' name='group1' value='option2'> Option2<br />
                <input type='radio' name='group1' value='option3'> Option3<br />
                <br />
                <input type='submit' value='Save changes' />
            </form>
        
        <form method='link' action='action/logoutscript.php'>
            <input type='submit' value='Log out'>
        </form>
        </div>
        ";
    }
    else
        print "<p>You must <a href='index.php'>log in</a> to enter this page</p>";
?>
<?php include_once "bottom.php"; ?>
