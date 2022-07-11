<?php
    namespace App\Classes\Controllers;

    class ErrorController extends Controller {
        public function displayError() {
            $this->render('views/templates/front',
                'error.html.twig');
        }
    }
