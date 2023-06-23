# 22-export

# PHPMyAdmin SQL Export

This PHP script exports a SQL file from PHPMyAdmin to the desktop. 
It allows you to automate the export process and create an SQL file that can be easily imported into a database.

## Functionality

The script performs the following tasks:

1. Connects to a MySQL database using the provided credentials.
2. Sets up the export file on the desktop.
3. Retrieves the database structure and writes it to the export file.
4. Retrieves the table structure and data for each table in the database.
5. Drops the table if it exists (optional).
6. Writes the table structure and data to the export file.
7. Closes the file and database connection.
8. Displays a success message.

## Usage

To use the script, follow these steps:

1. Make sure you have PHPMyAdmin installed and accessible.
2. Open the PHP script file in a text editor.
3. Modify the following variables according to your environment:
    - `$host`: The hostname or IP address of the MySQL server.
    - `$username`: The username to connect to the MySQL server.
    - `$password`: The password to connect to the MySQL server.
    - `$database`: The name of the database to export.
    - `$exportPath`: The path where you want to save the exported SQL file.
