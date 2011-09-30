<?php include_once "top.php"; ?>
<?php
    if ($_SESSION["logged_in"]==true){
        print "
        <center>
        <h3>Edit Identity & Avatar</h3>
        <script type='text/javascript'>
            websocket_host = 'localhost';
            websocket_port = '9999';
        </script>
        <script type='text/javascript' src='js/global.js'></script>
        <script type='text/javascript' src='js/3d.js'></script>
        <script type='text/javascript' src='js/entities.js'></script>
        <script type='text/javascript' src='js/socket.js'></script>
            
        <div id='left'>
            <canvas id='avatar' width='250' height='500'>
                This text is displayed if your browser does not support HTML5 Canvas.
            </canvas>
        </div>
        <div id='right'>
            <form method='post' action='action/saveavatarchanges.php'>
                <input type='radio' name='group1' value='option1' checked> Option1<br />
                <input type='radio' name='group1' value='option2'> Option2<br />
                <input type='radio' name='group1' value='option3'> Option3<br />
                <br />
                <input type='submit' value='Save changes' />
            </form>
        </div>
        <form method='link' action='action/logoutscript.php'>
            <input type='submit' value='Log out'>
        </form>
        ";
    }
    else
        print "<p>You must <a href='index.php'>log in</a> to enter this page</p>";
?>
<?php include_once "bottom.php"; ?>
