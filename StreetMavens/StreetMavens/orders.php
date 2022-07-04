<?php
define('WEBPAGE_TITLE', 'Orders');

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
                    <h3>Orders</h3>
                    <table id="tbl_orders" class="table table-striped table-bordered bg-white">
                        <thead>
                            <tr style="text-align: center;">
                                <th width="5%">ID</th>
                                <th width="10%">Image</th>
                                <th width="15%">Name</th>
                                <th width="10%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Total Price</th>
                                <th width="10%">Payment Method</th>
                                <th width="10%">Status</th>
                                <th width="15%">Date Added</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orders = get_user_orders($_SESSION['id'])['data'];

                            foreach ($orders as $order) {
                                $price = $order['price'];
                                $discounted_price = $price - ($price * ($order['discount_percentage'] / 100));
                                $can_cancel = ($order['status'] !== 'Confirmed'); ?>
                                <tr id="tr_order_item_<?= $order['order_id'] ?>">
                                    <td class="text-center align-middle"><?= $order['order_id'] ?></td>
                                    <td class="align-middle">
                                        <img class="img-fluid" src="<?= $order['image_path'] ?>" alt="Product Image">
                                    </td>
                                    <td class="align-middle"><?= $order['name'] ?></td>
                                    <td class="text-center align-middle">₱ <?= number_format($discounted_price, 2) ?></td>
                                    <td class="text-center align-middle"><?= $order['quantity'] ?></td>
                                    <td class="text-center align-middle">₱ <?= number_format($discounted_price * $order['quantity'], 2) ?></td>
                                    <td class="text-center align-middle"><?= $order['payment_method'] ?></td>
                                    <td class="text-center align-middle"><?= $order['status'] ?></td>
                                    <td class="text-center align-middle"><?= date('M-d-Y', strtotime($order['date_added'])) ?></td>
                                    <td class="text-center align-middle">
                                        <span class="text-danger lead <?php if ($can_cancel) echo 'remove_order_item'; ?>" style="cursor: <?= $can_cancel ? 'pointer' : 'not-allowed' ?>; opacity: <?= $can_cancel ? '100%' : '50%' ?>;" <?php if ($can_cancel) echo 'order_id="' . $order['order_id'] . '"'; ?>><i class="fas fa-times"></i></span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>

    <script>
        let tbl_orders;

        $(document).ready(function() {
            tbl_orders = $('#tbl_orders').DataTable({
                columnDefs: [{
                        targets: [1, 9],
                        orderable: false
                    },
                    {
                        'searchable': false,
                        'targets': [1, 9]
                    }
                ]
            });

            $('.remove_order_item').click(function() {
                if (confirm('Do you want to cancel this order?')) {
                    const order_id = $(this).attr('order_id');
                    const tr = $('#tr_order_item_' + order_id);

                    $.ajax({
                        url: 'action.php',
                        method: 'post',
                        data: {
                            action: 'remove_order_item',
                            order_id: order_id
                        },
                        success: function(success) {
                            if (success) tbl_orders.row(tr).remove().draw(false);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>