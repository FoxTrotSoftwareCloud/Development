<?php
$servername = "sql5c40n.carrierzone.com";
$username = "jjixgbv9my802728";
$password = "We3b2!12";
$dbname = "demo_jjixgbv9my802728";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM permrep WHERE BINARY username = 'demo' OR email = 'demo' LIMIT 1;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row["username"]."Password:". $row["webpswd"];
    }
} else {
    echo "0 results";
}
$conn->close();
?>
