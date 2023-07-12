<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    echo '<body>
    <form action="passreset.php" method="post" autocomplete="off">
            <h2>Register Below</h2>

            <div class="grid">
                <div>
                    <label>E-Mail</label>
                    <input type="text" name="email" value="' . @$_POST['email'] . '">
                </div>
            </div>

            <button type="submit" name="submit">Submit</button>';

            if(isset($_POST['submit']))
            {
                echo '<br>' . resetPasswordRequest($_POST['email']);
            }

            echo '
            <p>
                Remember your password? <a href="login.php">Sign in</a>
            </p>';
?>
    </body>
</html>