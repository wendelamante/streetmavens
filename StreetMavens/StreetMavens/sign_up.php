<?php
define('WEBPAGE_TITLE', 'Sign Up');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$username = $password = $confirm_password = $first_name = $middle_name = $last_name = $address = $email = $contact_number = '';
$username_err = $password_err = $confirm_password_err = $first_name_err = $middle_name_err = $last_name_err = $address_err = $email_err = $contact_number_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btn_sign_up'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $first_name = trim($_POST['first_name']);
        $middle_name = trim($_POST['middle_name']);
        $last_name = trim($_POST['last_name']);
        $address = trim($_POST['address']);
        $email = trim($_POST['email']);
        $contact_number = $_POST['contact_number'];

        // Validate username
        if (empty($username)) $username_err = 'Please enter username.';
        else if (is_user_exists($username)) $username_err = 'Username already exist.';

        // Validate password
        if (empty($password)) $password_err = 'Please enter password.';
        else if (strlen($password) < 8) $password_err = 'Password must be 8 characters and above.';
        else {
            $containLowercase = preg_match('/[a-z]/', $password);
            $containUppercase = preg_match('/[A-Z]/', $password);
            $containDigit = preg_match('/\d/', $password);
            $containSpecialCharacter = preg_match('/[^a-zA-Z\d]/', $password);

            if (!$containLowercase) $password_err = 'Password must contain lowercase.';
            else if (!$containUppercase) $password_err = 'Password must contain uppercase.';
            else if (!$containDigit) $password_err = 'Password must contain number.';
            else if (!$containSpecialCharacter) $password_err = 'Password must contain special character.';
        }

        // Validate confirm password
        if (empty($confirm_password)) $confirm_password_err = 'Please confirm password.';
        else {
            if (empty($password_err) && ($password == $confirm_password)) $password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            else $confirm_password_err = 'Password did not match.';
        }

        // Validate first name
        if (empty($first_name)) $first_name_err = 'Please enter first name.';

        // Validate middle name
        if (empty($middle_name)) $middle_name_err = 'Please enter middle name.';

        // Validate last name
        if (empty($last_name)) $last_name_err = 'Please enter last name.';

        // Validate address
        if (empty($address)) $address_err = 'Please enter address.';

        // Validate email
        if (empty($email)) $email_err = 'Please enter email.';
        else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (is_email_exists($email)) $email_err = 'Email already exist.';
        } else $email_err = 'Invalid email format.';

        // Validate contact number
        if (empty($contact_number)) $contact_number_err = 'Please enter contact number.';

        // Check input errors before inserting in database
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($address_err) && empty($email_err) && empty($contact_number_err)) {
            if (register_user($username, $password, $first_name, $middle_name, $last_name, $address, $email, $contact_number)) header('location: sign_in.php?sign_up=1');
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
            <div class="row mt-5 mb-4 justify-content-md-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST">
                                <h3 class="card-title mb-3">Sign Up</h3>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($username_err)) echo 'is-invalid'; ?>" name="username" value="<?php echo $username; ?>" placeholder="Username">
                                    <div class="invalid-feedback">
                                        <?php echo $username_err; ?>
                                    </div>
                                </div>


                                <div class="form-row mb-3">
                                    <input type="password" class="form-control <?php if (!empty($password_err)) echo 'is-invalid'; ?>" name="password" placeholder="Password">
                                    <div class="invalid-feedback">
                                        <?php echo $password_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="password" class="form-control <?php if (!empty($confirm_password_err)) echo 'is-invalid'; ?>" name="confirm_password" placeholder="Confirm Password">
                                    <div class="invalid-feedback">
                                        <?php echo $confirm_password_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($first_name_err)) echo 'is-invalid'; ?>" name="first_name" value="<?php echo $first_name; ?>" placeholder="First Name">
                                    <div class="invalid-feedback">
                                        <?php echo $first_name_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($middle_name_err)) echo 'is-invalid'; ?>" name="middle_name" value="<?php echo $middle_name; ?>" placeholder="Middle Name">
                                    <div class="invalid-feedback">
                                        <?php echo $middle_name_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($last_name_err)) echo 'is-invalid'; ?>" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name">
                                    <div class="invalid-feedback">
                                        <?php echo $last_name_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($address_err)) echo 'is-invalid'; ?>" name="address" value="<?php echo $address; ?>" placeholder="Address">
                                    <div class="invalid-feedback">
                                        <?php echo $address_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="email" class="form-control <?php if (!empty($email_err)) echo 'is-invalid'; ?>" name="email" value="<?php echo $email; ?>" placeholder="Email">
                                    <div class="invalid-feedback">
                                        <?php echo $email_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php if (!empty($contact_number_err)) echo 'is-invalid'; ?>" name="contact_number" value="<?php echo $contact_number; ?>" placeholder="Contact Number">
                                    <div class="invalid-feedback">
                                        <?php echo $contact_number_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <button class="btn btn-secondary btn-block" name="btn_sign_up">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>
</body>

</html>