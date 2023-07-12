<?php 
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    echo '<body>
        <form action="login.php" method="post" autocomplete="off">
            <h2>Sign in</h2>

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
                echo '<br>' . login($_POST['username'], $_POST['password']);
            }

            echo '
            <p>
                Already a member? <a href="index.php">Register here</a>
                <br>
                Forgot your password? <a href="passreset.php">Reset here</a>
                <br>
                <a href="admin.php">Admin Login</a>
            </p>
        </form>';
?>
    </body>
</html>