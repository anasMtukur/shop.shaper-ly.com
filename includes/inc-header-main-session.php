<?php
if ( !isset( $_SESSION['sps_logged'] ) ) {
	$from = $_SERVER['REQUEST_URI'];
    header('location:index.php?user_from='.$from);
}

$logged_account = $_SESSION['sps_logged_user_account'];
$logged_role    = $logged_account['is_admin'] === '1' ? 'admin' : 'manager';
?>