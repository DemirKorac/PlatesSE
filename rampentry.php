<?php 
include ('db_connection.php');

$tablice=$_POST["tablice"];

$query = "INSERT INTO activities(registration,datetime,action) VALUES ('$tablice',CURRENT_TIMESTAMP,'0')";
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
