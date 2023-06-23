<?php
function exportDatabase($host, $username, $password, $database, $exportPath) {

    // Connect to the database
    $mysqli = new mysqli($host, $username, $password, $database);
    $mysqli->select_db($database);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Database connection error: ' . $mysqli->connect_error);
    }

    // Set up the export file
    $file = fopen($exportPath, 'w');

    // Set character set and collation for the database
    $databaseCharset = $mysqli->query("SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$database'")->fetch_assoc();
    $databaseCharsetStmt = "ALTER DATABASE `$database` CHARACTER SET {$databaseCharset['DEFAULT_CHARACTER_SET_NAME']} COLLATE {$databaseCharset['DEFAULT_COLLATION_NAME']};\n";
    fwrite($file, $databaseCharsetStmt);

    // Retrieve the database structure if the database doesn't exist
    if ($mysqli->connect_errno === 1049) {
        $result = $mysqli->query('SHOW CREATE DATABASE ' . $database);
        $row = $result->fetch_row();
        $databaseStructure = $row[1];
        fwrite($file, $databaseStructure . ";\n");
    }

    // Retrieve the table structure and data
    $tables = $mysqli->query('SHOW TABLES');
    while ($table = $tables->fetch_array()) {
        $tableName = $table[0];

        // Drop the table if it exists
        $dropTableStmt = "DROP TABLE IF EXISTS `$tableName`;\n";
        fwrite($file, $dropTableStmt);

        // Retrieve the table structure
        $result = $mysqli->query('SHOW CREATE TABLE ' . $tableName);
        $row = $result->fetch_row();
        $tableStructure = $row[1];

        // Write the table structure to the export file
        fwrite($file, $tableStructure . ";\n");

        // Retrieve the table data
        $result = $mysqli->query('SELECT * FROM ' . $tableName);
        while ($row = $result->fetch_assoc()) {
            $insertStatement = 'INSERT INTO ' . $tableName . ' VALUES (';
            $values = array_map(function($value) use ($mysqli) {
                return "'" . $mysqli->real_escape_string($value) . "'";
            }, $row);
            $insertStatement .= implode(', ', $values) . ");\n";

            // Write the table data to the export file
            fwrite($file, $insertStatement);
        }
    }

    // Close the file and database connection
    fclose($file);
    $mysqli->close();

    echo 'Database exported successfully!';
}

// Usage
$host = 'localhost';
$username = 'root';
$password = 'mysql';
$database = 'database';
$exportPath = 'C:/Users/kevin/Desktop/database.sql';

exportDatabase($host, $username, $password, $database, $exportPath);
?>
