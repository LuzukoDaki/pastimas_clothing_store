<?php
include("DBConn.php");

echo "<h2>Loading ClothingStore Database...</h2><br>";

// ============================
// STEP 1: DROP ALL TABLES
// Order matters because of foreign keys
// Drop tblAorder first (it depends on tblUser and tblClothes)
// ============================

$dropTables = [
    "DROP TABLE IF EXISTS tblAorder",
    "DROP TABLE IF EXISTS tblClothes",
    "DROP TABLE IF EXISTS tblAdmin",
    "DROP TABLE IF EXISTS tblUser"
];

foreach($dropTables as $sql){
    if(mysqli_query($conn, $sql)){
        echo "Dropped: " . $sql . "<br>";
    } else {
        echo "Error dropping table: " . mysqli_error($conn) . "<br>";
    }
}

echo "<br>";

// ============================
// STEP 2: CREATE tblUser
// ============================
$createUser = "CREATE TABLE IF NOT EXISTS tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $createUser)){
    echo "Created: tblUser <br>";
} else {
    echo "Error creating tblUser: " . mysqli_error($conn) . "<br>";
}

// ============================
// STEP 3: CREATE tblAdmin
// ============================
$createAdmin = "CREATE TABLE IF NOT EXISTS tblAdmin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(100) NOT NULL,
    admin_email VARCHAR(100) NOT NULL UNIQUE,
    admin_password_hash VARCHAR(255) NOT NULL
)";

if(mysqli_query($conn, $createAdmin)){
    echo "Created: tblAdmin <br>";
} else {
    echo "Error creating tblAdmin: " . mysqli_error($conn) . "<br>";
}

// ============================
// STEP 4: CREATE tblClothes
// ============================
$createClothes = "CREATE TABLE IF NOT EXISTS tblClothes (
    clothes_id INT AUTO_INCREMENT PRIMARY KEY,
    clothes_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    size VARCHAR(20) NOT NULL,
    color VARCHAR(30) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 1,
    description TEXT,
    image_path VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $createClothes)){
    echo "Created: tblClothes <br>";
} else {
    echo "Error creating tblClothes: " . mysqli_error($conn) . "<br>";
}

// ============================
// STEP 5: CREATE tblAorder
// ============================
$createOrder = "CREATE TABLE IF NOT EXISTS tblAorder (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    clothes_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (clothes_id) REFERENCES tblClothes(clothes_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)";

if(mysqli_query($conn, $createOrder)){
    echo "Created: tblAorder <br>";
} else {
    echo "Error creating tblAorder: " . mysqli_error($conn) . "<br>";
}

echo "<br>";

// ============================
// STEP 6: LOAD DATA FROM TEXT FILES
// ============================

// --- Load tblUser from userData.txt ---
$userFile = fopen("userData.txt", "r");
if($userFile){
    while(($line = fgets($userFile)) !== false){
        $data = explode(",", trim($line));
        if(count($data) == 5){
            $name     = mysqli_real_escape_string($conn, $data[0]);
            $username = mysqli_real_escape_string($conn, $data[1]);
            $email    = mysqli_real_escape_string($conn, $data[2]);
            $password = mysqli_real_escape_string($conn, $data[3]);
            $status   = mysqli_real_escape_string($conn, $data[4]);

            $sql = "INSERT INTO tblUser(full_name, username, email, password_hash, status)
                    VALUES('$name','$username','$email','$password','$status')";
            if(mysqli_query($conn, $sql)){
                echo "Inserted into tblUser: $name <br>";
            } else {
                echo "Error inserting $name into tblUser: " . mysqli_error($conn) . "<br>";
            }
        }
    }
    fclose($userFile);
} else {
    echo "Error: Could not open userData.txt <br>";
}

echo "<br>";

// --- Load tblAdmin from adminData.txt ---
$adminFile = fopen("adminData.txt", "r");
if($adminFile){
    while(($line = fgets($adminFile)) !== false){
        $data = explode(",", trim($line));
        if(count($data) == 3){
            $name     = mysqli_real_escape_string($conn, $data[0]);
            $email    = mysqli_real_escape_string($conn, $data[1]);
            $password = mysqli_real_escape_string($conn, $data[2]);

            $sql = "INSERT INTO tblAdmin(admin_name, admin_email, admin_password_hash)
                    VALUES('$name','$email','$password')";
            if(mysqli_query($conn, $sql)){
                echo "Inserted into tblAdmin: $name <br>";
            } else {
                echo "Error inserting $name into tblAdmin: " . mysqli_error($conn) . "<br>";
            }
        }
    }
    fclose($adminFile);
} else {
    echo "Error: Could not open adminData.txt <br>";
}

echo "<br>";

// --- Load tblClothes from clothesData.txt ---
$clothesFile = fopen("clothesData.txt", "r");
if($clothesFile){
    while(($line = fgets($clothesFile)) !== false){
        $data = explode(",", trim($line));
        if(count($data) == 7){
            $name        = mysqli_real_escape_string($conn, $data[0]);
            $category    = mysqli_real_escape_string($conn, $data[1]);
            $size        = mysqli_real_escape_string($conn, $data[2]);
            $color       = mysqli_real_escape_string($conn, $data[3]);
            $price       = mysqli_real_escape_string($conn, $data[4]);
            $stock       = mysqli_real_escape_string($conn, $data[5]);
            $description = mysqli_real_escape_string($conn, $data[6]);

            $sql = "INSERT INTO tblClothes(clothes_name, category, size, color, price, stock_quantity, description)
                    VALUES('$name','$category','$size','$color','$price','$stock','$description')";
            if(mysqli_query($conn, $sql)){
                echo "Inserted into tblClothes: $name <br>";
            } else {
                echo "Error inserting $name into tblClothes: " . mysqli_error($conn) . "<br>";
            }
        }
    }
    fclose($clothesFile);
} else {
    echo "Error: Could not open clothesData.txt <br>";
}

echo "<br>";
echo "<strong>ClothingStore database loaded successfully.</strong>";

mysqli_close($conn);
?>