<?php

include("../db.php");
if(isset($_GET['action']) && $_GET['action']!="" && $_GET['action']=='delete')
{
    $user_id = $_GET['user_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "DELETE FROM user_info WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    // Check if the delete query was successful
    if(mysqli_stmt_affected_rows($stmt) > 0) {
        echo "User deleted successfully.";
    } else {
        echo "User deletion failed.";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Admin Panel</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style/css/bootstrap.min.css" rel="stylesheet">
<link href="style/css/k.css" rel="stylesheet">
<script src="style/js/jquery.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>

<div class="container-fluid">

<?php include("include/side_bar.php"); ?>
<div class="col-sm-9" style="margin-left:10px"> 
<div class="panel-heading" style="background-color:#c4e17f">
	<h1>Manage User </h1></div><br>

<div style="overflow-x:scroll;">
<table class="table table-bordered table-hover table-striped" style="font-size:18px">
	<tr>
			    <th>User Name</th>
                <th>User Password</th>
	<th><a href="add_user.php">Add New</a></th></tr>	
<?php 
$result=mysqli_query($con,"select user_id, email, password from user_info")or die ("query 2 incorrect.......");


while(list($user_id,$user_name,$user_password)=
mysqli_fetch_array($result))
{
echo "<tr><td>$user_name</td><td>$user_password</td>";

echo"<td>
<a href='edit_user.php?user_id=$user_id'>Edit</a>
<a href='manage_users.php?user_id=$user_id&action=delete'>Delete</a>
</td></tr>";
}
mysqli_close($con);
?>
</table>
</div>	
</div></div>
<?php include("include/js.php"); ?>
</body>
</html>