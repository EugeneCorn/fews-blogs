<?php
    include("auth_session.php");
    require("db_connection.php");
    include("mysql_secure_query_functions.php");
    include("website_functions.php");

    $username = $_SESSION['username'];
    $user_query = mysqli_prepare($db_connection,
        "SELECT *
        FROM `users` 
        WHERE username= ? ");
    $secure_stmt_variables = array ($username);
    $user_item = secureMysqliQuerySelect($user_query, $secure_stmt_variables);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile Edit | Fews News</title>
        <link href="/styles/main.css" rel="stylesheet"/>
        <link href="/styles/user_profile_edit.css" rel="stylesheet"/>
        <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
        <!-- Icons kit. -->
        <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
    </head>
    <header class="user_panel">
        <?php include("user_panel.php") ?>
    </header>
    <body>
        <h1>Profile Edit</h1>
        <form action="" method="post">
            <p>Email address</p>
            <input type="email" name="email" placeholder="email@example.com">
            <br>
            <input type="submit" name="change_email" value="Change">
        </form>
<?php
        if (isset($_REQUEST['email'])) {
            $name = "email";
            $value = mysqli_real_escape_string($db_connection, $_REQUEST['email']);
            edit_user_profile($name, $value);
        }
?>
        <form action="" method="post">
            <p>Sex</p>
            <input type="radio" name="gender" value="Female">Female
            <input type="radio" name="gender" value="Male">Male
            <input type="radio" name="gender" value="Cat">Cat
            <input type="radio" name="gender" value="Yes, please.">Yes, please.
            <input type="radio" name="gender" value="Other" checked>Other
            </br>
            <input type="submit" name="change_gender" value="Change">
        </form>
<?php
        if (isset($_REQUEST['gender'])){
            $name = "gender";
            $value = mysqli_real_escape_string($db_connection, $_REQUEST['gender']);
            edit_user_profile($name, $value);
        }
?>
        <form action="" method="post">
            <p>Password</p>
            <input type="password" name="password" placeholder="Password">
            </br>
            <input type="password" name="cpassword" placeholder="Confirm password">
            </br>
            <input type="submit" name="change_password" value="Change">
        </form>
<?php
        if (isset($_REQUEST['password'])){
            if ($_REQUEST['password'] == $_REQUEST['cpassword']){
                $name = "password";
                $password = mysqli_real_escape_string($db_connection, $_REQUEST['password']);
                $value = md5($password);
                edit_user_profile($name, $value);
            } else {
                print "ERROR: Password and Confirmation password are different. Password wasn't changed.";
            }
        }
?>
        <form action="" method="post">
            <p>Bio</p>
            <textarea name="bio" rows="10" cols="60"><?php print $user_item['bio'] ?></textarea>
            <div class="bio_change">
                <input type="submit" name="edit_bio" value="Change">
            </div>
        </form>
<?php
        if (isset($_REQUEST['bio'])){
            $name = "bio";
            $value = mysqli_real_escape_string($db_connection, $_REQUEST['bio']);
            edit_user_profile($name,$value);
        }
?>
    </body>
    <footer class="footer">
        <?php include("footer.php") ?>
    </footer>
    </html>
