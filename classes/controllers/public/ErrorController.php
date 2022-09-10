<?php
    namespace App\Classes\Controllers\Public;

    use App\Classes\Controllers\Controller;

    class ErrorController extends Controller {
        public function displayError() {
            $this->render('../views/templates/front',
                'error.html.twig');
        }
    }
