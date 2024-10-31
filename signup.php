<?php
include "generalConnection.php";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['re_password'])) {
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        // Retrieve and validate the form data
        $name = validate($_POST['name']);
        $email = validate($_POST['email']);
        $pass = validate($_POST['password']);
        $re_pass = validate($_POST['re_password']);
    
        $user_data = 'email=' . $email . '&name=' . $name;
    
        // Debugging: Display form data received
        echo "Received Data:<br>";
        echo "Name: " . $name . "<br>";
        echo "Email: " . $email . "<br>";
        echo "Password: " . $pass . "<br>";
        echo "Re-entered Password: " . $re_pass . "<br>";
    
        if (empty($email)) {
            header("Location: signup.php?error=Email is required&$user_data");
            exit();
        } else if (empty($pass)) {
            header("Location: signup.php?error=Password is required&$user_data");
            exit();
        } else if (empty($re_pass)) {
            header("Location: signup.php?error=Re-entered password is required&$user_data");
            exit();
        } else if (empty($name)) {
            header("Location: signup.php?error=Name is required&$user_data");
            exit();
        } else if ($pass !== $re_pass) {
            header("Location: signup.php?error=The confirmation password does not match&$user_data");
            exit();
        } else {
            echo "Preparing to execute SQL query...<br>";
    
            $sql = "SELECT * FROM userInfo WHERE userEmail='$email'";
            $result = mysqli_query($data, $sql);
    
            // Check if the email already exists
            if (!$result) {
                echo "Error: " . mysqli_error($data); // Display error if the query fails
            } else if (mysqli_num_rows($result) > 0) {
                header("Location: signup.php?error=The email is taken, try another&$user_data");
                exit();
            } else {
                $sql2 = "INSERT INTO userInfo (userEmail, userPassword, name) VALUES ('$email', '$pass', '$name')";
                // Debugging step: Display the SQL query being executed
                echo "SQL Query: " . $sql2 . "<br>";
                $result2 = mysqli_query($data, $sql2);
                if ($result2) {
                    echo "Data successfully inserted!<br>";
                    header("Location: signup.php?success=Your account has been created successfully");
                    exit();
                } else {
                    // Display error message if the insertion query fails
                    echo "Error inserting data: " . mysqli_error($data);
                    exit();
                }
            }
        }
    } else {
        header("Location: signup.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SIGN UP</title>
    <style>   
        element.style{
            background:blue;
        }
        .form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
        background-color: #fff;
        padding: 20px;
        border-radius: 20px;
        position: relative;
        padding-left:55px;
        }

        .title {
        font-size: 28px;
        color: royalblue;
        font-weight: 600;
        letter-spacing: -1px;
        position: relative;
        display: flex;
        align-items: center;
        padding-left: 30px;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .title::before,.title::after {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        border-radius: 50%;
        left: 0px;
        background-color: royalblue;
        }

        .title::before {
        width: 18px;
        height: 18px;
        background-color: royalblue;
        }

        .title::after {
        width: 18px;
        height: 18px;
        animation: pulse 1s linear infinite;
        }

        .message, .signin {
        color: rgba(88, 87, 87, 0.822);
        font-size: 14px;
        }

        .signin {
        text-align: center;
        }

        .signin a {
        color: royalblue;
        }

        .signin a:hover {
        text-decoration: underline royalblue;
        }

        .flex {
        display: flex;
        width: 100%;
        gap: 6px;
        }

        .form label {
        position: relative;
        }

        .form label .input {
        width: 100%;
        padding: 10px 10px 20px 10px;
        outline: 0;
        border: 1px solid rgba(105, 105, 105, 0.397);
        border-radius: 10px;
        }

        .form label .input + span {
        position: absolute;
        left: 10px;
        top: 15px;
        color: grey;
        font-size: 0.9em;
        cursor: text;
        transition: 0.3s ease;
        }

        .form label .input:placeholder-shown + span {
        top: 15px;
        font-size: 0.9em;
        }

        .form label .input:focus + span,.form label .input:valid + span {
        top: 30px;
        font-size: 0.7em;
        font-weight: 600;
        }

        .form label .input:valid + span {
        color: green;
        }

        .submit {
        border: none;
        outline: none;
        background-color: royalblue;
        padding: 10px;
        border-radius: 10px;
        color: #fff;
        font-size: 16px;
        transform: .3s ease;
        }

        .submit:hover {
        background-color: rgb(56, 90, 194);
        }
        .card{
        
            width:500px;
            height:70%;
            
        }
        .register_doctor{
            margin-top:40px;
            color:#fff;
        }
        @keyframes pulse {
            from {
                transform: scale(0.9);
                opacity: 1;
            }

            to {
                transform: scale(1.8);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="card" style ="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);">
        <form action="#" method="POST" class="form" id="signup-form">
            <h2 class="title">SIGN UP</h2>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            
            <label for="name">
                <input type="text" name="name" id="name" class="input" required placeholder=" ">
                <span>Name</span>
            </label>
            
            <label for="email">
                <input type="email" name="email" id="email" class="input" required placeholder=" ">
                <span>Email</span>
            </label>
            
            <label for="password">
                <input type="password" name="password" id="password" class="input" required placeholder=" ">
                <span>Password</span>
            </label>
            
            <label for="re_password">
                <input type="password" name="re_password" id="re_password" class="input" required placeholder=" ">
                <span>Re-enter your password</span>
            </label>

            <label for="register_doctor"style ="color: grey; padding-top:5px; font-size:12px;">
                <input type="checkbox" name="register_doctor" id="register_doctor" class=""  placeholder=" ">
                <span>Press me if you register as doctor</span>
            </label>
            
            <button type="submit" class="submit">Sign Up</button>
            <p class="signin">Have account? <a href="signin.php">Click here</a></p>
        </form>
    </div>
</body>
</html>