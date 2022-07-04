<?php
define('WEBPAGE_TITLE', 'Cart');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('includes/head.php') ?>
</head>

<body>
    <?php include_once('includes/navbar.php') ?>

    <main>
        <div class="container mt-5">
            <div class="row">
                <div class="table-responsive mx-3 p-5 bg-white rounded shadow">
                    <h3>Cart</h3>
                    <table id="tbl_cart" class="table table-striped table-bordered bg-white">
                        <thead>
                            <tr style="text-align: center;">
                                <th width="5%">ID</th>
                                <th width="10%">Image</th>
                                <th width="20%">Name</th>
                                <th width="10%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Total Price</th>
                                <th width="15%">Date Added</th>
                                <th width="20%">
                                    <span class="badge-danger badge p-1" style="cursor: pointer;" onclick="emptyCart()"><i class="fas fa-trash mr-1"></i>Clear Cart</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = get_all_products_in_cart($_SESSION['id']);
                            $products = $result['data'];

                            foreach ($products as $product) {
                                $price = $product['price'];
                                $discounted_price = $price - ($price * ($product['discount_percentage'] / 100)); ?>
                                
                                <tr id="tr_cart_item_<?= $product['cart_id'] ?>">
                                    <td class="text-center align-middle"><?= $product['cart_id'] ?></td>
                                    <td class="align-middle">
                                        <img class="img-fluid" src="<?= $product['image_path'] ?>" alt="Product Image">
                                    </td>
                                    <td class="align-middle"><?= $product['name'] ?></td>
                                    <td class="text-center align-middle">₱ <?= number_format($discounted_price, 2) ?></td>
                                    <td class="text-center align-middle">
                                        <input type="number" cart_id="<?= $product['cart_id'] ?>" class="form-control quantity" value="<?= $product['quantity'] ?>" placeholder="Quantity">
                                    </td>
                                    <td class="text-center align-middle" id="cart_total_price_<?= $product['cart_id'] ?>">₱ <?= number_format($discounted_price * $product['quantity'], 2) ?></td>
                                    <td class="text-center align-middle"><?= date('M-d-Y', strtotime($product['date_added'])) ?></td>
                                    <td class="text-center align-middle">
                                        <span class="text-danger lead remove_cart_item" style="cursor: pointer;" cart_id="<?= $product['cart_id'] ?>"><i class="fas fa-trash-alt"></i></span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">Total:</th>
                                <th id="grand_total">₱ <?= $result['total'] ?></th>
                                <th>
                                    <button id="btn_checkout" class="btn btn-success btn-block" onclick="window.location.href = 'checkout.php';" <?= ($result['total'] > 1) ? '' : 'disabled'; ?>><i class="far fa-credit-card mr-1"></i>Checkout</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>

    <script>
        let tbl_cart;

        function emptyCart() {
            if (confirm('Are you sure want to clear your cart?')) {
                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    cache: false,
                    data: {
                        action: 'empty_cart'
                    },
                    success: function(success) {
                        if (success) tbl_cart.clear().draw();

                        cartItemsTotalPrice();
                    }
                });
            }
        }

        function cartProductTotalPrice(cart_id) {
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: {
                    action: 'cart_product_total_price',
                    cart_id: cart_id
                },
                success: function(total) {
                    $('#cart_total_price_' + cart_id).html('₱ ' + total);
                }
            });
        }

        function cartItemsTotalPrice() {
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: {
                    action: 'cart_items_total_price'
                },
                success: function(total) {
                    $('#grand_total').html('₱ ' + total);
                }
            });
        }

        $(document).ready(function() {
            tbl_cart = $('#tbl_cart').DataTable({
                columnDefs: [{
                        targets: [1, 7],
                        orderable: false
                    },
                    {
                        'searchable': false,
                        'targets': [1, 4, 7]
                    }
                ]
            });

            $('.remove_cart_item').click(function() {
                const cart_id = $(this).attr('cart_id');
                const tr = $('#tr_cart_item_' + cart_id);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        action: 'remove_cart_item',
                        cart_id: cart_id
                    },
                    success: function(success) {
                        if (success) tbl_cart.row(tr).remove().draw(false);

                        cartItemsTotalPrice();
                    }
                });
            });

            $('.quantity').on('change', function() {
                const cart_id = $(this).attr('cart_id');
                const quantity = $(this).val();
                const tr = $('#tr_cart_item_' + cart_id);

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        action: 'edit_cart_item_quantity',
                        cart_id: cart_id,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response === 'remove_item') tbl_cart.row(tr).remove().draw(false);
                        else cartProductTotalPrice(cart_id);

                        cartItemsTotalPrice();
                    }
                });
            });
        });
    </script>
</body>

</html>