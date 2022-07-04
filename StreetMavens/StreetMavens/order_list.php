<?php
define('WEBPAGE_TITLE', 'Order List');

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
                    <h3>Order List</h3>
                    <table id="tbl_orders" class="table table-striped table-bordered bg-white">
                        <thead>
                            <tr style="text-align: center;">
                                <th width="5%">ID</th>
                                <th width="10%">Image</th>
                                <th width="10%">Name</th>
                                <th width="10%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Total Price</th>
                                <th width="10%">Payment Method</th>
                                <th width="10%">Status</th>
                                <th width="15%">Date Added</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
                ajax: {
                    type: 'POST',
                    url: 'action.php',
                    data: {
                        action: 'get_all_pending_orders'
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: 'order_id'
                    },
                    {
                        data: 'image_path'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'payment_method'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'date_added'
                    },
                    {
                        data: 'action'
                    }
                ],
                columnDefs: [{
                        targets: [1, 9],
                        orderable: false
                    },
                    {
                        'searchable': false,
                        'targets': [1, 9]
                    }
                ],
                createdRow: function(row, data, index) {
                    $('td', row).eq(0).addClass('text-center align-middle');

                    $('td', row).eq(1).addClass('align-middle');
                    $('td', row).eq(1).html('<img class="img-fluid" src="' + data['image_path'] + '" alt="Product Image">')

                    $('td', row).eq(2).addClass('align-middle');

                    $('td', row).eq(3).addClass('text-center align-middle');
                    $('td', row).eq(3).html('₱ ' + data['price']);

                    $('td', row).eq(4).addClass('text-center align-middle');

                    $('td', row).eq(5).addClass('text-center align-middle');
                    $('td', row).eq(5).html('₱ ' + data['total_price']);

                    $('td', row).eq(6).addClass('text-center align-middle');
                    $('td', row).eq(7).addClass('text-center align-middle');
                    $('td', row).eq(8).addClass('text-center align-middle');
                    $('td', row).eq(9).addClass('text-center align-middle');
                }
            });

            $('#tbl_orders').on('click', '.confirm_order_item', function() {
                if (confirm('Do you want to confirm this order?')) {
                    const order_id = $(this).attr('order_id');

                    $.ajax({
                        url: 'action.php',
                        method: 'post',
                        data: {
                            action: 'confirm_order_item',
                            order_id: order_id
                        },
                        success: function(success) {
                            if (success) tbl_orders.ajax.reload();;
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>