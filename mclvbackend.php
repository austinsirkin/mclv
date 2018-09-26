<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "mclv";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$test = "SHOW FULL TABLES FROM mclv";


$results = $conn->query($test);

//var_dump($results);




if ($results->num_rows > 0) {
    // output data of each row
    while($row = $results->fetch_assoc()) {
        echo $row[0] . " " . $row[1] . "<br>";
    }
} else {
    echo "0 results";
}



$conn->close();
