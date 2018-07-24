<?php
$json = file_get_contents('php://input');
$respond = json_decode($json);

$servername = "localhost";
$username = "fffuser";
$password = "fFfPaSsWoRd";
$dbname = "fff";

$company = $respond->company;
$year = $respond->year;
$website = $respond->website;
$name = $respond->name;
$position = $respond->position;

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Error: Conexion fallida";
} 

$sql = "INSERT INTO companies (company_name, creation_year, website, name, position)
VALUES ('".$company."', '".$year."', '".$website."', '".$name."', '".$position."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close(); 

mysqli_close($conn);
?>