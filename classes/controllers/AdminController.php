<?php
    namespace App\Classes\Controllers;

    use App\Classes\Models\CommentRepository;
    use App\Classes\Models\CategoryRepository;
    use App\Classes\Models\PostRepository;
    use App\Classes\Models\UserRepository;
    use App\Classes\Models\DataBaseConnection;

    class AdminController extends Controller {
        public function ifAdminIsLogin() {
            // If an administrator is logged in then we no longer return to this page
            if(isset($_SESSION["admin_id"])) {
                header("location:/blog_php/backoff/dashboard");
                exit;
            }
        }

        public function ifAdminIsNotLogin() {
            // If no administrator is logged in then we do not go to this page
            if(!isset($_SESSION["admin_id"])) {
                // The user is sent to the login page
                header("location:/blog_php/backoff/login");
                exit;
            }
        }

        public function displayLoginBackOffice() {
            $this->ifAdminIsLogin();

            $this->render('views/templates/admin',
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

            $this->render('views/templates/admin',
                'login.html.twig',
                ['mailUsername' => $mailUsername,
                'password' => $password,
                'errors' => $errors]
            );
        }

        public function displayDashboard() {
            $this->ifAdminIsNotLogin();

            $this->render('views/templates/admin',
                'dashboard.html.twig',
                ['admin' => $_SESSION["admin"]]
            );
        }

        public function displayPosts() {
            $this->ifAdminIsNotLogin();

            $posts = PostRepository::getPosts();

            $this->render('views/templates/admin',
                'posts_bo.html.twig',
                ['posts' => $posts,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function renderFormResetPost($id) {
            if(isset($_POST["resetPost"])) {
                PostRepository::resetPost($id);

                header("location:/blog_php/backoff/posts?page=1");
                exit;
            }
        }

        public function displayAddPost() {
            $this->ifAdminIsNotLogin();

            $categories = CategoryRepository::getCategories();

            $this->render('views/templates/admin',
                'add_post.html.twig',
                ['categories' => $categories,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function renderFormAddPost() {
            if(isset($_POST["addPost"])) {
                $category = htmlspecialchars(trim($_POST["category"]));
                $title = strip_tags(trim($_POST["title"]));
                $chapo = strip_tags(trim($_POST["chapo"]));
                $contents = strip_tags(trim($_POST["contents"]));
                $slug = strip_tags(trim($_POST["slug"]));
                if (isset($_POST["published"])) {
                    $published = 1;
                } else {
                    $published = 0;
                }
                $valid = true;
                $errors = [];

                if (empty($category)) {
                    $valid = false;
                    $errors['emptyCategory'] = "La \"Catégorie\" ne peut être vide.";
                } elseif (!preg_match("/^[0-9]+$/", $category)) {
                    $valid = false;
                    $errors['invalidCategory'] = "La \"Catégorie\" n'est pas valide.";
                }

                if (empty($title)) {
                    $valid = false;
                    $errors['emptyTitle'] = "Le \"Titre\" ne peut être vide.";
                }

                if (empty($chapo)) {
                    $valid = false;
                    $errors['emptyChapo'] = "Le \"Chapô\" ne peut être vide.";
                }

                if (empty($contents)) {
                    $valid = false;
                    $errors['emptyContents'] = "Le \"Contenu\" de l'article ne peut être vide.";
                }

                if (empty($slug)) {
                    $valid = false;
                    $errors['emptySlug'] = "Le \"Permalien\" ne peut être vide.";
                }

                if ($valid) {
                    PostRepository::insertPost($category, $_SESSION["admin_id"], $title, $chapo, $contents, $slug, $published);

                    header("location:/blog_php/backoff/posts?page=1");
                    exit;
                }

                $categories = CategoryRepository::getCategories();

                $this->render('views/templates/admin',
                    'add_post.html.twig',
                    ['categories' => $categories,
                    'category' => $category,
                    'title' => $title,
                    'chapo' => $chapo,
                    'contents' => $contents,
                    'slug' => $slug,
                    'errors' => $errors,
                    'admin' => $_SESSION["admin"]]
                );
            }
        }

        public function displayEditPost($id) {
            $this->ifAdminIsNotLogin();

            $post = PostRepository::getPostById($id);

            $categories = CategoryRepository::getCategories();

            $this->render('views/templates/admin',
                'edit_post.html.twig',
                ['post' => $post,
                'categories' => $categories,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function renderFormEditPost($id) {
            if(isset($_POST["editPost"])) {
                $category = htmlspecialchars(trim($_POST["category"]));
                $title = strip_tags(trim($_POST["title"]));
                $chapo = strip_tags(trim($_POST["chapo"]));
                $contents = strip_tags(trim($_POST["contents"]));
                $slug = strip_tags(trim($_POST["slug"]));
                if (isset($_POST["published"])) {
                    $published = 1;
                } else {
                    $published = 0;
                }
                $valid = true;
                $errors = [];

                if (empty($category)) {
                    $valid = false;
                    $errors['emptyCategory'] = "La \"Catégorie\" ne peut être vide.";
                } elseif (!preg_match("/^[0-9]+$/", $category)) {
                    $valid = false;
                    $errors['invalidCategory'] = "La \"Catégorie\" n'est pas valide.";
                }

                if (empty($title)) {
                    $valid = false;
                    $errors['emptyTitle'] = "Le \"Titre\" ne peut être vide.";
                }

                if (empty($chapo)) {
                    $valid = false;
                    $errors['emptyChapo'] = "Le \"Chapô\" ne peut être vide.";
                }

                if (empty($contents)) {
                    $valid = false;
                    $errors['emptyContents'] = "Le \"Contenu\" de l'article ne peut être vide.";
                }

                if (empty($slug)) {
                    $valid = false;
                    $errors['emptySlug'] = "Le \"Permalien\" ne peut être vide.";
                }

                if ($valid) {
                    PostRepository::setPost($category, $title, $chapo, $contents, $slug, $published, $id);

                    header("location:/blog_php/backoff/posts?page=1");
                    exit;
                }

                $post = PostRepository::getPostById($id);

                $categories = CategoryRepository::getCategories();

                $this->render('views/templates/admin',
                    'edit_post.html.twig',
                    ['post' => $post,
                    'categories' => $categories,
                    'category' => $category,
                    'title' => $title,
                    'chapo' => $chapo,
                    'contents' => $contents,
                    'slug' => $slug,
                    'errors' => $errors,
                    'admin' => $_SESSION["admin"]]
                );
            }
        }

        public function displayComments() {
            $this->ifAdminIsNotLogin();

            $comments = CommentRepository::getComments();

            $this->render('views/templates/admin',
                'comments.html.twig',
                ['comments' => $comments,
                'admin' => $_SESSION["admin"]]
            );
        }

        public function displayComment($id) {
            $this->ifAdminIsNotLogin();

            $comment = CommentRepository::getCommentById($id);

            $this->render('views/templates/admin',
                'comment.html.twig',
                ['comment' => $comment,
                'admin' => $_SESSION["admin"]]
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
                    'admin' => $_SESSION["admin"]]
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

        public function logoutBackOffice() {
            // Destroy all data associated with the current session
            session_destroy();

            // Send to homepage
            header("location:/blog_php/backoff/login");
        }
    }
