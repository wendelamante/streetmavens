<?php
define('WEBPAGE_TITLE', 'Account Settings');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$user_id = $_SESSION['id'];
$user = get_user_info($user_id)['data'];

$username = $user['username'];
$first_name = $user['first_name'];
$middle_name = $user['middle_name'];
$last_name = $user['last_name'];

$address = $user['address'];
$contact_number = $user['contact_number'];
$email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_changes'])) {
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];

        $address = $_POST['address'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];

        $result = edit_user_info($user_id, $first_name, $middle_name, $last_name, $address, $contact_number, $email) ? 'User info successfully updated!' : 'User info failed to update.';

        echo "<script>alert('$result')</script>";
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
            <div class="row pt-5 px-3">
                <div class="col-sm">
                    <h3>Account Settings</h3>
                </div>
            </div>
            <form action="" method="POST">
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" disabled>
                    </div>
                    <div class="col-sm">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $first_name ?>" required>
                    </div>
                    <div class="col-sm">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= $middle_name ?>" required>
                    </div>
                    <div class="col-sm">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $last_name ?>" required>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="address">Delivery Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $address ?>" required>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="contact_number">Contact Number</label>
                        <input type="contact" class="form-control" id="contact_number" name="contact_number" value="<?= $contact_number ?>" required>
                    </div>
                    <div class="col-sm">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    </div>
                </div>
                <div class="row pt-3 px-3 pb-5">
                    <div class="col-sm">
                        <button class="btn btn-primary" id="change_password">Change Password</button>
                    </div>
                    <div class="col-sm text-right">
                        <button class="btn btn-primary" name="save_changes">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password" class="col-form-label">Current Password:</label>
                        <input type="password" class="form-control" id="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="col-form-label">New Password:</label>
                        <input type="password" class="form-control" id="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save_changes_in_password">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>

    <script>
        $(document).ready(function() {
            $('#change_password').click(function(e) {
                e.preventDefault();

                $('#changePassword').modal('show');
            });

            $('#save_changes_in_password').click(function() {
                const current_password = $('#current_password').val();
                const new_password = $('#new_password').val();
                const confirm_password = $('#confirm_password').val();

                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: {
                        action: 'change_user_password',
                        current_password: current_password,
                        new_password: new_password,
                        confirm_password: confirm_password
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