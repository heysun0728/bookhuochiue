<?php
$dbHost = '127.0.0.1';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'volunteer';
//connect with the database
$db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
//get search term
$searchTerm = $_GET['term'];
//get matched data from skills table
$query = $db->query("SELECT * FROM school_view WHERE schoolid LIKE '%".$searchTerm."%' ORDER BY schoolid ASC");
while ($row = $query->fetch_assoc()) {
    $data[] = $row['schoolid'];
}
print_r($data);
//return json data
echo json_encode($data);
?>