<?php
    namespace App\Classes\Models;

    use App\Classes\Entities\User;
    use \PDO;

    class UserRepository
    {
        public static function getUsernameById($id)
        {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT `username` FROM `user` WHERE `id_user` = ?";
            $resultSet = $bdd->query($query, array($id));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function checkAdminCredentials() {
            // If the post login variable is declared and different from NULL
            if(isset($_POST["login"])) {
                $mailUsername  = htmlspecialchars(trim($_POST["mailUsername"])); // Retrieve the content of the "mailUsername" input field
                $password = trim($_POST["password"]); // We recover the password
                $valid = true;
                $emptyIdentifier = false;
                $emptyPass = false;
                $loginError = false;
                $_SESSION["admin_id"] = false;
                $_SESSION["admin"] = false;
                $_SESSION["admin_email"] = false;

                // Check the content of "mailUsername"
                if(empty($mailUsername)){
                    $valid = false;
                    $emptyIdentifier = true;
                }

                // Verification of the password
                if(empty($password)) {
                    $valid = false;
                    $emptyPass = true;
                }

                $bdd = DataBaseConnection::getConnect();

                // We request the hash for this user from our database
                $query = "SELECT `password` FROM `user` WHERE (`username` = ? OR `email` = ?)";
                $resultSet = $bdd->query($query, [$mailUsername, $mailUsername]);
                $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
                $hash = $resultSet->fetch();

                if($hash) {
                    // We check if the password used corresponds to this hash using password_verify
                    $correctPassword = password_verify($password, $hash->password);
                }

                else {
                    $correctPassword = false;
                }

                if($correctPassword) {
                    // We make a request to know if the name of the admin exists because it is unique
                    $query = "SELECT * FROM `user` WHERE (`username` = ? OR `email` = ?)";
                    $resultSet = $bdd->query($query, [$mailUsername, $mailUsername]);
                    $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
                    $user = $resultSet->fetch();

                    // If there is a result then we load the admin session using the session variables
                    if($valid) {
                        $_SESSION["admin_id"] = htmlspecialchars($user->id_user);
                        $_SESSION["admin"] = htmlspecialchars($user->username);
                        $_SESSION["admin_email"] = htmlspecialchars($user->email);
                    }
                }

                // Or if we have no result after the verification with password_verify() it means that there is no user corresponding to the couple username or e-mail + password
                else {
                    $loginError = true;
                }
            }

            return [
                'mailUsername' => $mailUsername,
                'password' => $password,
                'emptyIdentifier' => $emptyIdentifier,
                'emptyPass' => $emptyPass,
                'loginError' => $loginError,
                'admin_id' => $_SESSION["admin_id"],
                'admin' => $_SESSION["admin"],
                'admin_email' => $_SESSION["admin_email"]
            ];
        }
    }
