<?php
    namespace App\Classes\Controllers;

    use App\Classes\Entities\User;
    use App\Classes\Models\UserRepository;
    use App\Classes\Models\DataBaseConnection;
    use \PDO;

    class AdminController extends Controller {
        public function showBackOffice() {
            if(isset($_GET["reply"])) {
                $reply = htmlspecialchars($_GET["reply"]);

                $this->render('views/templates/admin',
                    'login.html.twig',
                    ['reply' => $reply]
                );
            } else {
                $this->render('views/templates/admin',
                    'login.html.twig');
            }
        }

        public function loginBackOffice() {
            $result = UserRepository::checkAdminCredentials();

            $this->render('views/templates/admin',
                'login.html.twig',
                ['mailUsername' => $result["mailUsername"],
                'password' => $result["password"],
                'emptyIdentifier' => $result["emptyIdentifier"],
                'emptyPass' => $result["emptyPass"],
                'loginError' => $result["loginError"],
                'admin_id' => $result["admin_id"],
                'admin' => $result["admin"],
                'admin_email' => $result["admin_email"]]
            );
        }
    }
