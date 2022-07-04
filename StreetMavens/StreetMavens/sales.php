<?php
define('WEBPAGE_TITLE', 'Sales');

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
                    <h3>Sales</h3>
                    <table id="tbl_sales" class="table table-striped table-bordered bg-white">
                        <thead>
                            <tr style="text-align: center;">
                                <th width="5%">ID</th>
                                <th width="10%">Image</th>
                                <th width="15%">Name</th>
                                <th width="15%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Total Price</th>
                                <th width="15%">Payment Method</th>
                                <th width="15%">Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sales = get_sales()['data'];

                            foreach ($sales as $sale) { ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $sale['sale_id'] ?></td>
                                    <td class="align-middle">
                                        <img class="img-fluid" src="<?= $sale['image_path'] ?>" alt="Product Image">
                                    </td>
                                    <td class="align-middle"><?= $sale['name'] ?></td>
                                    <td class="text-center align-middle">₱ <?= $sale['price'] ?></td>
                                    <td class="text-center align-middle"><?= $sale['quantity'] ?></td>
                                    <td class="text-center align-middle">₱ <?= $sale['total_price'] ?></td>
                                    <td class="text-center align-middle"><?= $sale['payment_method'] ?></td>
                                    <td class="text-center align-middle"><?= date('M-d-Y', strtotime($sale['date_added'])) ?></td>
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
        let tbl_sales;

        $(document).ready(function() {
            tbl_sales = $('#tbl_sales').DataTable({
                columnDefs: [{
                        targets: [1],
                        orderable: false
                    },
                    {
                        'searchable': false,
                        'targets': [1]
                    }
                ]
            });
        });
    </script>
</body>

</html>