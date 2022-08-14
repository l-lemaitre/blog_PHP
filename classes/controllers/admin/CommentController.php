<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Controllers\ErrorAdminController;
    use App\Classes\Middlewares\checkingLogin;
    use App\Classes\Models\CommentRepository;

    class CommentController extends Controller {
        protected array $middlewares = [ checkingLogin::class ];

        public function displayComments() {
            $comments = CommentRepository::getComments();

            $this->render('views/templates/admin',
                'comments.html.twig',
                ['comments' => $comments,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function displayComment($id) {
            $comment = CommentRepository::getCommentById($id);

            if (!$comment) {
                $errorAdminController = new ErrorAdminController();
                $errorAdminController->displayError();
                exit;
            }

            $this->render('views/templates/admin',
                'comment.html.twig',
                ['comment' => $comment,
                'admin' => $_SESSION["admin"],
                'admin_id' => $_SESSION["admin_id"]]
            );
        }

        public function renderFormEditComment($id) {
            if(isset($_POST["editComment"])) {
                $contents = strip_tags(trim($_POST["contents"]));
                $status = htmlspecialchars(trim($_POST["status"]));
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

                    header("location:/blog_php/backoff/comments?page=1");
                    exit;
                }

                $comment = CommentRepository::getCommentById($id);

                $this->render('views/templates/admin',
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

        public function renderFormResetComment($id) {
            if(isset($_POST["resetComment"])) {
                CommentRepository::resetComment($id);

                header("location:/blog_php/backoff/comments?page=1");
                exit;
            }
        }
    }
