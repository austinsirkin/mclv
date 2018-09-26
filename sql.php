<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$test = "SELECT * FROM persons";

// Create database
/* 
$sql = "CREATE DATABASE myDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}


$createTable = "CREATE TABLE persons (
    email varchar(255),
    lastname varchar(255),
    firstname varchar(255),
    Address varchar(255),
    City varchar(255),
    increment int(255) unsigned auto_increment primary key
)";

*/

$alterTable = "ALTER TABLE persons ADD increment INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY";

if ($conn->query($alterTable) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}




$add = "INSERT INTO persons (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";

;

if ($conn->query($add) === TRUE) {
    echo "Value added successfully";
} else {
    echo "Error adding value: " . $conn->error;
}

$results = $conn->query($test);

if ($results->num_rows > 0) {
    // output data of each row
    while($row = $results->fetch_assoc()) {
        echo "email: " . $row["email"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. ". You've been here:" . $row["increment"] . " times." . "<br>";
    }
} else {
    echo "0 results";
}


// $conn->query("DROP DATABASE myDB");
// $conn->close();
mysqli_close($conn);
?>