<?php
    namespace App\Classes\Controllers\Public;

    use App\Classes\Controllers\Controller;
    use App\Classes\Entities\Comment;
    use App\Classes\Models\CommentRepository;
    use App\Classes\Models\UserRepository;

    class CommentFrontController extends Controller {
        public function renderFormAddComment($slug, $id) {
            if(isset($_POST["addComment"])) {
                if (isset($_SESSION["admin_id"])) {
                    $username = htmlspecialchars(trim($_SESSION["admin"]));
                    $email = htmlspecialchars(strtolower(trim($_SESSION["admin_email"])));
                    $status = Comment::APPROVED;
                } else {
                    $username = htmlspecialchars(trim($_POST["username"]));
                    $email = htmlspecialchars(strtolower(trim($_POST["email"])));
                    $status = Comment::PENDING;
                }
                $contents = strip_tags(trim($_POST["contents"]));
                $valid = true;
                $errors = [];

                if (empty($username)) {
                    $valid = false;
                    $errors['emptyName'] = "Le \"Nom\" ne peut être vide.";
                }
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $username)) {
                    $valid = false;
                    $errors['invalidName'] = "Le \"Nom\" doit contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                if (empty($email)) {
                    $valid = false;
                    $errors['emptyMail'] = "L'adresse \"E-mail\" ne peut être vide.";
                }
                elseif (!preg_match("/^[0-9a-z\-_.]+@[0-9a-z]+\.[a-z]{2,3}$/i", $email)) {
                    $valid = false;
                    $errors['invalidMail'] = "L'adresse \"E-mail\" n'est pas valide.";
                }

                if ($valid) {
                    $user = UserRepository::checkUserCredentials($username, $email);

                    if (!isset($_SESSION["admin_id"])) {
                        if ($user) {
                            if (($user->username <> $username) || ($user->email <> $email)) {
                                $valid = false;
                                $errors['mailUsername'] = "Le \"Nom\" ou l'adresse \"E-mail\" sont déjà utilisées.";
                            }
                        } else {
                            UserRepository::insertCommentAuthor($username, $email);
                        }
                    }

                    $user_Id = UserRepository::getUserByEmail($email);
                }

                if (empty($contents)) {
                    $valid = false;
                    $errors['emptyContents'] = "Le Contenu du \"Commentaire\" ne peut être vide.";
                }

                if ($valid) {
                    CommentRepository::insertComment($user_Id->id_user, $id, $contents, $status);

                    header("location:/blog_php/post/".$slug."-".$id."?reply=ok#containerComment");
                    exit;
                }

                $this->render('views/templates/front',
                    'post.html.twig',
                    ['username' => $username,
                    'email' => $email,
                    'contents' => $contents,
                    'errors' => $errors]
                );
            }
        }
    }
