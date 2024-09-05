<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__, 2 ) . '/' );
    include( '../php/helpers/config.php' );
    include( '../php/classes/message.php' );
    $messages = new Message();
    $messagesCount = $messages->getMessageCount();
    $messages = $messages->get();
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
                <h3>Messages</h3>
                <div class="bg-primary rounded p-3 px-2 row mx-0" id="search_header">
                    <div class="col-9"><input type=text class="form-control px-1 rounded-0" id="search_data" placeholder="Display name or Message" /></div>
                    <div class="col-3 text-white text-end" id="message_count_box">Result: <span id="message_count"><?= $messagesCount ?></span></div>
                </div>
                <div id="resultbox">
                    <?php foreach( $messages as $i => $message): ?>
                        <div class="border border-secondary p-1 border rounded mt-3 p-3">
                            <div class="col-12 text-small mb-2 fw-lighter">Posted By: <?= $message['author']; ?></div>
                            <div class="col-12"><?= $message['message']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>