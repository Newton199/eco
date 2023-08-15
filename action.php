


<?php
session_start();
$totalProductDetails[] = array();
$ip_add = getenv("REMOTE_ADDR");
include "db.php";
if (isset($_POST["category"])) {
	$category_query = "SELECT * FROM categories";

	$run_query = mysqli_query($con, $category_query) or die(mysqli_error($con));
	echo "
		
            
            <div class='aside'>
							<h3 class='aside-title'>Categories</h3>
							<div class='btn-group-vertical'>
	";
	if (mysqli_num_rows($run_query) > 0) {
		$i = 1;
		while ($row = mysqli_fetch_array($run_query)) {

			$cid = $row["cat_id"];
			$cat_name = $row["cat_title"];
			$sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_cat=$i";
			$query = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($query);
			$count = $row["count_items"];
			$i++;


			echo "
					
                    <div type='button' class='btn navbar-btn category' cid='$cid'>
									
									<a href='#'>
										<span  ></span>
										$cat_name
										<small class='qty'>($count)</small>
									</a>
								</div>
                    
			";
		}


		echo "</div>";
	}
}
if (isset($_POST["brand"])) {
	$brand_query = "SELECT * FROM brands";
	$run_query = mysqli_query($con, $brand_query);
	echo "
		<div class='aside'>
							<h3 class='aside-title'>Brand</h3>
							<div class='btn-group-vertical'>
	";
	if (mysqli_num_rows($run_query) > 0) {
		$i = 1;
		while ($row = mysqli_fetch_array($run_query)) {

			$bid = $row["brand_id"];
			$brand_name = $row["brand_title"];
			$sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_brand=$i";
			$query = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($query);
			$count = $row["count_items"];
			$i++;
			echo "
					
                    
                    <div type='button' class='btn navbar-btn selectBrand' bid='$bid'>
									
									<a href='#'>
										<span ></span>
										$brand_name
										<small >($count)</small>
									</a>
								</div>
			";
		}
		echo "</div>";
	}
}
if (isset($_POST["page"])) {
	$sql = "SELECT * FROM products";
	$run_query = mysqli_query($con, $sql);
	$count = mysqli_num_rows($run_query);
	$pageno = ceil($count / 9);
	for ($i = 1; $i <= $pageno; $i++) {
		echo "
			<li><a href='#product-row' page='$i' id='page' class='active'>$i</a></li>
            
            
		";
	}
}
if (isset($_POST["getProduct"])) {
	$limit = 9;
	if (isset($_POST["setPage"])) {
		$pageno = $_POST["pageNumber"];
		$start = ($pageno * $limit) - $limit;
	} else {
		$start = 0;
	}
	$product_query = "SELECT * FROM products,categories WHERE product_cat=cat_id LIMIT $start,$limit";
	$run_query = mysqli_query($con, $product_query);
	if (mysqli_num_rows($run_query) > 0) {
		while ($row = mysqli_fetch_array($run_query)) {
			$pro_id    = $row['product_id'];
			$pro_cat   = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];

			$cat_name = $row["cat_title"];
			echo "
				
                        
                        <div class='col-md-4 col-xs-6' >
								<a href='product.php?p=$pro_id'><div class='product'>
									<div class='product-img'>
										<img src='product_images/$pro_image' style='max-height: 170px;' alt=''>
										<div class='product-label'>
											<span class='sale'>-30%</span>
											<span class='new'>NEW</span>
										</div>
									</div></a>
									<div class='product-body'>
										<p class='product-category'>$cat_name</p>
										<h3 class='product-name header-cart-item-name'><a href='product.php?p=$pro_id'>$pro_title</a></h3>
										<h4 class='product-price header-cart-item-info'>$pro_price<del class='product-old-price'>990.00</del></h4>
										<div class='product-rating'>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
										</div>
										<div class='product-btns'>
											<button class='add-to-wishlist'><i class='fa fa-heart-o'></i><span class='tooltipp'>add to wishlist</span></button>
											<button class='add-to-compare'><i class='fa fa-exchange'></i><span class='tooltipp'>add to compare</span></button>
											<button class='quick-view'><i class='fa fa-eye'></i><span class='tooltipp'>quick view</span></button>
										</div>
									</div>
									<div class='add-to-cart'>
										<button pid='$pro_id' id='product' class='add-to-cart-btn block2-btn-towishlist' href='#'><i class='fa fa-shopping-cart'></i> add to cart</button>
									</div>
								</div>
							</div>
                        
			";
		}
	}
}


