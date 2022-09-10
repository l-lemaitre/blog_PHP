<?php
    namespace App\Classes\Controllers\Public;

    use App\Classes\Controllers\Controller;
    use App\Classes\Entities\Comment;
    use App\Classes\Entities\User;
    use App\Classes\Models\CommentRepository;
    use App\Classes\Models\PostRepository;
    use App\Classes\Models\UserRepository;

    class CommentFrontController extends Controller {
        public function renderFormAddComment($slug, $id) {
            if (isset($_POST["addComment"])) {
                $args = [
                    'username'   => FILTER_SANITIZE_STRING,
                    'email'   => FILTER_SANITIZE_STRING,
                    'contents' => FILTER_SANITIZE_STRING
                ];

                $formInputs = filter_input_array(INPUT_POST, $args);

                if (isset($_SESSION["admin_id"])) {
                    $username = htmlspecialchars(trim($_SESSION["admin"]));
                    $email = htmlspecialchars(strtolower(trim($_SESSION["admin_email"])));
                    $status = Comment::APPROVED;
                } else {
                    $username = htmlspecialchars(trim($formInputs["username"]));
                    $email = htmlspecialchars(strtolower(trim($formInputs["email"])));
                    $status = Comment::PENDING;
                }
                $contents = strip_tags(trim($formInputs["contents"]));
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
                            if (($user->getUsername() <> $username) || ($user->getEmail() <> $email)) {
                                $valid = false;
                                $errors['mailUsername'] = "Le \"Nom\" ou l'adresse \"E-mail\" sont déjà utilisées.";
                            }
                            elseif ($user->getDeleted() == User::DELETED) {
                                $valid = false;
                                $errors['userDeleted'] = "Ce compte a été suspendu.";
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
                    CommentRepository::insertComment($user_Id->getIdUser(), $id, $contents, $status);

                    header("location:/blog_php/post/".$slug."-".$id."?reply=ok#containerComment");
                } else {
                    $post = PostRepository::getPublishedPostById($id);

                    $comments = CommentRepository::getApprovedCommentsById($id);

                    $this->render('../views/templates/front',
                        'post.html.twig',
                        ['post' => $post,
                        'comments' => $comments,
                        'username' => $username,
                        'email' => $email,
                        'contents' => $contents,
                        'errors' => $errors]
                    );
                }
            }
        }
    }
