<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__, 2 ) . '/' );
    include( '../php/helpers/config.php' );
    include( '../php/classes/user.php' );
    include( '../php/classes/message.php' );
    $user = new User();
    if( !$user->validate() )
    {
        echo '';
        exit;
    }
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
                <h2>Welcome</h2>
                <p>
                    This tool was created to show some basic functionality, by using plain PHP, basic OOP, and AJAX.
                    Designed as a single page application, we dive into something I never did before.
                    To support this, we use Javascript and Ajax to load pages and process data.
                <p>
                <p>
                    This is not a MVC project, because I wanted to show that plain PHP is still a skill I possess, and that I can use accordingly.
                    When using MVC, I usually prefer a framework like Vue, Laravel, or Yii2. These provide the basis for more elegant coding.
                    In this case the premise was to show more "vanilla" skills instead of relying om a framework to do most of the work.
                    It meant writing plain PHP, MySQL, HTML, and CSS to show these skills, ranging from inline php, to objects and helper methods.
                </p>
                <p>
                    All in all, I still prefer a framework to do the heavy lifting, and the codebase could be smaller and faster, but there should always be room to improve and update.
                </p>
                <h3>Fun Stuff</h3>
                <p class="mt-3">Database</p>
                <ul>
                    <li>The database is created with plain MySQL Statements.</li>
                    <li>The users table has a composite unique index, which includes display_name and email.</li>
                    <li>The users table has a composite index, which includes display_name and email for searching.</li>
                    <li>The messages table has a search index for the message contents.</li>
                </ul>
                <p class="mt-3">General</p>
                <ul>
                    <li>On contrary of the assignment, I tried making the project a single page application.</li>
                    <li>All pages are components, loaded with AJAX.</li>
                    <li>All forms have client-side validation, next to back-end validation of data.</li>
                </ul>
                <p class="mt-3">Login / Register / Search</p>
                <ul>
                    <li>Form submissions are sent as AJAX calls, which load the dashboard when succesful.</li>
                    <li>Searching ranges across multiple fields, the groups are id, email/display_name, and author/message.</li>
                </ul>
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