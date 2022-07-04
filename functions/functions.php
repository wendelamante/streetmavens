<?php
function restrict_webpage($webpage_title)
{
    switch ($webpage_title) {
        case 'Sign In':
        case 'Sign Up':
            if (isset($_SESSION['id'])) {
                header('location: index.php');
                exit();
            }
            break;
        case 'Cart':
        case 'Orders':
        case 'Account Settings':
            if (!isset($_SESSION['id'])) {
                header('location: index.php');
                exit();
            }
            break;
        case 'Checkout':
            if (!isset($_SESSION['id']) && count(get_all_products_in_cart($_SESSION['id'])['data']) < 1) {
                header('location: index.php');
                exit();
            }
            break;
        case 'Checkout Success':
            if (!isset($_SESSION['is_checkout_success']) || $_SESSION['is_checkout_success'] === false) {
                header('location: index.php');
                exit();
            }
            break;
        case 'Product':
        case 'Order List':
        case 'Sales':
            if (!isset($_SESSION['id']) || $_SESSION['permission'] !== 'admin') {
                header('location: index.php');
                exit();
            }
            break;
    }
}

// User
function login_user($username, $password)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare("SELECT id, username, password, permission FROM users WHERE username = ? LIMIT 1");

    if (
        $stmt &&
        $stmt->bind_param('s', $username) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($id, $username, $hashed_password, $permission) &&
        $stmt->fetch()
    ) {
        if (password_verify($password, $hashed_password)) {
            $data['id'] = $id;
            $data['permission'] = $permission;
        } else $error = 2;
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}

