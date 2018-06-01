<?php 
include ('db_connection.php');
$plates=$_POST["plates"];
$usermail=$_POST["usermail"];

$query = "UPDATE people
SET registration = '$plates'
WHERE email='$usermail'";
$result = mysqli_query($db, $query);
	
	if($result)
	{
		echo "Inserted";
	}
	else
	{
		echo "Not Inserted";
	}
 ?>