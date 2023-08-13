<?php

include "db.php";

session_start();
$ip_add = getenv("REMOTE_ADDR");

if (isset($_SESSION["uid"])) {
	$uid = $_SESSION["uid"];
	$sql = "DELETE FROM cart WHERE user_id = '$uid'";
} else {
	$sql = "DELETE FROM cart WHER ip_add = '$ip_add'";
}

$query = mysqli_query($con,$sql);

header("location: cart.php");



