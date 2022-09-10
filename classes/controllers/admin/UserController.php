<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Exceptions\NotFoundException;
    use App\Classes\Middlewares\CheckingLogin;
    use App\Classes\Models\UserRepository;
    use App\Classes\Services\Mail;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

    class UserController extends Controller {
        protected array $middlewares = [ CheckingLogin::class ];

        public function displayUsers() {
            $users = UserRepository::getUsers();

            if(isset($_GET["reply"])) {
                $reply = filter_input(INPUT_GET, 'reply', FILTER_SANITIZE_URL);

                $reply = htmlspecialchars($reply);
            } else {
                $reply = false;
            }

            $this->render('../views/templates/admin',
                'users.html.twig',
                ['users' => $users,
                'admin' => $_SESSION["admin"],
                'reply' => $reply]
            );
        }

        public function renderFormResetUser() {
            if (isset($_POST["resetUser"])) {
                $idUser = filter_input(INPUT_POST, 'resetUser', FILTER_VALIDATE_INT);

                $idUser = htmlspecialchars($idUser);

                UserRepository::resetUser($idUser);

                if ($_SESSION["admin_id"] == $idUser) {
                    header("location:logout");
                } else {
                    header("location:users?page=1");
                }
            }
        }

        public function displayAddAdmin() {
            $this->render('../views/templates/admin',
                'add_admin.html.twig',
                ['admin' => $_SESSION["admin"]]
            );
        }

        public function renderFormAddAdmin() {
            if (isset($_POST["addAdmin"])) {
                $args = [
                    'lastname' => FILTER_SANITIZE_STRING,
                    'firstname' => FILTER_SANITIZE_STRING,
                    'username' => FILTER_SANITIZE_STRING,
                    'email' => FILTER_SANITIZE_STRING,
                    'password' => FILTER_SANITIZE_STRING,
                    'passwordConfirmation' => FILTER_SANITIZE_STRING
                ];

                $formInputs = filter_input_array(INPUT_POST, $args);

                $lastname = htmlspecialchars(trim($formInputs["lastname"]));
                $firstname = htmlspecialchars(trim($formInputs["firstname"]));
                $username = htmlspecialchars(trim($formInputs["username"]));
                $email = htmlspecialchars(strtolower(trim($formInputs["email"])));
                $password = trim($formInputs["password"]);
                $passwordConfirmation = trim($formInputs["passwordConfirmation"]);
                $valid = true;
                $errors = [];

                if (empty($lastname)) {
                    $valid = false;
                    $errors['emptyLastname'] = "Le \"Nom\" ne peut être vide.";
                }
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $lastname)) {
                    $valid = false;
                    $errors['invalidLastname'] = "Le \"Nom\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                if (empty($firstname)) {
                    $valid = false;
                    $errors['emptyFirstname'] = "Le \"Prénom\" ne peut être vide.";
                }
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $firstname)) {
                    $valid = false;
                    $errors['invalidFirstname'] = "Le \"Prénom\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                if (empty($username)) {
                    $valid = false;
                    $errors['emptyUsername'] = "Le \"Nom d'utilisateur\" ne peut être vide.";
                }
                elseif (!preg_match("/^[0-9A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $username)) {
                    $valid = false;
                    $errors['invalidUsername'] = "Le \"Nom d'utilisateur\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }
                else {
                    $checkUsername = UserRepository::checkUsername($username);

                    if($checkUsername) {
                        $valid = false;
                        $errors['usedUsername'] = "Ce \"Nom d'utilisateur\" n'est pas disponible.";
                    }
                }

                if (empty($email)) {
                    $valid = false;
                    $errors['emptyMail'] = "L'adresse \"E-mail\" ne peut être vide.";
                }
                elseif (!preg_match("/^[0-9a-z\-_.]+@[0-9a-z]+\.[a-z]{2,3}$/i", $email)) {
                    $valid = false;
                    $errors['invalidMail'] = "L'adresse \"E-mail\" n'est pas valide.";
                }
                else {
                    $checkEmail = UserRepository::checkEmail($email);

                    if($checkEmail) {
                        $valid = false;
                        $errors['usedMail'] = "Cette adresse \"E-mail\" est déjà utilisée.";
                    }
                }

                if(empty($password)) {
                    $valid = false;
                    $errors['emptyPass'] = "Le \"Mot de passe\" ne peut être vide.";
                }
                elseif(!preg_match("/^[0-9A-Za-z]{8,}$/", $password)) {
                    $valid = false;
                    $errors['invalidPass'] = "Le \"Mot de passe\" n'est pas valide. Il doit contenir 8 caractères alphanumériques au minimum et ne comporter aucun accent ni caractères spéciaux.";
                }
                elseif($password != $passwordConfirmation) {
                    $valid = false;
                    $errors['invalidPassConf'] = "La confirmation du \"Mot de passe\" ne correspond pas.";
                }
                else {
                    $hash = password_hash($password, PASSWORD_ARGON2I);
                }

                if ($valid) {
                    UserRepository::insertAdmin($username, $email, $hash, $lastname, $firstname);

                    $emailSender = "no-reply@blog.llemaitre.com";
                    $emailRecipient = $email;
                    $subject = "Création de votre compte Administrateur de Blog LLemaitre.com";

                    $message =  "<!DOCTYPE html><html lang=\"fr\"><body>"
                        . nl2br("<p>Bonjour " . htmlspecialchars($username) . ",
                        
                        Vous pouvez vous connecter via le <a style=\"color: #8540f5;\" href=\"http://127.0.0.1/blog_php/backoff/login\">lien</a> se trouvant dans le footer du blog en utilisant votre nom d'utilisateur ou bien votre addresse e-mail ainsi que votre mot de passe.

                        Voici votre mot de passe : <b>" . htmlentities($password) . "</b> 

                        Il est fortement recommandé de le modifier en vous rendant dans la partie <i>\"Gérer les utilisateurs\"</i> du back-office.</p>
                        
                        <p>Copyright 2022 <a style=\"color: #8540f5;\" href=\"https://llemaitre.com\">llemaitre.com</a>. Tous droits réservés</p>
                        </body></html>");

                    $sendMail = Mail::send($emailSender, $emailRecipient, $subject, $message);

                    try {
                        $sendMail;
                    } catch (TransportExceptionInterface $e) {
                        header("location:users?page=1&reply=error");
                    }

                    header("location:users?page=1&reply=ok");
                } else {
                    $this->render('../views/templates/admin',
                        'add_admin.html.twig',
                        ['lastname' => $lastname,
                        'firstname' => $firstname,
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'errors' => $errors,
                        'admin' => $_SESSION["admin"]]
                    );
                }
            }
        }

        public function displayEditUser($id) {
            $user = UserRepository::getUserById($id);

            if (!$user) {
                throw new NotFoundException();
            }

            $this->render('../views/templates/admin',
                'edit_user.html.twig',
                ['user' => $user,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function renderFormEditUser($id) {
            if (isset($_POST["editUser"])) {
                $args = [
                    'lastname' => FILTER_SANITIZE_STRING,
                    'firstname' => FILTER_SANITIZE_STRING,
                    'username' => FILTER_SANITIZE_STRING,
                    'email' => FILTER_SANITIZE_STRING,
                    'password' => FILTER_SANITIZE_STRING,
                    'passwordConfirmation' => FILTER_SANITIZE_STRING,
                    'isAdmin' => FILTER_SANITIZE_STRING
                ];

                $formInputs = filter_input_array(INPUT_POST, $args);

                $lastname = htmlspecialchars(trim($formInputs["lastname"]));
                $firstname = htmlspecialchars(trim($formInputs["firstname"]));
                $username = htmlspecialchars(trim($formInputs["username"]));
                $email = htmlspecialchars(strtolower(trim($formInputs["email"])));
                $password = trim($formInputs["password"]);
                $passwordConfirmation = trim($formInputs["passwordConfirmation"]);
                $formInputs["isAdmin"] ? $isAdmin = 1 : $isAdmin = 0;
                $valid = true;
                $errors = [];

                $user = UserRepository::getUserById($id);

                if (empty($lastname)) {
                    $valid = false;
                    $errors['emptyLastname'] = "Le \"Nom\" ne peut être vide.";
                }
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $lastname)) {
                    $valid = false;
                    $errors['invalidLastname'] = "Le \"Nom\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                if (empty($firstname)) {
                    $valid = false;
                    $errors['emptyFirstname'] = "Le \"Prénom\" ne peut être vide.";
                }
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $firstname)) {
                    $valid = false;
                    $errors['invalidFirstname'] = "Le \"Prénom\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                if (empty($username)) {
                    $valid = false;
                    $errors['emptyUsername'] = "Le \"Nom d'utilisateur\" ne peut être vide.";
                }
                elseif (!preg_match("/^[0-9A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $username)) {
                    $valid = false;
                    $errors['invalidUsername'] = "Le \"Nom d'utilisateur\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }
                else {
                    $checkUsername = UserRepository::checkUsername($username);

                    if($checkUsername && $checkUsername->getUsername() <> $user->getUsername()) {
                        $valid = false;
                        $errors['usedUsername'] = "Ce \"Nom d'utilisateur\" n'est pas disponible.";
                    }
                }

                if (empty($email)) {
                    $valid = false;
                    $errors['emptyMail'] = "L'adresse \"E-mail\" ne peut être vide.";
                }
                elseif (!preg_match("/^[0-9a-z\-_.]+@[0-9a-z]+\.[a-z]{2,3}$/i", $email)) {
                    $valid = false;
                    $errors['invalidMail'] = "L'adresse \"E-mail\" n'est pas valide.";
                }
                else {
                    $checkEmail = UserRepository::checkEmail($email);

                    if($checkEmail && $checkEmail->getEmail() <> $user->getEmail()) {
                        $valid = false;
                        $errors['usedMail'] = "Cette adresse \"E-mail\" est déjà utilisée.";
                    }
                }

                if(empty($password)) {
                    $hash = $user->getPassword();
                }
                elseif(!preg_match("/^[0-9A-Za-z]{8,}$/", $password)) {
                    $valid = false;
                    $errors['invalidPass'] = "Le \"Mot de passe\" n'est pas valide. Il doit contenir 8 caractères alphanumériques au minimum et ne comporter aucun accent ni caractères spéciaux.";
                }
                elseif($password != $passwordConfirmation) {
                    $valid = false;
                    $errors['invalidPassConf'] = "La confirmation du \"Mot de passe\" ne correspond pas.";
                }
                else {
                    $hash = password_hash($password, PASSWORD_ARGON2I);
                }

                if ($valid) {
                    UserRepository::setUser($username, $email, $hash, $lastname, $firstname, $isAdmin, $id);

                    if ($isAdmin && $hash && ($isAdmin <> $user->getIsAdmin() || $username <> $user->getUsername() || $email <> $user->getEmail() || $hash <> $user->getPassword())) {
                        $emailSender = "no-reply@blog.llemaitre.com";
                        $emailRecipient = $email;
                        $subject = "Activation de votre compte Administrateur de Blog LLemaitre.com";

                        if (!$password) {
                            $password = "Non modifié";
                        }

                        $message = "<!DOCTYPE html><html lang=\"fr\"><body>"
                            . nl2br("<p>Bonjour " . htmlspecialchars($username) . ",

                        Vous pouvez vous connecter via le <a style=\"color: #8540f5;\" href=\"http://127.0.0.1/blog_php/backoff/login\">lien</a> se trouvant dans le footer du blog en utilisant votre nom d'utilisateur ou bien votre addresse e-mail ainsi que votre mot de passe.

                        Voici votre mot de passe : <b>" . htmlentities($password) . "</b> 

                        Il est fortement recommandé de le modifier en vous rendant dans la partie <i>\"Gérer les utilisateurs\"</i> du back-office.</p>
                        
                        <p>Copyright 2022 <a style=\"color: #8540f5;\" href=\"https://llemaitre.com\">llemaitre.com</a>. Tous droits réservés</p>
                        </body></html>");

                        $sendMail = Mail::send($emailSender, $emailRecipient, $subject, $message);

                        try {
                            $sendMail;
                        } catch (TransportExceptionInterface $e) {
                            header("location:users?page=1&reply=error");
                        }

                        header("location:users?page=1&reply=ok");
                    } else {
                        header("location:users?page=1");
                    }
                } else {
                    $this->render('../views/templates/admin',
                        'edit_user.html.twig',
                        ['user' => $user,
                        'lastname' => $lastname,
                        'firstname' => $firstname,
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'isAdmin' => $isAdmin,
                        'errors' => $errors,
                        'admin' => $_SESSION["admin"]]
                    );
                }
            }
        }
    }
