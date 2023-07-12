<!--Header-->
<?php 
    require_once('includes/header.php');
    require("methods.php");
    echo '<body>
        <form action="index.php" method="post" autocomplete="off">
            <h2>Register Below</h2>

            <div class="grid">
                <div>
                    <label>Forename</label>
                    <input type="text" name="forename" value="' . @$_POST['forename'] . '">
                </div>

                <div>
                    <label>Surname</label>
                    <input type="text" name="surname" value="' . @$_POST['surname'] . '">
                </div>

                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="' . @$_POST['username'] . '">
                </div>

                <div>
                    <label>Email</label>
                    <input type="text" name="email" value="' .  @$_POST['email'] . '">
                </div>

                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phoneno" value="' .  @$_POST['phoneno'] . '">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" value="' .  @$_POST['password'] . '">
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" value="' .  @$_POST['cpassword'] . '">
                </div>
            </div>

            <button type="submit" name="submit">Submit</button>';

            if(isset($_POST['submit']))
            {
                echo '<br>' . register($_POST['username'], $_POST['email'], $_POST['password'], $_POST['cpassword'], $_POST['phoneno'], $_POST['forename'], $_POST['surname']);
            }

            echo '
            <p>
                Already a member? <a href="login.php">Sign in</a>
            </p>
        </form>';
?>

        
    </body>
</html>

    