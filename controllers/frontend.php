<?php
    function displayHomePage()
    {
        require_once 'vendor/autoload.php';

        $loader = new \Twig\Loader\FilesystemLoader('views/templates/front');
        $twig = new \Twig\Environment($loader, [
            'cache' => 'cache/twig',
        ]);

        echo $twig->render('index.html.twig');
    }