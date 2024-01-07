<?php
    include("auth_session.php");

    if($_SERVER['REQUEST_METHOD'] == "GET") {

        require("db_connection.php");
        include('mysql_secure_query_functions.php');

        $article_id = $url[1];
        $username = $_SESSION['username'];

        $article_delete_query = mysqli_prepare($db_connection,
    "DELETE 
           FROM `articles` 
           WHERE ( `article_id` , `username` ) = ( ? , ? )");

        // The array for the secureMysqliQueryExecute function.
        $secure_stmt_variables = array($article_id, $username);

        // Execute the prepared statement.
        secureMysqliQueryExecute($article_delete_query, $secure_stmt_variables);

        header("location:/dashboard");
        die(mysqli_error($db_connection));
    }
