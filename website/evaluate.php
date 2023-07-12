<?php 
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    if ($_SESSION['logged']!=true)
    {
        header('location: login.php');
    }


    echo '<body>
        <form action="evaluate.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <h2>Enter item name and description</h2>

            <div class="grid">
                <div>
                    <label>Item Name</label>
                    <input type="text" name="itemName" value="' . @$_POST['itemName'] . '">
                </div>

                <div>
                    <label>Item Description</label>
                    <input type="text" name="description" value="' .  @$_POST['description'] . '">
                </div>

                <div>
                    <label>Item Image</label>
                    <input type="file" name="image" value="' .  @$_POST['image'] . '">
                </div>
            </div>

            <button type="submit" name="submit">Submit</button>';

            if(isset($_POST['submit']))
            {
                echo '<br>' . evaluation($_POST['itemName'], $_POST['description']);
            }
            echo '
            <p>
                <a href="account.php">Return to account management.</a>
            </p>
        </form>';
?>
    </body>
</html>