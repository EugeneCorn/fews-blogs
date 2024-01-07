<head>
    <link href="/styles/user_panel.css" rel="stylesheet"></link>
</head>
<ul>
    <li><a class="left_a" href="/">Homepage</a></li>
<?php
    require("db_connection.php");
    // Show register and login invitation for not logged in users.
    if(!isset($_SESSION['username'])) {
        print "
        <li class='right_a'><a href='/login'>Log In</a></li>
        <li class='right_a'><a href='/registration'>Register</a></li>
        <li class='right_a'><a href='/about_us'>About us</a></li>
        ";
    // Show username and logout link for logged in users.
    } else {
        $login_user = $_SESSION['username'];
        $login_user_query = mysqli_query($db_connection, "SELECT * FROM `users` 
            WHERE username='$login_user'");
        $login_user_id = mysqli_fetch_array($login_user_query);
        print "
            <li class='left_a'><a href='/dashboard'>My Articles</a></li>
            <li class='left_a'><a href='/comment/list'>My Comments</a></li>
            <li class='right_a'><a href='/logout'>Log out</a></li>
            <li class='right_a' class='border-none'><a href='/user/".$login_user_id['id']."'>". newlines2br($_SESSION['username']) ."</a></li>
            <li class='right_p'><p>You logged in as: </p></li>
            <li class='right_a'><a href='/about_us'>About us</a></li>
        ";
    }
?>
</ul>
