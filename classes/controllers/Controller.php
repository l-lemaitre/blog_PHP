<?php
    namespace App\Classes\Controllers;

    class Controller {
        protected array $middlewares = [];

        public function __construct() {
            $this->executeMiddlewares();
        }

        private function executeMiddlewares() {
            foreach($this->middlewares as $middleware) {
                $middlewareImplementation = new $middleware();
                $middlewareImplementation->process();
            }
        }

        protected function render($path, $file, $table = []) {
            $loader = new \Twig\Loader\FilesystemLoader($path);
            $twig = new \Twig\Environment($loader, [
                'cache' => 'cache/twig',
            ]);

            echo $twig->render($file, $table);
        }
    }
