<?php
$db = mysqli_connect("localhost", "root", "", "burchplates");
if (!$db) {
 die("Connection failed: " . mysqli_connect_error());
}
?>