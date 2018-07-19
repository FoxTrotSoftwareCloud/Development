<?php
$servername = "sql5c40n.carrierzone.com";
$username = "jjixgbv9my353010";
$password = "We3b2!12";

// Create connection
$conn = new mysqli($servername, $username, $password,'CloudFox_jjixgbv9my353010');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$sql = "SELECT * FROM ft_account_type";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["type"]. " " . $row["status"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>