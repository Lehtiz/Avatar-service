<?php include_once "top.php"; ?>

<?php
    if ($_SESSION["logged_in"]==true){
        include_once "js.php";
        include_once "action/dbconnect.php";
        
        //db stuff
        $query = "SELECT * FROM avatar";
        $result = mysql_query($query);
        if(!$result){
            print mysql_error();
            mysql_close($dbConnection);
            exit;
        }
        //
    
        print "
            <h3>Edit Identity & Avatar</h3>

            <div id='left'>
                <canvas id='graffa' width='250' height='500'>
                    This text is displayed if your browser does not support HTML5 Canvas.
                </canvas>
            </div>
            <div id='right'>
                <br />
                <form method='post' action='action/saveavatarchanges.php'>
                    Select avatar appearance:<br /><select name='drbavatar'>
        "; //onchange -> get, reload avatar?
        while ($dataArray = mysql_fetch_assoc($result)){
            print "<option value='$dataArray[avatarid]'>$dataArray[avatarname]</option>";
        }
        print "
                    </select><br />
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
