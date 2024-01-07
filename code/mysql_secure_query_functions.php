<?php

/**
 * Returns an array with prepared secure SELECT SQL query.
 * @param mysqli_stmt $stmt Prepared MySQL query.
 * @param array $params An array with variables for SQL query.
 * @return array|null
 */
function secureMysqliQuerySelect(mysqli_stmt $stmt,array $params):array|null{

    if (secureMysqliQueryExecute($stmt, $params)) {
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    } else {
        die("Error executing statement: " . mysqli_stmt_error($stmt));
    }

}

/** The function makes the statement secure and executes it.
 * @param mysqli_stmt $stmt Prepared MySQL query.
 * @param array $params An array with variables for SQL query.
 * @return bool
 */
function secureMysqliQueryExecute(mysqli_stmt $stmt, array $params):bool{

    // Count input params and make a string with the type of input.
    $paramTypes = '';
    for ($i = 1; $i <= count($params); $i++) {
        $paramTypes .= "s";
    }

    // Bind parameters if provided.
    if ($paramTypes && $params) {
         $stmt->bind_param($paramTypes, $params);
    }

    return mysqli_stmt_execute($stmt);
}

/** The function makes prepared array with MySQL results for using with loops.
 * @param mysqli_stmt $stmt Prepared MySQL query.
 * @param array|null $params An array with variables to pass to the MySQL query.
 * @return array|null
 */
function secureMysqliQuerySelectForLoop(mysqli_stmt $stmt, array $params = null):array|null {

    // Execute the statement.
    if (secureMysqliQueryExecute($stmt, $params)) {
        // Get result set metadata
        $resultMetadata = mysqli_stmt_result_metadata($stmt);

        // Fetch field names from metadata.
        $fieldNames = array();
        while ($field = mysqli_fetch_field($resultMetadata)) {
            $fieldNames[] = $field->name;
        }

        // Create an array to hold result column references.
        $resultColumns = array();
        foreach ($fieldNames as $fieldName) {
            $resultColumns[$fieldName] = null; // Initialize with null
        }

        // Bind the result columns to references.
        $bindResultParams = array($stmt);
        foreach ($fieldNames as $fieldName) {
            $bindResultParams[] = &$resultColumns[$fieldName];
        }

        call_user_func_array('mysqli_stmt_bind_result', $bindResultParams);

        // Fetch and return the results.
        $results = array();

        while (mysqli_stmt_fetch($stmt)) {

            // Create a new array for each row
            $row = array();

            // Copy the values from $resultColumns to $row
            foreach ($resultColumns as $key => $value) {
                $row[$key] = $value;
                $resultColumns[$key] = null; // Reset the reference for the next iteration
            }
            $results[] = $row;
        }

        return $results;

    } else {
        die("Error executing statement: " . mysqli_stmt_error($stmt));
    }
}

/** This function selects rows from a database table and counts the outputted strings.
 * @param mysqli_stmt $stmt Prepared MySQL query.
 * @param array $params An array with variables for SQL query.
 * @return string|null
 */
function secureMysqlQuerySelectNumRows(mysqli_stmt $stmt, array $params):string|null {

    if (secureMysqliQueryExecute($stmt, $params)) {
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt);
    } else {
        die("Error executing statement: " . mysqli_stmt_error($stmt));
    }
}