if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
	if (isset($_POST["get_seleted_Category"])) {
		$id = $_POST["cat_id"];
		$sql = "SELECT * FROM products,categories WHERE product_cat = '$id' AND product_cat=cat_id";
	} else if (isset($_POST["selectBrand"])) {
		$id = $_POST["brand_id"];
		$sql = "SELECT * FROM products,categories WHERE product_brand = '$id' AND product_cat=cat_id";
	} else {

		$keyword = $_POST["keyword"];
		header('Location:store.php');
		$sql = "SELECT * FROM products,categories WHERE product_cat=cat_id AND product_keywords LIKE '%$keyword%'";
	}

	$run_query = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($run_query)) {
		$pro_id    = $row['product_id'];
		$pro_cat   = $row['product_cat'];
		$pro_brand = $row['product_brand'];
		$pro_title = $row['product_title'];
		$pro_price = $row['product_price'];
		$pro_image = $row['product_image'];
		$cat_name = $row["cat_title"];
		echo "
					
                        
                        <div class='col-md-4 col-xs-6'>
								<a href='product.php?p=$pro_id'><div class='product'>
									<div class='product-img'>
										<img  src='product_images/$pro_image'  style='max-height: 170px;' alt=''>
										<div class='product-label'>
											<span class='sale'>-30%</span>
											<span class='new'>NEW</span>
										</div>
									</div></a>
									<div class='product-body'>
										<p class='product-category'>$cat_name</p>
										<h3 class='product-name header-cart-item-name'><a href='product.php?p=$pro_id'>$pro_title</a></h3>
										<h4 class='product-price header-cart-item-info'>$pro_price<del class='product-old-price'>990.00</del></h4>
										<div class='product-rating'>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
										</div>
										<div class='product-btns'>
											<button class='add-to-wishlist' tabindex='0'><i class='fa fa-heart-o'></i><span class='tooltipp'>add to wishlist</span></button>
											<button class='add-to-compare'><i class='fa fa-exchange'></i><span class='tooltipp'>add to compare</span></button>
											<button class='quick-view' ><i class='fa fa-eye'></i><span class='tooltipp'>quick view</span></button>
										</div>
									</div>
									<div class='add-to-cart'>
										<button pid='$pro_id' id='product' href='#' tabindex='0' class='add-to-cart-btn'><i class='fa fa-shopping-cart'></i> add to cart</button>
									</div>
								</div>
							</div>
			";
	}
}



if (isset($_POST["addToCart"])) {


	$p_id = $_POST["proId"];


	if (isset($_SESSION["uid"])) {

		$user_id = $_SESSION["uid"];

		$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
		$run_query = mysqli_query($con, $sql);
		$count = mysqli_num_rows($run_query);
		if ($count > 0) {
			echo "
				<div class='alert alert-warning'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is already added into the cart Continue Shopping..!</b>
				</div>
			"; //not in video
		} else {
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','$user_id','1')";
			if (mysqli_query($con, $sql)) {
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is Added..!</b>
					</div>
				";
			}
		}
	} else {
		$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
		$query = mysqli_query($con, $sql);
		if (mysqli_num_rows($query) > 0) {
			echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
			exit();
		}
		$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','-1','1')";
		if (mysqli_query($con, $sql)) {
			echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
			exit();
		}
	}
}

//Count User cart item
if (isset($_POST["count_item"])) {
	//When user is logged in then we will count number of item in cart by using user session id
	if (isset($_SESSION["uid"])) {
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
	} else {
		//When user is not logged in then we will count number of item in cart by using users unique ip address
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
	}

	$query = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($query);
	echo $row["count_item"];
	exit();
}
//Count User cart item

