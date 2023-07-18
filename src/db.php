<?php

function db_exec_query($query)
{
    require_once("config.php");
    try {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $result = $conn->query($query);
        if (!$result)
            throw new mysqli_sql_exception($conn->error);
        return $result;
    } catch (mysqli_sql_exception $e) {
        if ($conn->errno == 1062) {
            // Duplicate Key
            $errorMessage = $conn->error;
            $matches = [];
            preg_match("/Duplicate entry '(.+)' for key '(.+)'/", $errorMessage, $matches);
            $duplicateValue = $matches[1];
            $duplicateKey = explode("_", $matches[2]);
            echo "$duplicateKey". "<br>";
            return "Duplicate entry '$duplicateValue' found for key '$duplicateKey'.";
        }
        return $e->getMessage();
    }

    // $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // if ($conn->connect_error)
    //     return $conn->error;

    // $result = $conn->query($query);
    // if ($result) {
    //     $conn->close();
    //     return $result;
    // } else {
    //     if ($conn->errno == 1062) { // Error code for duplicate key violation
    //         $errorMessage = $conn->error;
    //         $matches = [];
    //         preg_match("/Duplicate entry '(.+)' for key '(.+)'/", $errorMessage, $matches);
    //         $duplicateValue = $matches[1];
    //         $duplicateKey = $matches[2];
    //         echo "Duplicate entry '$duplicateValue' found for key '$duplicateKey'.";
    //     } else {
    //         echo "Error: " . $conn->error;
    //     }
    //     $error = $conn->error;
    //     $conn->close();
    //     return $error;
    // }
}
