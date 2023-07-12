<?php 
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    if ($_SESSION['logged']!=true)
    {
        header('location: login.php');
    }
    echo '<body>
        <form action="passchange.php" method="post" autocomplete="off">
            <h2>Sign in</h2>

            <div class="grid">
                <div>
                    <label>Old Password</label>
                    <input type="password" name="oldpass" value="' . @$_POST['oldpass'] . '">
                </div>

                <div>
                    <label>New Password</label>
                    <input type="password" name="newpass" value="' .  @$_POST['newpass'] . '">
                </div>

            </div>

            <button type="submit" name="submit">Submit</button>';

            if(isset($_POST['submit']))
            {
                echo '<br>' . changePassword($_POST['oldpass'], $_POST['newpass']);
            }

            echo '
            <p>
                <a href="account.php">Return to account management.</a>
            </p>
        </form>';
?>
    </body>
</html>