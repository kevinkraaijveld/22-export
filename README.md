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

# 22-import

# PHPMyAdmin SQL Import

This PHP script imports an SQL file into a MySQL database using PHPMyAdmin. 
It automates the import process and allows you to easily import an SQL file into a database.

## Functionality

The script performs the following tasks:

1. Connects to a MySQL database using the provided credentials.
2. Reads the SQL file from the specified path.
3. Sets up a loading screen with a progress bar.
4. Executes the SQL queries from the file using the `multi_query` function.
5. Displays a success message after the import is completed.
6. Provides options to return to localhost or close the tab.

## Usage

To use the script, follow these steps:

1. Make sure you have PHPMyAdmin installed and accessible.
2. Open the PHP script file in a text editor.
3. Modify the following variables according to your environment:
    - `$host`: The hostname or IP address of the MySQL server.
    - `$username`: The username to connect to the MySQL server.
    - `$password`: The password to connect to the MySQL server.
    - `$database`: The name of the database to import into.
    - `$importPath`: The path to the SQL file you want to import.

After making the necessary modifications, save the file and run it in your PHP environment.

The script will display a loading screen with a progress bar as it imports the SQL file into the specified database. Once the import is completed, a success message will be shown, and you will have the option to return to localhost or close the tab.
