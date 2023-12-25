<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\RegisteredUser;
$regUser = new RegisteredUser();

$users = $regUser->list();
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/inc-header-main-tags.php" ?>
    <body class="sidebar-icon-only">
        <div class="container-scroller">
            <?php include "includes/inc-header-main-navbar.php" ?>

            <div class="container-fluid page-body-wrapper">

                <?php include "includes/inc-header-main-sidebar.php" ?>

                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php include "includes/inc-alerts.php" ?>
                        <?php UIFragments::renderPageTitle("System Users", "Manage all system users!", "Add User", "href='users_new.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th class="text-center">Admin</th>
                                                        <th class="text-center">Manager</th>
                                                        <th class="text-center">Blocked</th>
                                                        <th>Created</th>
                                                        <th class="text-center">
                                                            <i class="ti-settings"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if( count($users) > 0) { ?>
                                                        <?php foreach( $users as $user ) { ?>
                                                            <tr>
                                                                <td><?= $user['fullname'] ?></td>
                                                                <td><?= $user['username'] ?></td>
                                                                <td class="text-center text-<?= $user['is_admin'] == 1 ? 'success' : 'danger' ?>">
                                                                    <i class="fa fa-<?= $user['is_admin'] == 1 ? 'check' : 'times' ?>"></i>
                                                                </td>
                                                                <td class="text-center text-<?= $user['is_manager'] == 1 ? 'success' : 'danger' ?>">
                                                                    <i class="fa fa-<?= $user['is_manager'] == 1 ? 'check' : 'times' ?>"></i>
                                                                </td>
                                                                <td class="text-center text-<?= $user['is_blocked'] == 1 ? 'success' : 'danger' ?>">
                                                                    <i class="fa fa-<?= $user['is_blocked'] == 1 ? 'check' : 'times' ?>"></i>
                                                                </td>
                                                                <td><?= $user['created_at'] ?></td>
                                                                <td class="text-center">
                                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                                        <a href="users_edit.php?reference=<?= $user['id'] ?>">
                                                                            <button type="button" class="btn mr-2 btn-sm btn-outline-primary"><i class="ti-pencil"></i></button>
                                                                        </a>
                                                                        <form method="POST" action="users_action.php">
                                                                            <input type="hidden" name="reference" value="<?= $user['id'] ?>">
                                                                            <button type="submit" name="block" class="btn btn-sm btn-outline-danger"><i class="fa fa-ban"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7">
                                                                No Users
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer bg-white">
                                        xx
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- /Site Content -->
                    </div>

                    <?php include "includes/inc-footer-main.php" ?>
                </div>

            </div>
        </div>

        <?php include "includes/inc-footer-tags.php" ?>
    </body>
</html>