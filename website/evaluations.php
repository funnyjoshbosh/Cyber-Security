<?php
    require_once('includes/header.php');
    require("methods.php");
    session_start();
    if ($_SESSION['logged']!=true)
    {
        header('location: login.php');
    }
    if ($_SESSION['admin']!=true)
    {
        header('location: login.php');
    }

    $data = getEvaluations();

    if ($data->num_rows > 0) {
        // output data of each row
        while($row = $data->fetch_assoc()) {
            echo "
            Evaluation ID: ". $row["ID"]. " |---| Customer: ". $row["forename"]. " " . $row["surname"] . " |---| Customer ID: " . $row["accID"] . "<br>" .
            " Item Name: " . $row["name"] . "<br>" .
            " Description: " . $row["description"] . "<br>" .
            '<img src="data:'.$row['imagetype'].';base64,'.base64_encode($row['image']).'"/>'."<br>" .
            " Phone Number: " . $row["phoneno"] . " |---| Email: " . $row["email"] . "<br>" .
            "____________________________________________________________________________________<br><br>";
        }
    } 
    else 
    {
        echo "0 results";
    }
?>