function register_user($username, $password, $first_name, $middle_name, $last_name, $address, $email, $contact_number)
{
    global $__conn;

    $stmt = $__conn->prepare(
        "INSERT INTO users
        (username, password, first_name, middle_name, last_name, address, email, contact_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    return ($stmt &&
        $stmt->bind_param('ssssssss', $username, $password, $first_name, $middle_name, $last_name, $address, $email, $contact_number) &&
        $stmt->execute());
}

function is_user_exists($username)
{
    global $__conn;

    $stmt = $__conn->prepare("SELECT NULL FROM users WHERE username = ? LIMIT 1");

    return ($stmt &&
        $stmt->bind_param('s', $username) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->num_rows() == 1);
}

function is_email_exists($email)
{
    global $__conn;

    $stmt = $__conn->prepare("SELECT NULL FROM users WHERE email = ? LIMIT 1");

    return ($stmt &&
        $stmt->bind_param('s', $email) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->num_rows() == 1);
}

function get_user_info($user_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT username, password, first_name, middle_name, last_name,
        address, contact_number, email
        FROM users
        WHERE id = ? LIMIT 1
    "
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $user_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $username,
            $password,
            $first_name,
            $middle_name,
            $last_name,
            $address,
            $contact_number,
            $email
        ) &&
        $stmt->fetch()
    ) {
        $data['username'] = $username;
        $data['password'] = $password;
        $data['first_name'] = $first_name;
        $data['middle_name'] = $middle_name;
        $data['last_name'] = $last_name;
        $data['address'] = $address;
        $data['contact_number'] = $contact_number;
        $data['email'] = $email;
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}


function edit_user_info($user_id, $first_name, $middle_name, $last_name, $address, $contact_number, $email)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "UPDATE users
        SET first_name = ?, middle_name = ?, last_name = ?, address = ?, contact_number = ?, email = ?
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('ssssssi', $first_name, $middle_name, $last_name, $address, $contact_number, $email, $user_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function edit_user_password($user_id, $new_password)
{
    global $__conn;

    $affected_rows = 0;
    $new_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $__conn->prepare(
        "UPDATE users
        SET password = ?
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('si', $new_password, $user_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

// Product
function get_all_products()
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path,
        (SELECT IFNULL(SUM(orders.quantity), 0) FROM orders WHERE orders.product_id = products.id) AS sold
        FROM products
        ORDER BY sold DESC"
    );

    if (
        $stmt &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($id, $name, $description, $price, $discount_percentage, $stock, $image_path, $sold)
    ) {
        while ($stmt->fetch()) {
            $data[] = array(
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount_percentage' => $discount_percentage,
                'stock' => $stock,
                'image_path' => $image_path,
                'sold' => $sold
            );
        }
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}

function get_product($product_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT id, name, description, price, discount_percentage, stock, image_path
        FROM products WHERE id = ? LIMIT 1"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $product_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($id, $name, $description, $price, $discount_percentage, $stock, $image_path) &&
        $stmt->num_rows() == 1 &&
        $stmt->fetch()
    ) {
        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'discount_percentage' => $discount_percentage,
            'stock' => $stock,
            'image_path' => $image_path
        );
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}

function edit_product_stock($product_id, $stock)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "UPDATE products
        SET stock = ?
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('ii', $stock, $product_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function add_product($user_id, $name, $description, $price, $discount_percentage, $stock)
{
    global $__conn;

    $stmt = $__conn->prepare(
        "INSERT INTO products
        (user_id, name, description, price, discount_percentage, stock) VALUES
        (?, ?, ?, ?, ?, ?)"
    );

    return ($stmt &&
        $stmt->bind_param('issiii', $user_id, $name, $description, $price, $discount_percentage, $stock) &&
        $stmt->execute()) ? $stmt->insert_id : 0;
}

function edit_product($product_id,  $name,  $description,  $price,  $discount_percentage, $stock)
{
    global $__conn;

    $stmt = $__conn->prepare(
        "UPDATE products
        SET name = ?, description = ?,  price = ?,  discount_percentage = ?, stock = ?
        WHERE id = ?"
    );

    return ($stmt &&
        $stmt->bind_param('ssiiii', $name,  $description,  $price,  $discount_percentage, $stock, $product_id) &&
        $stmt->execute());
}

function set_product_image($product_id, $image_path)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare("UPDATE products SET image_path = ? WHERE id = ?");

    if (
        $stmt &&
        $stmt->bind_param('si', $image_path, $product_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function delete_product($product_id)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare("DELETE FROM products WHERE id = ?");

    if (
        $stmt &&
        $stmt->bind_param('i', $product_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

// Cart
function empty_cart($user_id)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "DELETE
        FROM cart
        WHERE user_id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $user_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function remove_cart_item($cart_id)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "DELETE
        FROM cart
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $cart_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function edit_cart_item_quantity($cart_id, $quantity)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "UPDATE cart
        SET quantity = ?
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('ii', $quantity, $cart_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function get_cart_item($cart_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare("SELECT product_id, quantity FROM cart WHERE id = ? LIMIT 1");

    if (
        $stmt &&
        $stmt->bind_param('i', $cart_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($product_id, $quantity) &&
        $stmt->num_rows() == 1 &&
        $stmt->fetch()
    ) {
        $data['product_id'] = $product_id;
        $data['quantity'] = $quantity;
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}

function get_all_products_in_cart($user_id)
{
    global $__conn;

    $error = 0;
    $data = array();
    $total = 0;

    $stmt = $__conn->prepare(
        "SELECT cart.id, products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path, cart.quantity, cart.date_added
        FROM cart
        INNER JOIN products ON products.id = cart.product_id
        WHERE cart.user_id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $user_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $cart_id,
            $product_id,
            $name,
            $description,
            $price,
            $discount_percentage,
            $stock,
            $image_path,
            $quantity,
            $date_added
        )
    ) {
        while ($stmt->fetch()) {
            $discounted_price = $price - ($price * ($discount_percentage / 100));
            $total += $discounted_price * $quantity;

            $data[] = array(
                'cart_id' => $cart_id,
                'product_id' => $product_id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount_percentage' => $discount_percentage,
                'stock' => $stock,
                'image_path' => $image_path,
                'quantity' => $quantity,
                'date_added' => $date_added
            );
        }
    }

    return array(
        'error' => $error,
        'data' => $data,
        'total' => number_format($total, 2)
    );
}

function get_product_in_cart($user_id, $product_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");

    if (
        $stmt &&
        $stmt->bind_param('ii', $user_id, $product_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result($id, $quantity) &&
        $stmt->num_rows() == 1 &&
        $stmt->fetch()
    ) {
        $data['id'] = $id;
        $data['quantity'] = $quantity;
    } else $error = 1;

    return array(
        'error' => $error,
        'data' => $data
    );
}

function add_to_cart($user_id, $product_id, $quantity)
{
    global $__conn;

    $result = get_product_in_cart($user_id, $product_id);

    if (empty($result['error'])) {
        $data = $result['data'];

        $cart_id = $data['id'];
        $new_quantity = $quantity + $data['quantity'];

        $stmt = $__conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");

        return ($stmt &&
            $stmt->bind_param('ii', $new_quantity, $cart_id) &&
            $stmt->execute()) ? 'Item is already exists in your cart, merged successfully!' : 'Item adding failed.';
    }

    $stmt = $__conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");

    return ($stmt &&
        $stmt->bind_param('iii', $user_id, $product_id, $quantity) &&
        $stmt->execute()) ? 'Item added to your cart!' : 'Item adding failed.';
}

// Checkout
function checkout($user_id, $payment_method)
{
    global $__conn;

    $cart_items = get_all_products_in_cart($user_id)['data'];
    $count = 0;

    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        $stmt = $__conn->prepare("INSERT INTO orders (user_id, product_id, quantity, payment_method) VALUES (?, ?, ?, ?)");

        if (
            $stmt &&
            $stmt->bind_param('iiis', $user_id, $product_id, $quantity, $payment_method) &&
            $stmt->execute()
        ) ++$count;
    }

    return $count > 0;
}

function get_user_orders($user_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT orders.id, products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path,
        orders.quantity, orders.status, orders.payment_method, orders.date_added
        FROM orders
        INNER JOIN products ON products.id = orders.product_id
        WHERE orders.user_id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $user_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $order_id,
            $product_id,
            $name,
            $description,
            $price,
            $discount_percentage,
            $stock,
            $image_path,
            $quantity,
            $status,
            $payment_method,
            $date_added
        )
    ) {
        while ($stmt->fetch()) {
            $data[] = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount_percentage' => $discount_percentage,
                'stock' => $stock,
                'image_path' => $image_path,
                'quantity' => $quantity,
                'status' => $status,
                'payment_method' => $payment_method,
                'date_added' => $date_added
            );
        }
    }

    return array(
        'error' => $error,
        'data' => $data,
    );
}

function remove_order_item($order_id)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "DELETE
        FROM orders
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $order_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}


function get_all_pending_orders()
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT orders.id, products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path,
        orders.quantity, orders.status, orders.payment_method, orders.date_added
        FROM orders
        INNER JOIN products ON products.id = orders.product_id
        WHERE orders.status = 'Pending'"
    );

    if (
        $stmt &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $order_id,
            $product_id,
            $name,
            $description,
            $price,
            $discount_percentage,
            $stock,
            $image_path,
            $quantity,
            $status,
            $payment_method,
            $date_added
        )
    ) {
        while ($stmt->fetch()) {
            $discounted_price = $price - ($price * ($discount_percentage / 100));

            $data[] = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'name' => $name,
                'description' => $description,
                'price' => number_format($discounted_price, 2),
                'discount_percentage' => $discount_percentage,
                'stock' => $stock,
                'image_path' => $image_path,
                'quantity' => $quantity,
                'status' => $status,
                'payment_method' => $payment_method,
                'total_price' => number_format($discounted_price * $quantity, 2),
                'action' => '',
                'date_added' => $date_added
            );
        }
    }

    return array(
        'error' => $error,
        'data' => $data,
    );
}

function get_pending_order($order_id)
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT orders.id, products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path,
        orders.quantity, orders.status, orders.payment_method, orders.date_added
        FROM orders
        INNER JOIN products ON products.id = orders.product_id
        WHERE orders.id = ? AND orders.status = 'Pending' LIMIT 1"
    );

    if (
        $stmt &&
        $stmt->bind_param('i', $order_id) &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $order_id,
            $product_id,
            $name,
            $description,
            $price,
            $discount_percentage,
            $stock,
            $image_path,
            $quantity,
            $status,
            $payment_method,
            $date_added
        ) &&
        $stmt->num_rows() === 1 &&
        $stmt->fetch()
    ) {
        $discounted_price = $price - ($price * ($discount_percentage / 100));

        $data = array(
            'order_id' => $order_id,
            'product_id' => $product_id,
            'name' => $name,
            'description' => $description,
            'price' => number_format($discounted_price, 2),
            'discount_percentage' => $discount_percentage,
            'stock' => $stock,
            'image_path' => $image_path,
            'quantity' => $quantity,
            'status' => $status,
            'payment_method' => $payment_method,
            'total_price' => number_format($discounted_price * $quantity, 2),
            'date_added' => $date_added
        );
    }

    return array(
        'error' => $error,
        'data' => $data,
    );
}

