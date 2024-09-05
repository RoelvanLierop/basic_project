<?php
    session_start();
    include( 'src/php/classes/csrf.php' );

    if( isset( $_POST['dbserver'] ) ) {
        include( 'src/php/classes/installer.php' );
        include( 'src/php/classes/validator.php' );
        $validationData = Validator::check([
            'dbserver' => "/[A-Za-z0-9\.\-_]{8,64}/",
            'dbport' => "/[0-9]{3,5}/",
            'dbname' => "/[A-Za-z0-9\-_]{4,16}/",
            'dbuser' => "/[A-Za-z0-9\-]{4,16}/",
            'dbpassword' => "/[A-Za-z0-9\-_]{8,16}/"
        ]);
        if($validationData['valid'] === true ){
            $installer = new Installer();
            $installer->writeConfig();
            $installer->initializeDB();
    
            if( isset( $_POST['remove_installer'] ) ) {
                chmod("install.php", 0777);
                chmod("src/backup", 0777);
                copy('install.php', 'src/backup/install_backup.php');
                // commented the dangerous part for now!
                // unlink('install.php');
            }
    
            header('Location: index.php');
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bundeling Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/style.css">
  </head>
  <body class="bg-light">
        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
            <div id="install_form" class="col-12 col-md-4 bg-dark rounded text-white p-3">
                <h3>Project installer</h3>
                <form method="POST" action="install.php" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="CSRF" value="<?php Csrf::generate(); ?>">
                    <p>Set your Database credentials</p>
                    <div class="mb-3">
                        <label for="dbserver" class="form-label">Server</label>
                        <input value="<?= $_POST['dbserver']; ?>" type="text" class="form-control" id="dbserver" name="dbserver" placeholder="localhost" required pattern="[A-Za-z0-9\.\-_]{8,64}">
                        <?php if( isset($validationData['errors']['dbserver']) ): ?>
                            <div class="invalid-feedback">
                                <?= $validationData['errors']['dbserver']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="dbport" class="form-label">Port</label>
                        <input value="<?= $_POST['dbport']; ?>" type="text" class="form-control" id="dbport" name="dbport" placeholder="3306" required pattern="[0-9]{3,5}">
                        <?php if( isset($validationData['errors']['dbport']) ): ?>
                            <div class="invalid-feedback">
                                <?= $validationData['errors']['dbport']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="dbname" class="form-label">Database name</label>
                        <input type="text" class="form-control" id="dbname" name="dbname" required pattern="[A-Za-z0-9\-_]{4,16}">
                        <?php if( isset($validationData['errors']['dbname']) ): ?>
                            <div class="invalid-feedback">
                                <?= $validationData['errors']['dbname']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="dbuser" class="form-label">User</label>
                        <input value="<?= $_POST['dbuser']; ?>" type="text" class="form-control" id="dbuser" name="dbuser" placeholder="administrator" required pattern="[A-Za-z0-9\-]{4,16}">
                        <?php if( isset($validationData['errors']['dbuser']) ): ?>
                            <div class="invalid-feedback">
                                <?= $validationData['errors']['dbuser']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="dbpassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="dbpassword" name="dbpassword" required pattern="[A-Za-z0-9\-_]{8,16}">
                        <?php if( isset($validationData['errors']['dbpassword']) ): ?>
                            <div class="invalid-feedback">
                                <?= $validationData['errors']['dbpassword']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p>Would you like to remove the installer file after installation?</p>
                    <div class="form-check">
                        <input class="form-check-input" name="remove_installer" value="1" type="checkbox" value="" id="remove_installer_check">
                        <label class="form-check-label" for="remove_installer_check">Remove installer file</label>
                    </div>
                    <button type="submit" class="btn btn-primary float-end mt-2">Install</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="src/js/app.js"></script>

  </body>
</html>
<?php

?>