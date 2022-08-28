<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Middlewares\CheckingLogin;
    use App\Classes\Models\UserRepository;

    class AuthController extends Controller {
        public function isLogged() {
            // If an administrator is logged in then we no longer return to this page
            if(isset($_SESSION["admin_id"])) {
                header("location:/blog_php/backoff/dashboard");
            }
        }

        public function displayLoginBackOffice() {
            $this->isLogged();

            $this->render('views/templates/front',
                'login.html.twig');
        }

        public function renderFormLoginBackOffice() {
            // If the post login variable is declared and different from NULL
            if(isset($_POST["login"])) {
                $mailUsername  = htmlspecialchars(trim($_POST["mailUsername"])); // Retrieve the content of the "mailUsername" input field
                $password = trim($_POST["password"]); // We recover the password
                $valid = true;
                $errors = [];

                // Check the content of "mailUsername"
                if(empty($mailUsername)){
                    $valid = false;
                    $errors['emptyIdentifier'] = "\"L'identifiant\" ne peut être vide.";
                }

                // Verification of the password
                if(empty($password)) {
                    $valid = false;
                    $errors['emptyPass'] = "Le \"Mot de passe\" ne peut être vide.";
                }

                $user = UserRepository::checkAdminCredentials($mailUsername);

                if($user) {
                    // We check if the password used corresponds to this hash using password_verify
                    $correctPassword = password_verify($password, $user->password);
                }
                else {
                    $correctPassword = false;
                }

                // If there is a result then we load the admin session using the session variables
                if($correctPassword && $valid) {
                    $_SESSION["admin_id"] = htmlspecialchars($user->id_user);
                    $_SESSION["admin"] = htmlspecialchars($user->username);
                    $_SESSION["admin_email"] = htmlspecialchars($user->email);

                    // Send to the back office homepage
                    header("location:/blog_php/backoff/dashboard");
                }
                // Or if we have no result after the verification with password_verify() it means that there is no user corresponding to the couple username or e-mail + password
                else {
                    $errors['loginError'] = "\"L'identifiant\" ou le \"Mot de passe\" est incorrect.";
                }
            }

            $this->render('views/templates/front',
                'login.html.twig',
                ['mailUsername' => $mailUsername,
                'password' => $password,
                'errors' => $errors]
            );
        }

        public function logoutBackOffice() {
            // Destroy all data associated with the current session
            session_destroy();

            // Send to homepage
            header("location:/blog_php/backoff/login");
        }
    }
