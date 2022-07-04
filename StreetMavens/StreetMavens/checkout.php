<?php
define('WEBPAGE_TITLE', 'Checkout');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$user_id = $_SESSION['id'];
$user = get_user_info($user_id)['data'];

$first_name = $user['first_name'];
$middle_name = $user['middle_name'];
$last_name = $user['last_name'];

$address = $user['address'];
$contact_number = $user['contact_number'];
$email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        $payment_method = $_POST['payment_method'];

        if (checkout($user_id, $payment_method)) {
            empty_cart($user_id);

            $_SESSION['is_checkout_success'] = true;

            header('Location: checkout_success.php');
            exit();
        } else echo "<script>alert('Checkout failed!')</script>";
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
        <div class="container bg-white rounded shadow mt-5">
            <div class="row pl-5 pt-5">.
                <h3>Checkout</h3>
            </div>
            <form action="" method="POST">
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" value="<?= $first_name ?>" disabled>
                    </div>
                    <div class="col-sm">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" value="<?= $middle_name ?>" disabled>
                    </div>
                    <div class="col-sm">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" value="<?= $last_name ?>" disabled>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="address">Delivery Address</label>
                        <input type="text" class="form-control" id="address" value="<?= $address ?>" disabled>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="contact_number">Contact Number</label>
                        <input type="contact" class="form-control" id="contact_number" value="<?= $contact_number ?>" disabled>
                    </div>
                    <div class="col-sm">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?= $email ?>" disabled>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">-- select one --</option>
                            <option value="Cash On Delivery">Cash on Delivery</option>
                        </select>
                    </div>
                    <div class="col-sm">
                        <label for="total_amount">Total Amount</label>
                        <input type="text" class="form-control text-right" id="total_amount" value="₱ <?= get_all_products_in_cart($user_id)['total'] ?>" disabled>
                    </div>
                </div>
                <div class="row pt-3 px-3 pb-5">
                    <div class="col-sm text-right">
                        <button class="btn btn-primary" name="confirm">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>
</body>

</html>