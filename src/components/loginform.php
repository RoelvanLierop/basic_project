<?php
    session_start();
    include( '../php/classes/csrf.php' );
?>
<div class="col-12 col-md-4 bg-dark rounded text-white p-3">
    <h3>Login</h3>
    <form id="login_form" method="POST" action="index.php" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="CSRF" value="<?php Csrf::generate(); ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="administrator" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required pattern="[A-Za-z0-9\-_\!]{8,16}">
            <div class="invalid-feedback"></div>
        </div>
        <button type="submit" class="btn btn-primary float-end mt-2">Login</button>
        <a id="btn_register" class="btn btn-primary mt-2">Register</a>
    </form>
</div>
<script type="text/javascript">
    console.log("Ready!");
    loginform();
</script>