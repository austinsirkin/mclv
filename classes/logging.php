<?php

		$servername = "localhost:3307";
		$username = "root";
		$password = "";
		$database = "mclv";
		$SQLAPI = "$apiArray[0]" . "_" . "$apiArray[1]";
		$sqlTest = "SELECT * FROM $SQLAPI";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $database);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		if ($conn->query($sqlTest) == FALSE) {

		$createTable = "CREATE TABLE $SQLAPI (
		    connections int(255) unsigned auto_increment primary key,
		    timestamp TIMESTAMP
		)";


		if ($conn->query($createTable) === TRUE) {
		//    echo "Table created successfully";
		} else {
		//    echo "Error creating table: " . $conn->error;
		} 
		} else {
		if ($conn->query("INSERT INTO $SQLAPI (connections) VALUES (NULL)") === TRUE) {
		    // echo "Row inserted";
		} else {
		    // echo "Error adding row: " . $conn->error;
		} }
		$conn->close();
