<?php
    namespace App\Classes\Controllers;

    class Controller {
        protected function render($path, $file, $table = []) {
            $loader = new \Twig\Loader\FilesystemLoader($path);
            $twig = new \Twig\Environment($loader, [
                'cache' => 'cache/twig',
            ]);

            echo $twig->render($file, $table);
        }
    }
