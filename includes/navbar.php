<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top p-2">
    <a class="nav-brand" href="index.php">
        <img src="images/logo.png" alt="Logo" height="50">
    </a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav text-center p-2">
            <!-- Home -->
            <li class="nav-item <?php if (WEBPAGE_TITLE === 'Home') echo 'active'; ?>">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <!-- Shop -->
            <li class="nav-item <?php if (WEBPAGE_TITLE === 'Shop') echo 'active'; ?>">
                <a class="nav-link" href="shop.php">Shop</a>
            </li>
            <?php
            if (isset($_SESSION['id'])) {
                if ($_SESSION['permission'] === 'normal') {
                    // Cart
                    echo
                    '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Cart') ? 'active' : '') . '">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>';

                    // Orders
                    echo
                    '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Orders') ? 'active' : '') . '">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>';
                } else {
                    // Order List
                    echo
                    '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Order List') ? 'active' : '') . '">
                        <a class="nav-link" href="order_list.php">Order List</a>
                    </li>';

                    // Sales
                    echo
                    '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Sales') ? 'active' : '') . '">
                        <a class="nav-link" href="sales.php">Sales</a>
                    </li>';
                }

                // Account Settings
                echo
                '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Account Settings') ? 'active' : '') . '">
                    <a class="nav-link" href="account_settings.php">Account Settings</a>
                </li>';

                // Sign Out
                echo
                '<li class="nav-item">
                    <a class="nav-link" href="sign_out.php">Sign Out</a>
                </li>';
            } else {
                // Sign In
                echo
                '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Sign In') ? 'active' : '') . '">
                    <a class="nav-link" href="sign_in.php">Sign In</a>
                </li>';

                // Sign Up
                echo
                '<li class="nav-item ' . ((WEBPAGE_TITLE === 'Sign Up') ? 'active' : '') . '">
                    <a class="nav-link" href="sign_up.php">Sign Up</a>
                </li>';
            }
            ?>
        </ul>
    </div>
</nav>