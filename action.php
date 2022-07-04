<?php
require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');

if (isset($_POST['action'])) {
	$action = $_POST['action'];

	if ($action === 'add_to_cart') {
		$product_id = $_POST['product_id'];
		$quantity = $_POST['quantity'];

		if (isset($_SESSION['id'])) echo add_to_cart($_SESSION['id'], $product_id, $quantity);
		else echo 'Please Sign In first.';
	} else if ($action === 'empty_cart') {
		echo empty_cart($_SESSION['id']);
	} else if ($action === 'remove_cart_item') {
		echo remove_cart_item($_POST['cart_id']);
	} else if ($action === 'edit_cart_item_quantity') {
		$cart_id = $_POST['cart_id'];
		$quantity = $_POST['quantity'];
		$success = false;

		if ($quantity < 1) $success = remove_cart_item($cart_id);
		else $success = edit_cart_item_quantity($cart_id, $quantity);

		echo ($quantity < 1) ? 'remove_item' : $success;
	} else if ($action === 'cart_product_total_price') {
		$cart_id = $_POST['cart_id'];
		$cart_item = get_cart_item($cart_id)['data'];

		$product = get_product($cart_item['product_id'])['data'];
		$price = $product['price'];
		$discounted_price = $price - ($price * ($product['discount_percentage'] / 100));

		$quantity = $cart_item['quantity'];
		$total_price = $discounted_price * $quantity;

		echo number_format($total_price, 2);
	} else if ($action === 'cart_items_total_price') {
		echo get_all_products_in_cart($_SESSION['id'])['total'];
	} else if ($action === 'change_user_password') {
		$user_id = $_SESSION['id'];
		$current_password = $_POST['current_password'];
		$new_password = $_POST['new_password'];
		$confirm_password = $_POST['confirm_password'];

		if ($new_password === $confirm_password) {
			$hashed_old_password = get_user_info($user_id)['data']['password'];

			if (password_verify($current_password, $hashed_old_password)) {
				echo edit_user_password($user_id, $new_password) ? 'Password updated successfully!' : 'Password failed to update.';
			} else echo 'Current password does not match to your current password.';
		} else echo 'Password does not match.';
	} else if ($action === 'remove_order_item') {
		echo remove_order_item($_POST['order_id']);
	} else if ($action === 'get_all_pending_orders') {
		$all_pending_orders = get_all_pending_orders()['data'];
		$len = count($all_pending_orders);

		for ($i = 0; $i < $len; ++$i) {
			$can_accept = ($all_pending_orders[$i]['stock'] >= $all_pending_orders[$i]['quantity']);

			$action = '<span class="text-success lead ';

			if ($can_accept) $action .= 'confirm_order_item';

			$action .= '" style="cursor: ' .  ($can_accept ? 'pointer' : 'not-allowed') . '; opacity: ' . ($can_accept ? '100%' : '50%') . '"';

			if ($can_accept) $action .= ' order_id="' . $all_pending_orders[$i]['order_id'] . '"';

			$action .= '><i class="fas fa-check"></i></span>';

			$all_pending_orders[$i]['action'] = $action;
			$all_pending_orders[$i]['date_added'] = date('M-d-Y', strtotime($all_pending_orders[$i]['date_added']));
		}

		echo json_encode($all_pending_orders);
	} else if ($action === 'confirm_order_item') {
		echo confirm_order($_POST['order_id']);
	}
}