function set_order_status($order_id, $status)
{
    global $__conn;

    $affected_rows = 0;

    $stmt = $__conn->prepare(
        "UPDATE orders
        SET status = ?
        WHERE id = ?"
    );

    if (
        $stmt &&
        $stmt->bind_param('si', $status, $order_id) &&
        $stmt->execute()
    ) $affected_rows = $stmt->affected_rows;

    return $affected_rows > 0;
}

function confirm_order($order_id)
{
    $order = get_pending_order($order_id)['data'];

    return edit_product_stock($order['product_id'], $order['stock'] - $order['quantity']) &&
        set_order_status($_POST['order_id'], 'Confirmed');
}

function get_sales()
{
    global $__conn;

    $error = 0;
    $data = array();

    $stmt = $__conn->prepare(
        "SELECT orders.id, products.id, products.name, products.description, products.price,
        products.discount_percentage, products.stock, products.image_path,
        orders.quantity, orders.payment_method, orders.date_added
        FROM orders
        INNER JOIN products ON products.id = orders.product_id
        WHERE orders.status = 'Confirmed'"
    );

    if (
        $stmt &&
        $stmt->execute() &&
        $stmt->store_result() &&
        $stmt->bind_result(
            $sale_id,
            $product_id,
            $name,
            $description,
            $price,
            $discount_percentage,
            $stock,
            $image_path,
            $quantity,
            $payment_method,
            $date_added
        )
    ) {
        while ($stmt->fetch()) {
            $discounted_price = $price - ($price * ($discount_percentage / 100));

            $data[] = array(
                'sale_id' => $sale_id,
                'product_id' => $product_id,
                'name' => $name,
                'description' => $description,
                'price' => number_format($discounted_price, 2),
                'discount_percentage' => $discount_percentage,
                'stock' => $stock,
                'image_path' => $image_path,
                'quantity' => $quantity,
                'payment_method' => $payment_method,
                'total_price' => number_format($discounted_price * $quantity, 2),
                'date_added' => $date_added
            );
        }
    }

    return array(
        'error' => $error,
        'data' => $data,
    );
}
