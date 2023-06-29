<?php
// Set allow_scripts_to_close_windows to true in  about:config

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
            $values = array_map(function ($value) use ($mysqli) {
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

    // Output the prettier HTML
    echo '<html>
              <head>
                  <title>Database Exported</title>
                  <style>
                      body {
                          font-family: Arial, sans-serif;
                          background-color: #f7f7f7;
                          margin: 0;
                          padding: 0;
                      }
                      .container {
                          display: flex;
                          align-items: center;
                          justify-content: center;
                          height: 100vh;
                      }
                      .message {
                          text-align: center;
                          padding: 30px;
                          background-color: #fff;
                          border-radius: 10px;
                          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                      }
                      h1 {
                          font-size: 24px;
                          margin: 0;
                          padding-bottom: 10px;
                          color: #333;
                      }
                      p {
                          font-size: 16px;
                          margin: 0;
                          color: #777;
                      }
                      .buttons {
                          margin-top: 20px;
                      }
                      .button {
                          display: inline-block;
                          padding: 10px 20px;
                          font-size: 16px;
                          font-weight: bold;
                          text-decoration: none;
                          background-color: #3490dc;
                          color: #fff;
                          border-radius: 4px;
                          transition: background-color 0.3s;
                      }
                      .button:hover {
                          background-color: #2779bd;
                      }
                  </style>
              </head>
              <body>
                  <div class="container">
                      <div class="message">
                          <h1>Database Exported Successfully!</h1>
                          <p>The SQL file has been Exported into a SQL file..</p>
                          <div class="buttons">
                              <a href="http://localhost" class="button">Return to localhost</a>
                              <button class="button" onclick="window.close()">Close Tab</button>
                          </div>
                      </div>
                  </div>
              </body>
          </html>';
}

// Usage
$host = 'localhost';
$username = 'root';
$password = 'mysql';
$database = 'database';
$exportPath = 'C:/Users/user/Desktop/database.sql';

exportDatabase($host, $username, $password, $database, $exportPath);
?>
