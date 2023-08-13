<?php
// Make sure to include db.php correctly based on the file location.
include("../db.php");

// Initialize variables to avoid undefined variable warnings.
$product_id = $product_title = $product_price = $stock = '';

$data = [];
// Check if product_id is provided in the URL parameter.
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product data from the database.
    $query = "SELECT product_id, product_title,product_price, stock FROM products WHERE product_id=?";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the product details.
            $data = mysqli_fetch_array($result);
            list($product_id, $product_name, $product_price , $stock) = $data;
        } else {
            die("Product not found.");
        }
    } else {
        die("Query 1 failed: " . mysqli_error($con));
    }
    $product_price = $data["product_price"];
    $product_title = $data["product_title"];
    $stock = $data["stock"];
    // var_dump($data);die;
}

if (isset($_POST['btn_save'])) {
    // Get the updated data from the form.
    $product_title = $_POST['product_title'];
    $product_price  = $_POST['product_price'];
    $stock = $_POST['stock'];

    // Update the product details in the database.
    $query = "UPDATE products SET product_title=?, product_price =?, stock=? WHERE product_id=?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssii", $product_title, $product_price , $stock, $product_id);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the index.php if the update is successful.
            header("location: index.php");
            exit;
        } else {
            die("Query 2 failed: " . mysqli_error($con));
        }
    } else {
        die("Query 2 preparation failed: " . mysqli_error($con));
    }
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
<div class="container-fluid main-container">
    <?php include("include/side_bar.php"); ?>

    <div class="col-md-9 content" align="center">
        <div class="panel-heading" style="background-color:#c4e17f">
            <h1>Edit User Details</h1>
        </div>
        <br>
        <form action="" name="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>"/>

            <div class="col-sm-7 ">
                <label style="font-size:18px;">Product Title</label><br>
                <input class="input-lg" style="font-size:18px; width:200px" name="product_title" type="text"
                       id="product_title"
                       value="<?php echo $product_title; ?>" autofocus><br><br>
            </div>
            <div class="col-sm-7 ">
                <label style="font-size:18px;">product_price </label><br>
                <input class="input-lg" style="font-size:18px; width:200px" name="product_price" type="text" id="product_price "
                       value="<?php echo $product_price ; ?>" autofocus><br><br>
            </div>

            <div class="col-sm-7 ">
                <label style="font-size:18px;">Stock</label><br>
                <input class="input-lg" style="font-size:18px; width:200px" name="stock" type="text" id="stock"
                       value="<?php echo $stock; ?>">
                <br><br>
            </div>
            <div class="col-sm-7">
                <button type="submit" class="btn btn-success " name="btn_save" id="btn_save"
                        style="font-size:18px">Submit
                </button>
            </div>
        </form>
    </div>
</div>
<?php include("include/js.php"); ?>
</body>
</html>
