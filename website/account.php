<?php 

    require_once('includes/header.php');
    require("methods.php");
    session_start();
    if ($_SESSION['logged']!=true)
    {
        header('location: login.php');
    }
    echo '<body>
    <form action="account.php" method="post" autocomplete="off">
        <h2>Manage your account below</h2>

        <div class="grid">
            <div>
                <button type="submit" name="evaluation">Request Evaluation</button>
            </div>

            <div>
                <button type="submit" name="delAccount">Delete Account</button>
            </div>

            <div>
                <button type="submit" name="changePassword">Change Password</button>
            </div>

            <div>
                <button type="submit" name="logOut">Log Out</button>
            </div>
        </div>';

        if(isset($_POST['evaluation']))
        {
            header('Location: evaluate.php');
        }
        if(isset($_POST['delAccount']))
        {
            header('Location: delete.php');
        }
        if(isset($_POST['changePassword']))
        {
            header('Location: passchange.php');
        }
        if(isset($_POST['logOut']))
        {
            echo '<br>' . logOut();
        }
        echo '
    </form>';
?>

    
</body>
</html>