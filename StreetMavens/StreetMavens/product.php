<?php
define('WEBPAGE_TITLE', 'Product');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

restrict_webpage(WEBPAGE_TITLE);

$user_id = $_SESSION['id'];

$product_id = $name = $description = $price = $discount_percentage = $stock = '';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = get_product($product_id)['data'];

    $name = $product['name'];
    $description = $product['description'];
    $price = $product['price'];
    $discount_percentage = $product['discount_percentage'];
    $stock = $product['stock'];

    // if (isset($_FILES['upload_image'])) {
    //     echo 'HAS IMAGE';
    // }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        $price = $_POST['price'];
        $discount_percentage = $_POST['discount_percentage'];
        $stock = $_POST['stock'];

        $product_id = add_product($_SESSION['id'], $name, $description, $price, $discount_percentage, $stock);

        $file_name = $_FILES['upload_image']['name'];
        $temp_name = $_FILES['upload_image']['tmp_name'];
        $arr = explode('.', $file_name);
        $extension = end($arr);
        $image_path = "uploads/$product_id.$extension";

        if ($product_id > 0 && set_product_image($product_id, $image_path) && move_uploaded_file($temp_name, $image_path)) {
            echo "<script>alert('Product added successfully!')</script>";
        } else {
            echo "<script>alert('Product failed to add.')</script>";
        }

        $product_id = $name = $description = $price = $discount_percentage = $stock = '';
    } else if (isset($_POST['edit_product'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        $price = $_POST['price'];
        $discount_percentage = $_POST['discount_percentage'];
        $stock = $_POST['stock'];

        if (edit_product($product_id, $name, $description, $price, $discount_percentage, $stock)) {
            if (isset($_FILES['upload_image'])) {
                $file_name = $_FILES['upload_image']['name'];
                $temp_name = $_FILES['upload_image']['tmp_name'];
                $arr = explode('.', $file_name);
                $extension = end($arr);
                $image_path = "uploads/$product_id.$extension";

                move_uploaded_file($temp_name, $image_path);
            }

            echo "<script>alert('Product edited successfully!')</script>";
        } else {
            echo "<script>alert('Product failed to edit.')</script>";
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
        <div class="container bg-white rounded shadow mt-5">
            <div class="row pt-5 px-3">
                <div class="col-sm">
                    <h3><?= isset($_GET['id']) ? 'Edit Product' : 'New Product' ?></h3>
                </div>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
                    </div>
                    <div class="col-sm">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?= $price ?>" required>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"><?= $description ?></textarea>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="discount_percentage">Discount Percentage</label>
                        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="<?= $discount_percentage ?>" required>
                    </div>
                    <div class="col-sm">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?= $stock ?>" required>
                    </div>
                </div>
                <div class="row pt-3 px-3">
                    <div class="col-sm">
                        <label for="upload_image">Upload Image</label>
                        <input type="file" class="form-control" id="upload_image" name="upload_image" <?php if (!isset($_GET['id'])) echo 'required' ?>>
                    </div>
                </div>
                <div class="row pt-3 px-3 pb-5">
                    <div class="col-sm text-right">
                        <?php if (isset($_GET['id'])) { ?>
                            <button class="btn btn-primary" name="edit_product">Edit Product</button>
                        <?php } else { ?>
                            <button class="btn btn-primary" name="add_product">Add Product</button>
                        <?php } ?>
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