<?php 
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    echo '<body>
        <form action="admin.php" method="post" autocomplete="off">
            <h2>Administrator Sign in</h2>

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
                echo '<br>' . loginAdmin($_POST['username'], $_POST['password']);
            }

            echo '
            <p>
                Not an admin? <a href="login.php">Return to sign in!</a>
            </p>
        </form>';
?>
    </body>
</html>