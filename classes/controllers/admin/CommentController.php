<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Exceptions\NotFoundException;
    use App\Classes\Middlewares\CheckingLogin;
    use App\Classes\Models\CommentRepository;

    class CommentController extends Controller {
        protected array $middlewares = [ CheckingLogin::class ];

        public function displayComments() {
            $comments = CommentRepository::getComments();

            $this->render('../views/templates/admin',
                'comments.html.twig',
                ['comments' => $comments,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function displayComment($id) {
            $comment = CommentRepository::getCommentById($id);

            if (!$comment) {
                throw new NotFoundException();
            } else {
                $this->render('../views/templates/admin',
                    'comment.html.twig',
                    ['comment' => $comment,
                    'admin' => $_SESSION["admin"],
                    'admin_id' => $_SESSION["admin_id"]]
                );
            }
        }

        public function renderFormEditComment($id) {
            if (isset($_POST["editComment"])) {
                $args = [
                    'contents' => FILTER_SANITIZE_STRING,
                    'status' => FILTER_SANITIZE_STRING
                ];

                $formInputs = filter_input_array(INPUT_POST, $args);

                $comment = CommentRepository::getCommentById($id);

                $formInputs["contents"] ? $contents = strip_tags(trim($formInputs["contents"])) : $contents = $comment->getContents();
                $status = htmlspecialchars(trim($formInputs["status"]));
                $valid = true;
                $errors = [];

                if (empty($contents)) {
                    $valid = false;
                    $errors['emptyContents'] = "Le \"Contenu\" du commentaire ne peut être vide.";
                }

                if (empty($status)) {
                    $valid = false;
                    $errors['emptyStatus'] = "Le \"Statut\" ne peut être vide.";
                }

                if ($valid) {
                    CommentRepository::setComment($contents, $status, $id);

                    header("location:comments?page=1");
                } else {
                    $this->render('../views/templates/admin',
                        'comment.html.twig',
                        ['comment' => $comment,
                        'contents' => $contents,
                        'status' => $status,
                        'errors' => $errors,
                        'admin' => $_SESSION["admin"],
                        'admin_id' => $_SESSION["admin_id"]]
                    );
                }
            }
        }

        public function renderFormResetComment() {
            if (isset($_POST["resetComment"])) {
                $idComment = filter_input(INPUT_POST, 'resetComment', FILTER_VALIDATE_INT);

                $idComment = htmlspecialchars($idComment);

                CommentRepository::resetComment($idComment);

                header("location:comments?page=1");
            }
        }
    }
