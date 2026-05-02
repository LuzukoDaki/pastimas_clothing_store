<?php

include("DBConn.php");

// This code ought to Drop tblUser if it exists
$sqlDrop = "DROP TABLE IF EXISTS tblUser";

if(mysqli_query($conn, $sqlDrop)){
    echo "tblUser deleted successfully if it existed.<br>";
}

// This code ought to Recreate tblUser
$sqlCreate = "CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $sqlCreate)){
    echo "tblUser created successfully.<br>";
}

// This code ought to Open userData.txt
$file = fopen("userData.txt", "r");

// This code ought to Read each line
while(($line = fgets($file)) !== false){

    $data = explode(",", trim($line));

    $name = $data[0];
    $email = $data[1];
    $password = $data[2];

    $sqlInsert = "INSERT INTO tblUser(full_name,email,password_hash)
                  VALUES('$name','$email','$password')";

    mysqli_query($conn, $sqlInsert);
}

fclose($file);

echo "Data loaded successfully.";

mysqli_close($conn);

?>