<?php

/** A function to convert new lines into <br> for HTML documents.
 * @param $string string A string with new lines.
 * @return string A string with <br> tags for html page rendering.
 */
function newlines2br(string $string):string {
    $commentContent = $string;
    $userInput = preg_replace("/(\r\n|\r|\n)/", PHP_EOL, $commentContent);
    return nl2br(htmlspecialchars($userInput));
}


/** A function for editing user's data.
 * @param string $name A name of a column to update in a database.
 * @param string $value A value to pass in a database.
 */
function edit_user_profile(string $name, string $value) {

    global $username, $db_connection;

    $user_query = mysqli_prepare($db_connection,
        "UPDATE `users`
            SET $name = ?
            WHERE username = ?")
    or die(mysqli_error($db_connection));
    $secure_stmt_variables = array($value, $username,);
    $user_query = secureMysqliQueryExecute($user_query, $secure_stmt_variables);

    if($user_query){
        print "The " . $name . " has changed successfully.";
    } else {
        print "The " . $name . " wasn't changed.";
    }
}
