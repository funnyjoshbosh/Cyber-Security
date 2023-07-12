<?php

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    function connect()
    {
        $mysqli = new mysqli('localhost','server','km7ujaG4','lovejoy');
        if($mysqli->connect_errno != 0)
        {
            $error = $mysqli->connect_error;
            file_put_contents("db-log.txt", $error, FILE_APPEND);
        }
        else
        {
            return ($mysqli);
        }
    }

    function valid_email($str) 
    {
        return (preg_match("/^(?=.{6,250}$)([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str));
    }
    
    function register($username, $email, $password, $cpassword, $phoneno, $forename, $surname)
    {
        // Connect to DB and store the arguments submitted
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        // Check if password fields are empty
        if (empty($password))
        {
            return ("Please enter a password!");
        }
        if (empty($cpassword)) 
        {
            return ("Please confirm your password!");
        }

        // Check if password fields match
        if ($password != $cpassword)
        {
            return ("Please ensure password fields match!");
        }

        // Validate password strength
        if (strlen($password) < 12)
        {
            return ("Password must be at least 12 characters!");
        }
        if (!preg_match("#[0-9]+#",$password))
        {
            return ("Password must contain at least 1 number!");
        }
        if (!preg_match("#[A-Z]+#",$password))
        {
            return ("Password must contain at least 1 capital character!");
        }
        if (!preg_match("#[a-z]+#",$password))
        {
            return ("Password must contain at least 1 lowercase character!");
        }

        // Check if E-mail is in the valid format
        if(!valid_email($email))
        {
            return ("Invalid email address.");
        }

        // Check if E-Mail already exists
        $statement = $mysqli->prepare("SELECT email FROM accounts WHERE email = ?");
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data != NULL){
            return ("E-Mail already exists, please use a different E-Mail");
        }

        // Check if username already exists
        $statement = $mysqli->prepare("SELECT username FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data != NULL){
            return ("Username already exists, please use a different username");
        }

        // Check if phone number already exists
        $statement = $mysqli->prepare("SELECT phoneno FROM accounts WHERE phoneno = ?");
        $statement->bind_param("s", $phoneno);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data != NULL){
            return ("Phone Number already exists, please use a different phone number");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $verification = randString(12);

        $statement = $mysqli->prepare("INSERT INTO accounts(username, password, email, phoneno, forename, surname, verification) VALUES(?,?,?,?,?,?,?)");
        $statement->bind_param("sssssss", $username, $hashed_password, $email, $phoneno, $forename, $surname, $verification);
        $statement->execute();

        if($statement->affected_rows != 1) 
        {
            return ("Something went wrong. Please try again.");
        } 

        return ("Registration Successful! Please log in.");
    }
    
    function login($username, $password)
    {
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        // Check if username field is empty
        if (empty($username))
        {
            return ("Please enter a username!");
        }

        // Check if password fields are empty
        if (empty($password))
        {
            return ("Please enter a password!");
        }

        // Check if username exists
        $statement = $mysqli->prepare("SELECT username FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data == NULL)
        {
            return ("Username does not exist, please enter a valid username!");
        }

        // Check if password is correct
        $statement = $mysqli->prepare("SELECT password FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);
        if(!password_verify($password, $data))
        {
            return ("Password is invalid, please enter a valid password!");
        }

        // Get customer ID number
        $statement = $mysqli->prepare("SELECT ID FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);

        $_SESSION['userID']=$data;
        $_SESSION['logged']=true;
        header('Location: account.php');
        die();
    }
    
    function loginAdmin($username, $password)
    {
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        // Check if username field is empty
        if (empty($username))
        {
            return ("Please enter a username!");
        }

        // Check if password fields are empty
        if (empty($password))
        {
            return ("Please enter a password!");
        }

        // Check if username exists
        $statement = $mysqli->prepare("SELECT username FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data == NULL)
        {
            return ("Username does not exist, please enter a valid username!");
        }

        // Check if password is correct
        $statement = $mysqli->prepare("SELECT password FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);
        if(!password_verify($password, $data))
        {
            return ("Password is invalid, please enter a valid password!");
        }

        // Check if admin
        $statement = $mysqli->prepare("SELECT admin FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if(!$data)
        {
            return ("You do not have permission for this site!");
        }

        // Get customer ID number
        $statement = $mysqli->prepare("SELECT ID FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);

        $_SESSION['userID'] = $data;
        $_SESSION['logged'] = true;
        $_SESSION['admin'] = true;
        header('Location: evaluations.php');
        die();
    }

    function logOut()
    {
        session_destroy();
        header('Location: login.php');
    }

    function randString($len)
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $data = substr(str_shuffle($chars), 0, $len);
        return $data;
    }

    function changePassword($oldPass, $newPass)
    {
        $mysqli = connect();

        $statement = $mysqli->prepare("SELECT password FROM accounts WHERE ID = ?");
        $statement->bind_param("s", $_SESSION['userID']);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);

        if(!password_verify($oldPass, $data))
        {
            return ("Old password is invalid, please enter a valid password!");
        }

        // Validate password strength
        if (strlen($newPass) < 12)
        {
            return ("Password must be at least 12 characters!");
        }
        if (!preg_match("#[0-9]+#",$newPass))
        {
            return ("Password must contain at least 1 number!");
        }
        if (!preg_match("#[A-Z]+#",$newPass))
        {
            return ("Password must contain at least 1 capital character!");
        }
        if (!preg_match("#[a-z]+#",$newPass))
        {
            return ("Password must contain at least 1 lowercase character!");
        }

        $hashed_password = password_hash($newPass, PASSWORD_DEFAULT);

        $statement = $mysqli->prepare("UPDATE accounts SET password = ? WHERE id = ?");
        $statement->bind_param("ss", $hashed_password, $_SESSION['userID']);
        $statement->execute();

        return ("Password Updated!");
    }

    function resetPassword($email)
    {
        // Connect to DB and store the arguments submitted
        $mysqli = connect();

        $newPass = randString(20);
        $hashed_password = password_hash($newPass, PASSWORD_DEFAULT);

        $statement = $mysqli->prepare("UPDATE accounts SET password = ? WHERE email = ?");
        $statement->bind_param("ss", $hashed_password, $email);
        $statement->execute();

        return($newPass);
    }

    function resetPasswordRequest($email)
    {
        // Connect to DB and store the arguments submitted
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        // Check if E-mail is in the valid format
        if(!valid_email($email))
        {
            return ("Invalid email address.");
        }

        // Check if E-Mail exists
        $statement = $mysqli->prepare("SELECT email FROM accounts WHERE email = ?");
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data == NULL){
            return ("E-Mail doesn't exist, please use a valid E-Mail");
        }

        $newPass = resetPassword($email);

        //Create an instance; passing `true` enables exceptions
        require_once('PHPMailer/src/Exception.php');
        require_once('PHPMailer/src/PHPMailer.php');
        require_once('PHPMailer/src/SMTP.php');
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.office365.com';                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'jw.lovejoy@outlook.com';               //SMTP username
            $mail->Password   = 'lovejoy123';                           //SMTP password
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('jw.lovejoy@outlook.com', 'Lovejoy Antique');
            $mail->addAddress($email);            //Add a recipient

            //Content
            $mail->isHTML(true);                                        //Set email format to HTML
            $mail->Subject = 'Password Recovery';
            $mail->Body    = '<p>Your password has been reset. It is now ' . $newPass . '. We encourage you change this in the account page.';
            $mail->AltBody = 'Your password has been reset. It is now ' . $newPass . '. We encourage you change this in the account page.';

            $mail->send();
            echo 'Message has been sent';
            header('Location: login.php');
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function evaluation($itemName, $description)
    {
        // Connect to DB and store the arguments submitted
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        if (strlen($itemName) > 50)
        {
            return ("Item name too long!");
        }

        if (strlen($description) > 255)
        {
            return ("Description too long!");
        }

        // Image checking and storage
        if ($_FILES['image']['size'] > 0)
        {
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_type = $_FILES['image']['type'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));

            if ($file_ext != "png" && $file_ext != "jpeg" && $file_ext != "jpg")
            {
                return ("extension not allowed, please choose a JPEG or PNG file.");
            }
            if($file_size > 20000000) 
            {
                return ('File size must be no larger than 20 MB');
            }

            $image_content = file_get_contents($_FILES['image']['tmp_name']);
        }

        // Insert data into evaluation table
        $statement = $mysqli->prepare("INSERT INTO evaluations(name, description, image, imagetype, accID) VALUES(?,?,?,?,?)");
        $statement->bind_param("ssssi", $itemName, $description, $image_content, $file_ext, $_SESSION['userID']);
        $statement->execute();

        return ("Submission Successful!");
    }

    function getEvaluations()
    {
        $mysqli = connect();

        $data = $mysqli->query("SELECT 
            evaluations.ID, evaluations.name, evaluations.description, evaluations.image, evaluations.imagetype, evaluations.accID,
            accounts.forename, accounts.surname, accounts.email, accounts.phoneno 
            FROM accounts INNER JOIN evaluations ON evaluations.accID = accounts.ID
            ORDER BY evaluations.ID ASC;");
            
        return ($data);
    }

    function delAccount($username, $password)
    {
        $mysqli = connect();
        $args = func_get_args();

        // Remove whitespace from arguments
        $args = array_map(function($value){
            return trim($value);
        }, $args);

        // Prevent empty fields
        foreach ($args as $value) {
            if(empty($value)){
                return ("all fields are required");
            }
        }

        // Prevent <> characters from input fields
        foreach ($args as $value) {
            if (preg_match("/([<|>])/", $value)) {
                return ("<> characters are invalid");
            }
        }

        // Check if username field is empty
        if (empty($username))
        {
            return ("Please enter a username!");
        }

        // Check if password field is empty
        if (empty($password))
        {
            return ("Please enter a password!");
        }

        // Check if username exists
        $statement = $mysqli->prepare("SELECT username FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        if($data == NULL)
        {
            return ("Username does not exist, please enter a valid username!");
        }

        // Check if password is correct
        $statement = $mysqli->prepare("SELECT password FROM accounts WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();
        $data = implode($data);
        if(!password_verify($password, $data))
        {
            return ("Password is invalid, please enter a valid password!");
        }

        // Perform evaluations deletion
        $statement = $mysqli->prepare("DELETE FROM evaluations WHERE accID = ?");
        $statement->bind_param("s", $_SESSION['userID']);
        $statement->execute();

        // Perform account deletion
        $statement = $mysqli->prepare("DELETE FROM accounts WHERE ID = ?");
        $statement->bind_param("s", $_SESSION['userID']);
        $statement->execute();

        header('Location: index.php');
    }
?>