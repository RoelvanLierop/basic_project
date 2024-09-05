<?php
    session_start();
    include( '../php/classes/csrf.php' );
?>

<div class="col-12 col-md-4 bg-dark rounded text-white p-3">
    <h3>Register new user</h3>
    <form id="register_form" method="POST" action="index.php" enctype="" class="needs-validation" novalidate>
        <input type="hidden" name="CSRF" value="<?php Csrf::generate(); ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="administrator@project.com" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required pattern="[A-Za-z0-9\-_\!]{8,16}">
            <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="display_name" class="form-label">Display name</label>
            <input type="text" class="form-control" id="display_name" name="display_name" placeholder="administrator" required pattern="[A-Za-z0-9\-_\!]{8,32}">
            <div class="invalid-feedback"></div>
        </div>
        <button type="submit" id="btn_submit" class="btn btn-primary float-end mt-2">Register</button>
        <a id="btn_back" class="btn btn-primary mt-2">Back</a>
    </form>
</div>