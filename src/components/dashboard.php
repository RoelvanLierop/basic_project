<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__, 2 ) . '/' );
    include( '../php/helpers/config.php' );
    include( '../php/classes/user.php' );
    include( '../php/classes/message.php' );
    $user = new User();
    $message = new Message();
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
        <div class="offset-1 col-7">
            <div class="border-primary border rounded p-3">
                <h3>Welcome</h3>
            </div>
        </div>
        <div class="col-3">
            <div class="btn_users bg-primary rounded text-center p-3 pb-2 text-white" role="button">
                <h3>Users</h3>
                <p><?= $user->getUserCount(); ?></p>
            </div>
            <div class="btn_messages bg-primary rounded text-center p-3 pb-2 text-white mt-3" role="button">
                <h3>Messages</h3>
                <p><?= $message->getMessageCount(); ?></p>
            </div>
        </div>
    </div>
</div>