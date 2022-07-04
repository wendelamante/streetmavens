<?php
define('WEBPAGE_TITLE', 'Sign In');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$username = $password = '';
$username_err = $password_err = '';

if (isset($_GET['sign_up']) && $_GET['sign_up'] == 1) {
    echo "<script>alert('Sign up success, you can now sign in!')</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btn_sign_in'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username)) $username_err = 'Please enter username.';
        else if (!is_user_exists($username)) $username_err = 'User does not exist.';
        if (empty($password)) $password_err = 'Please enter password.';

        if (empty($username_err) && empty($password_err)) {
            $result = login_user($username, $password);
            $result_error = $result['error'];

            if ($result_error) {
                if ($result_error == 2) $password_err = 'Invalid password.';
            } else {
                $user = $result['data'];

                // Store user's data in session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['permission'] = $user['permission'];

                header('location: index.php');
                exit();
            }
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
                                <h3 class="card-title mb-3">Sign In</h3>

                                <div class="form-row mb-3">
                                    <input type="text" class="form-control <?php echo empty($username_err) ? '' : 'is-invalid' ?>" name="username" value="<?php echo $username; ?>" placeholder="Username">
                                    <div class="invalid-feedback">
                                        <?php echo $username_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <input type="password" class="form-control <?php echo empty($password_err) ? '' : 'is-invalid' ?>" name="password" placeholder="Password">
                                    <div class="invalid-feedback">
                                        <?php echo $password_err; ?>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <button class="btn btn-secondary btn-block" name="btn_sign_in">Sign In</button>
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