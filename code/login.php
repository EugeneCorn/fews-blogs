<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Fews Blogs</title>
</head>
<body>
    <h1>Login</h1>
<?php
        require('db_connection.php');
        session_start();
        // When form submitted create user session.
        if (isset($_POST['username'])) {
            $username   = stripslashes($_REQUEST['username']); // Remove backslashes.
            $username   = mysqli_real_escape_string($db_connection, $username);
            $password   = stripslashes($_REQUEST['password']);
            $password   = mysqli_real_escape_string($db_connection, $password);
            // Check does the user exist in a database.
            $user_query      = "SELECT * FROM `users` WHERE username='$username' AND password='" . md5($password) . "'";
            $result     = mysqli_query($db_connection, $user_query) or die(mysql_error());
            $rows       = mysqli_num_rows($result);

            if ($rows == 1) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
            } else {
                echo "<script> alert('Incorrect Username or Password') </script></br>";
                echo "<script> window.location='login.php' </script>";
                exit();
            }
        } else {
?>
        <form method="post" name="login">
            <input type="text" name="username" placeholder="Username" autofocus="true">
            </br>
            <input type="password" name="password" placeholder="Password">
            </br>
            <input type="submit" value="Login" name="submit">
            </br>
            <p><a href="registration.php">Register</a></p>
        </from>            
<?php   }   ?>
    <p>Back to <a href="index.php">Homepage</a></p>
</body>
</html>
