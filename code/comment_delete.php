<?php
    include("auth_session.php");

    if($_SERVER['REQUEST_METHOD'] == "GET") {

        require("db_connection.php");
        include('mysql_secure_query_functions.php');

        $comment_id = $url[1];
        $username = $_SESSION['username'];

        $comment_delete_query = mysqli_prepare($db_connection,
    "DELETE
           FROM `comments`
           WHERE ( `comment_id` , `username` ) = ( ? , ? )");

        // The array for the secureMysqliQueryExecute function.
        $secure_stmt_variables = array($comment_id, $username);

        // Execute the prepared statement.
        secureMysqliQueryExecute($comment_delete_query, $secure_stmt_variables);

        header("location:$_SERVER[HTTP_REFERER]");
        die(mysqli_error($db_connection));
    }
