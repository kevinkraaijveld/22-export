<?php
// Set allow_scripts_to_close_windows to true in  about:config

function importDatabase($host, $username, $password, $database, $importPath) {
    // Connect to the database
    $mysqli = new mysqli($host, $username, $password, $database);
    $mysqli->select_db($database);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Database connection error: ' . $mysqli->connect_error);
    }

    // Increase maximum execution time and maximum allowed packet size
    $mysqli->query('SET GLOBAL max_execution_time = 300'); // Set maximum execution time to 5 minutes
    $mysqli->query('SET GLOBAL max_allowed_packet = 524288000'); // Set maximum allowed packet size to 500 MB

    // Read the SQL file
    $sql = file_get_contents($importPath);

    // Execute the SQL queries
    if ($mysqli->multi_query($sql)) {
        echo '<html>
                  <head>
                      <title>Database Importing</title>
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
                          .loading {
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
                          .progress-bar {
                              width: 100%;
                              height: 20px;
                              background-color: #f5f5f5;
                              border-radius: 4px;
                              overflow: hidden;
                          }
                          .progress-bar-fill {
                              height: 100%;
                              width: 0;
                              background-color: #3490dc;
                              transition: width 8s linear;
                          }
                          .message {
                              display: none;
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
                      <script type="text/javascript">
                          window.onload = function() {
                              var progressBarFill = document.querySelector(".progress-bar-fill");
                              var progress = 0;
                              var interval = setInterval(function() {
                                  progress += 2;
                                  progressBarFill.style.width = progress + "%";
                                  if (progress >= 100) {
                                      clearInterval(interval);
                                      setTimeout(function() {
                                          document.getElementById("loading").style.display = "none";
                                          document.getElementById("message").style.display = "block";
                                      }, 8000); // 4 seconds delay before displaying the success message
                                  }
                              }, 40); // 4 seconds = 4000ms, 4000ms / 100 increments = 40ms per increment
                          };
                      </script>


                  </head>
                  <body>
                      <div class="container">
                          <div id="loading" class="loading">
                              <h1>Importing Database...</h1>
                              <div class="progress-bar">
                                  <div class="progress-bar-fill"></div>
                              </div>
                          </div>
                          <div id="message" class="message">
                              <h1>Database Imported Successfully!</h1>
                              <p>The SQL file has been imported into the database.</p>
                              <div class="buttons">
                                  <a href="http://localhost" class="button">Return to localhost</a>

                                  <button class="button" onclick="window.close()">Close Tab</button>
                              </div>
                          </div>
                      </div>
                  </body>
              </html>';
    } else {
        echo 'Error importing database: ' . $mysqli->error;
    }

    // Close the database connection
    $mysqli->close();
}

// Usage
$host = 'localhost';
$username = 'root';
$password = 'mysql';
$database = 'database';
$importPath = 'C:/Users/user/Desktop/database.sql';

importDatabase($host, $username, $password, $database, $importPath);
?>
