<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__, 2 ) . '/' );
    include( '../php/helpers/config.php' );
    include( '../php/classes/user.php' );
    $user = new User();
    $users = $user->get();
?>
<div class="container-fluid">
    <div class="row bg-secondary">
        <div class="col-10 offset-1 py-2">
            <div id="btn_logout" class="btn btn-primary float-end">Log out</div>
            <div id="btn_dashboard" class="btn btn-primary">Dashboard</div>
            <div class="btn_users btn btn-primary">Users</div>
            <div class="btn_messages btn btn-primary">Messages</div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="offset-1 col-10">
            <div class="border-primary border rounded p-3">
                <h3>Users</h3>
                <div class="bg-primary rounded-top p-3 px-2 row mx-0" id="search_header">
                    <div class="ps-1 col-1"><input type=text class="form-control px-1 rounded-0" id="search_id" placeholder="id" /></div>
                    <div class="ps-1 col-11"><input type=text class="form-control px-1 rounded-0" id="search_data" placeholder="Display name or Email" /></div>
                </div>
                <div id="resultbox" class="border border-primary border-top-0 rounded-bottom p-2 py-1">
                    <?php foreach( $users as $i => $user): ?>
                        <div class="border-border-secondary p-1 row">
                            <div class="col-1"><?= $user['id']; ?></div>
                            <div class="col-5"><?= $user['display_name']; ?></div>
                            <div class="col-6"><?= $user['email']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>