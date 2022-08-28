<?php
    namespace App\Classes\Middlewares;

    class CheckingLogin {
        public function process() {
            // If no administrator is logged in then we do not go to this page
            if(!isset($_SESSION["admin_id"])) {
                // The user is sent to the login page
                header("location:/blog_php/backoff/login");
            }
        }
    }
