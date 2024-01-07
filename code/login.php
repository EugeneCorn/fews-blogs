<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Fews Blogs</title>
    <link href="styles/main.css" rel="stylesheet"/>
    <link href="styles/login.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
    <!-- Icons kit. -->
    <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
</head>
<header class="user_panel">
    <?php include("user_panel.php") ?>
</header>
<body>
    <h1>Login</h1>
<?php
        require('db_connection.php');
        include('mysql_secure_query_functions.php');
        session_start();
        // When form submitted create user session.
        if (isset($_POST['username'])) {

            $username   = $_REQUEST['username'];
            $password   = $_REQUEST['password'];
            $md5Password = md5($password);

            // Check does the user exist in a database.
            $user_query = mysqli_prepare($db_connection,
                "SELECT
                COUNT(id)
                AS users_number
                FROM `users`
                WHERE username= ?
                  AND password= ? ") or die(mysqli_error($db_connection));

            $secure_stmt_variables = array($username, $md5Password);
            $rows = secureMysqliQuerySelect($user_query, $secure_stmt_variables);

            if ($rows['users_number'] == 1) {
                $_SESSION['username'] = $username;
                header("Location: /");
            } else {
                echo "<script> alert('Incorrect Username or Password') </script></br>";
                echo "<script> window.location='/login' </script>";
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
            <p>Don't have an account yet? <a href="/registration">Register</a></p>
        </form>
<?php   }   ?>
</body>
<footer class="footer">
    <?php include("footer.php") ?>
</footer>
</html>
