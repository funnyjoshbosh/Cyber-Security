<?php 
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    if ($_SESSION['logged']!=true)
    {
        header('location: login.php');
    }
    echo '<body>
        <form action="delete.php" method="post" autocomplete="off">
            <h2>Please Sign in again to confirm account deletion</h2>
            <br>
            <p><b>This cannot be undone!</b></p>

            <div class="grid">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="' . @$_POST['username'] . '">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" value="' .  @$_POST['password'] . '">
                </div>
            </div>

            <button type="submit" name="submit">Submit</button>';

            if(isset($_POST['submit']))
            {
                echo '<br>' . delAccount($_POST['username'], $_POST['password']);
            }

            echo '
            <p>
                <a href="account.php">Return to account management.</a>
            </p>
        </form>';
?>
    </body>
</html>