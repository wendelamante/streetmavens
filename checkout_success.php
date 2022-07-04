<?php
define('WEBPAGE_TITLE', 'Checkout Success');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$user_id = $_SESSION['id'];

unset($_SESSION['is_checkout_success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('includes/head.php') ?>
</head>

<body>
    <?php include_once('includes/navbar.php') ?>

    <main>
        <div class="container bg-white rounded shadow mt-5">
            <div class="row pl-5 pt-5">.
                <h3>Checkout Success</h3>
            </div>
            <div class="row pt-3 px-3">
                <div class="col-sm">
                    <p>You have successfully purchased all items in your cart. Please wait for the confirmation
                        of the admin. You can check your orders in the order webpage.</p>
                </div>
            </div>
            <div class="row pt-3 px-3 pb-5 justify-content-center">
                <div class="col-sm text-right">
                    <a href="shop.php" class="btn btn-primary">Go to shop</a>
                    <a href="orders.php" class="btn btn-primary">Go to your orders</a>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>
</body>

</html>