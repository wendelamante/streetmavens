<?php
define('WEBPAGE_TITLE', 'Shop');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'delete') {
        $product_id = $_GET['id'];

        if (delete_product($product_id)) {
            header('Location: shop.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('includes/head.php') ?>
</head>

<body>
    <?php include_once('includes/navbar.php') ?>
    <main>
        <div class="container">
            <div class="row mt-5 mb-4">
                <?php if (isset($_SESSION['id']) && $_SESSION['permission'] === 'admin') { ?>
                    <div class="col-sm text-right">
                        <a class="btn btn-primary" href="product.php">New Product</a>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <?php
                $products = get_all_products()['data'];
                $len = count($products);

                for ($i = 0; $i < $len; ++$i) {
                    $product = $products[$i];
                    $price = $product['price'];
                    $discounted_price = $price - ($price * ($product['discount_percentage'] / 100)); ?>
                    <div class="col-md-3 p-3">
                        <div class="card shadow">
                            <form product_id="<?= $product['id'] ?>">
                                <div class="container p-0" style="position: relative;">
                                    <?php if ($i < 3) { ?>
                                        <span class="badge badge-success" style="position: absolute;">Top <?= $i + 1 ?></span>
                                    <?php } ?>
                                    <span class="badge badge-info" style="position: absolute; right: 0; bottom: 0;"><?= $product['sold'] ?> sold</span>
                                    <img class="card-img-top" src="<?= $product['image_path'] ?>" alt="Product Image">
                                </div>
                                <div class="card-body">
                                    <div class="card-title h5"><?= $product['name'] ?></div>
                                    <div class="card-text h6 text-right" <?php if ($price !== $discounted_price) echo 'style="text-decoration: line-through;"' ?>>₱ <?= number_format($price, 2) ?></div>
                                    <div class="card-text h6 text-right">
                                        <?php if ($price !== $discounted_price) { ?>
                                            ₱ <?= number_format($discounted_price, 2) ?>
                                        <?php } else { ?>
                                            <br>
                                        <?php } ?>
                                    </div>
                                    <div class="card-text font-italic"><?= $product['stock'] ?> left</div>
                                    <?php if (!isset($_SESSION['id']) || $_SESSION['permission'] === 'normal') { ?>
                                        <input type="number" class="form-control my-2" id="quantity_<?= $product['id'] ?>" value="1" placeholder="Quantity" min="1" max="<?= $product['stock'] ?>" <?= ($product['stock'] < 1) ? 'disabled' : '' ?>>
                                        <button class="btn btn-secondary btn-block" <?= ($product['stock'] < 1) ? 'disabled' : '' ?>>Add to Cart</button>
                                    <?php } else { ?>
                                        <a class="btn btn-info btn-block" href="product.php?id=<?= $product['id'] ?>">Edit</a>
                                        <a class="btn btn-danger btn-block" href="?action=delete&id=<?= $product['id'] ?>" onclick="return confirm('Do you want to delete this data?')">Delete</a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();

                const product_id = $(this).attr('product_id');
                const quantity = $('#quantity_' + product_id).val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        action: 'add_to_cart',
                        product_id: product_id,
                        quantity: quantity
                    },
                    success: function(message) {
                        alert(message);
                    }
                });
            });
        });
    </script>
</body>

</html>