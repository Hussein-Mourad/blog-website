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
        if ($conn->errno == 1062) {  // Duplicate Key Code
            $errorMessage = $conn->error;
            $matches = [];
            preg_match("/Duplicate entry '(.+)' for key '(.+)'/", $errorMessage, $matches);
            $key= explode("_", $matches[2])[0]; // Duplicated field name
            return "Duplicate $key";
        }
        return $e->getMessage();
    }
}
