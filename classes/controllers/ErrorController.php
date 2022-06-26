<?php
    namespace App\Classes\Controllers;

    class ErrorController extends Controller {
        public function showError() {
            $this->render('error.html.twig');
        }
    }
