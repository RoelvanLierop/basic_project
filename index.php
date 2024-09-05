<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__ ) . '/src/' );
    include( 'src/php/helpers/config.php' );
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <body class="bg-light">
        <div class="w-100 h-100 d-flex justify-content-center" id="app"></div>
        </div>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="src/js/app.js"></script>
        <script>
            let homeUrl = 'http://localhost/bdproject';
            <?php if( !isset( $_SESSION['USER'] ) ): ?>
                loadComponent('loginform', 'Bundeling Project | Login');
            <?php else: ?>
                loadComponent('dashboard', 'Bundeling Project | Dashboard');
            <?php endif; ?>
        </script>
    </body>
</html>