//Get Cart Item From Database to Dropdown menu
if (isset($_POST["Common"])) {

	if (isset($_SESSION["uid"])) {
		//When user is logged in this query will execute
		$sql = "SELECT a.product_id,a.Stock,a.product_title,a.product_price,a.product_desc,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
	} else {
		//When user is not logged in this query will execute
		$sql = "SELECT a.product_id,a.Stock,a.product_title,a.product_desc,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.ip_add='$ip_add' AND b.user_id < 0";
	}
	$query = mysqli_query($con, $sql);
	if (isset($_POST["getCartItem"])) {
		//display cart item in dropdown menu
		if (mysqli_num_rows($query) > 0) {
			$n = 0;
			$total_price = 0;
			while ($row = mysqli_fetch_array($query)) {

				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_image = $row["product_image"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				$stock = $row["Stock"];
				$total_price = $total_price + $product_price;
				echo '
					
                    
                    <div class="product-widget">
												<div class="product-img">
													<img src="product_images/' . $product_image . '" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">' . $product_title . '</a></h3>
													<h4 class="product-price"><span class="qty">' . $n . '</span>$' . $product_price . '</h4>
												</div>
												
											</div>';
			}

			echo '<div class="cart-summary">
				    <small class="qty">' . $n . ' Item(s) selected</small>
				    <h5 id="total"></h5>
				</div>'
?>


			<?php

			exit();
		}
	}



	if (isset($_POST["checkOutDetails"])) {
		if (mysqli_num_rows($query) > 0) {
			//display user cart item with "Ready to checkout" button if user is not login
			echo '<div class="main ">
			<div class="table-responsive">			
	               <table id="cart" class="table table-hover table-condensed" id="">
    				<thead>
						<tr>
							<th style="width:50%">Product</th>
							<th style="width:10%">Stock</th>
							<th style="width:10%">Price</th>
							<th style="width:8%">Quantity</th>
							<th style="width:7%" class="text-center">Subtotal</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>
                    ';
			$n = 0;
			$totalProductPrice = 0;
			while ($row = mysqli_fetch_array($query)) {
				$totalProductDetails[] = $row; 
				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_image = $row["product_image"];
				$product_desc = $row["product_desc"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				$stock = $row["Stock"];
				$total=0;
				$totalProductPrice = ((int)$product_price)* $qty;


				echo
				'  
						<tr id="row-'.strval($n).'">
							<td data-th="Product" >
								<div class="row">
								
									<div class="col-sm-4 "><img src="product_images/' . $product_image . '" style="height: 70px;width:75px;"/>
									<h4 class="nomargin product-name header-cart-item-name"><a href="product.php?p=' . $product_id . '">' . $product_title . '</a></h4>
									</div>
									<div class="col-sm-6">
										<div style="max-width=50px;">
										<p>' . $product_desc . '</div>
									</div>
									
									
								</div>
							</td>
                            <input type="hidden" name="product_id[]" value="' . $product_id . '"/>
				            <input type="hidden" name="" value="' . $cart_item_id . '"/>	
							<td data-th="stock">
								<input type="text" class="form-control qty stock" value="' . $stock . '" readonly>
								<input type="hidden" class="form-control qty orginal_stock" value="' . $stock. '" >
							</td>				
							<td data-th="Price"><input type="text" id = "price_'.strval($n).'" class="form-control price" value="' . $product_price . '" readonly="readonly"></td>
							<td data-th="Quantity">
								<input type="text" id="quantity_'.strval($n).'" class="form-control qty quantity" value="' . $qty . '" onkeyup="quantity_check('.strval($n).')"   name = "quantity_'.strval($n).'">

								
							</td>
							<td data-th="Subtotal" class="text-center"><input type="text" class="form-control total " id="subtotal_'.strval($n).'" value="' . $total . '" readonly="readonly" " ></td>
							<td class="actions" data-th="">
							<div class="btn-group">
								<a href="#" class="btn btn-info btn-sm update" update_id="' . $product_id . '"><i class="fa fa-refresh"></i></a>
								
								<a href="#" class="btn btn-danger btn-sm remove" remove_id="' . $product_id . '"><i class="fa fa-trash-o"></i></a>		
							</div>							
                            <div id="total"></div>
							</td>
						</tr>
                            
                            ';

			?>
			<div> 


				<script>

				function updateTotal(price, ppid) {
		
					
            var quantityInput = document.getElementById('quantity_'.concat(ppid)).value;
            var priceInput = parseInt(document.getElementById('price_'.concat(ppid)).value);
            document.getElementById('subtotal_'.concat(ppid)).value = quantityInput*priceInput;
        

  
        }

					function quantity_check(rowId) {
						var parenttd = document.querySelector("#row-" + rowId.toString());
						var qty = parseInt(parenttd.querySelector('.quantity')?.value || "0");
						var stock = parseInt(parenttd.querySelector('.stock')?.value || "0");
						var orginal_stock = parseInt(parenttd.querySelector('.orginal_stock')?.value || "0")
						
						if (qty == "") {
							if (parenttd.querySelector('.stock') != undefined)
								parenttd.querySelector('.stock').value = orginal_stock
						} else {
							var dec_stock = stock - qty;
							if (dec_stock < 0) {
								alert("Out of Stock");
								return;
							}
							if (parenttd.querySelector('.stock') != undefined){
								parenttd.querySelector('.stock').value = dec_stock;
								document.querySelector(`[name='quantity_${rowId}']`).value = qty;

							}

						}
						updateTotal(5, rowId);
						changedPrice();
					}
		function changedPrice()
		{
			var totalProductsinListtt = document.getElementById('totalCount').value;

			var i=1;
			var totalPrice=0;
			while(i<=totalProductsinListtt)
			{
				totalPrice += parseInt(document.getElementById('subtotal_'.concat(i)).value);
				i++;

			}
			console.log(totalPrice);
			document.getElementById('ttotal').textContent ="Total :NPR "+ totalPrice;
			document.getElementById('Totalll').value = totalPrice;
		}
				</script>
<?php

			}
			echo '</tbody>
			<input type="hidden" id= "totalCount" value="'.$n.'" >
				<tfoot>
					
					<tr>
						<td><a href="store.php" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
						<td colspan="2" class="hidden-xs"></td>
						<td class="hidden-xs text-center"><b id="ttotal" class="net_total" ></b></td>
						<div id="issessionset"></div>
                        <td>
									<button id = "checkoutBtn" class="btn btn-success" value="Proceed to Checkout" onclick="hideunhide()">Proceed to Checkout</button>
									</td>
									
									</tr>
									
									</tfoot>
									
							</table></div></div>    
								';
			}
		}
	}

//Remove Item From cart
if (isset($_POST["removeItemFromCart"])) {
	$remove_id = $_POST["rid"];
	if (isset($_SESSION["uid"])) {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
	} else {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
	}
	if (mysqli_query($con, $sql)) {
		echo "<div class='alert alert-danger'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is removed from cart</b>
				</div>";
		exit();
	}
}


//Update Item From cart
if (isset($_POST["updateCartItem"])) {
	$update_id = $_POST["update_id"];
	$qty = $_POST["qty"];
	if (isset($_SESSION["uid"])) {
		$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
	} else {
		$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
	}
	if (mysqli_query($con, $sql)) {
		echo "<div class='alert alert-info'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is updated</b>
				</div>";
		exit();
	}
}
?>





















<section id="tohide" class="section" style="display: none;">       
    <?php 
    include "checkout.php";
    ?>
</section>

<script>
	function hideunhide()
	{
		var x= document.getElementById('tohide');
		var y= document.getElementById('checkoutBtn');
		if(x.style.display == 'none')
		{
			x.style.display = 'block';
			y.innerHTML = "Cancel Checkout";
			var totalProductsinListtt = document.getElementById('totalCount').value;

			var i=1;
			while(i<=totalProductsinListtt)
			{
				document.getElementById('quantity_'.concat(i)).disabled = true;
				i++;

			}

		}
		else
		{
			x.style.display = 'none';
			y.innerHTML = "Proceed to Checkout";
			var totalProductsinListtt = document.getElementById('totalCount').value;

			var i=1;
			while(i<=totalProductsinListtt)
			{
				document.getElementById('quantity_'.concat(i)).disabled = false;
				i++;

			}	
		}
	}

	const calculateButton = document.getElementById("checkoutBtn");
    calculateButton.addEventListener("click", calculateTotalQty);

function calculateTotalQty() {
      const qtyInputs = document.querySelectorAll(".quantity");
      let totalQty = 0;
      
      qtyInputs.forEach(input => {
      	console.log(input.value);
        totalQty += parseInt(input.value) || 0;
      });
      
      const totalQuantityElement = document.getElementById("totalProductQty");
      totalQuantityElement.value = totalQty;
      console.log(totalQuantityElement);
    }
	</script>
        
