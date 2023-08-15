<section class="section">       
    <div class="container-fluid">
        <div class="row-checkout">
        <?php
        if(isset($_SESSION["uid"])){
            $sql = "SELECT * FROM user_info WHERE user_id='$_SESSION[uid]'";
            $query = mysqli_query($con,$sql);
            $row=mysqli_fetch_array($query);
        
        echo'
            <div class="col-75">
                <div class="container-checkout">
                <form id="checkout_form" action="checkout_process.php" method="POST" class="was-validated">

                    <div class="row-checkout">
                    
                    <div class="col-50">
                        <h3>Billing Address</h3>
                        <label for="fname"><i class="fa fa-user" ></i> Full Name</label>
                        <input type="text" id="fname" class="form-control" name="firstname" pattern="^[a-zA-Z ]+$"  value="'.$row["first_name"].' '.$row["last_name"].'">
                        <label for="email"><i class="fa fa-envelope"></i> Email</label>
                        <input type="text" id="email" name="email" class="form-control" pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$" value="'.$row["email"].'" required>
                        <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                        <input type="text" id="adr" name="address" class="form-control" value="'.$row["address1"].'" required>
                        <label for="city"><i class="fa fa-institution"></i> City</label>
                        <input type="text" id="city" name="city" class="form-control" value="'.$row["address2"].'" pattern="^[a-zA-Z ]+$" required>

                        <div class="row">
                        <div class="col-50">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" class="form-control" pattern="^[a-zA-Z ]+$" required>
                        </div>
                        <div class="col-50">
                            <label for="zip">Zip</label>
                            <input type="text" id="zip" name="zip" class="form-control" pattern="^[0-9]{6}(?:-[0-9]{4})?$" required>
                        </div>
                        </div>
                    </div>
                    
                    
                    
                    <label><input type="CHECKBOX" name="q" class="roomselect" value="conform" required> Shipping address same as billing
                    </label>';


                     $i=1;
                    $total=0;
                    foreach ($totalProductDetails as $result) {
                        if (isset($result['product_title']) && isset($result['product_price']) && isset($result['qty'])) {
                            $item_name_ = $result['product_title'];
                            $quantity_ = $result['qty'];
                            $amount_ = $result['product_price']*$quantity_;
                            $sql = "SELECT product_id FROM products WHERE product_title='$item_name_'";
                        $query = mysqli_query($con,$sql);
                        $row=mysqli_fetch_array($query);
                        $product_id=$row["product_id"];
                        echo "  
                        <input type='hidden' name='prod_id_$i' value='$product_id'>
                        <input type='hidden' name='prod_price_$i' value='$amount_'>
                        <input type='hidden' name='prod_qty_$i' value='$quantity_'>
                        ";
                        
                        }
                        $i++;
                    }
                  
                    
                        

                echo' 
                <input type="hidden" name="total_count" value="'.$i.'">
                <input type="hidden" name="total_product_count" value="0" id="totalProductQty">
                <input id="Totalll" type="hidden" name="total_price" value="'.$totalProductPrice.'">';   

                

                    echo'    
                    <input type="button" id="submit" value="Cancel order" class="checkout-btn" onclick=\'window.location = "cancel_order.php"\'>

                    <input type="submit" id="submit" value="Continue to checkout" class="checkout-btn" >

                </form>
                </div>
            </div>
            ';
        }else{
            echo"<script>window.location.href = 'cart.php'</script>";
        }
        ?>

            <div class="col-25">
                <div class="container-checkout">
                
                <?php
                if (isset($_POST["cmd"])) {
                
                    $user_id = $_POST['custom'];
                    
                    
                    $i=1;
                    echo
                    "
                    <h4>Cart 
                    <span class='price' style='color:black'>
                    <i class='fa fa-shopping-cart'></i> 
                    <b>$total_count</b>
                    </span>
                </h4>

                    <table class='table table-condensed'>
                    <thead><tr>
                    <th >no</th>
                    <th >product title</th>
                    <th >   qty </th>
                    <th >   amount</th></tr>
                    </thead>
                    <tbody>
                    ";
                    $total=0;
                    while($i<=$total_count){

                        $item_name_ = $_POST['item_name_'.$i];
                        
                        $item_number_ = $_POST['item_number_'.$i];
                        
                        $amount_ = $_POST['amount_'.$i];
                        
                        $quantity_ = $_POST['quantity_'.$i];
                        $sql = "SELECT product_id, Stock FROM products WHERE product_title='$item_name_'";
                        $query = mysqli_query($con,$sql);
                        $row=mysqli_fetch_array($query);
                        $product_id=$row["product_id"];
                        $stock = $row["Stock"];
                        

                        $outOfStockOrAmount = intval($stock) - intval($quantity_) > 0 ? $amount_ : "<i>Out of stock</i>";

                        if (intval($stock) - intval($quantity_) > 0)                      $total=$total*$amount_ ;


                        echo "  
                        <tr><td><p>$item_number_</p></td><td><p>$item_name_</p></td><td ><p>$quantity_</p></td><td ><p>$outOfStockOrAmount</p></td></tr>";
                        
                        $i++;
                        echo"

                        ";
                    }

                echo"

                </tbody>
                </table>
                <hr>
                
                <h3>total<span class='price' style='color:black'><b>Npr$total</b></span></h3>";
                    
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</section>
