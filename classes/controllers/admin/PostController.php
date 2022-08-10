<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Controllers\ErrorAdminController;
    use App\Classes\Middlewares\checkingLogin;
    use App\Classes\Models\CategoryRepository;
    use App\Classes\Models\PostRepository;

    class PostController extends Controller {
        protected array $middlewares = [ checkingLogin::class ];

        public function displayPosts() {
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
            $post = PostRepository::getPostById($id);

            if (!$post) {
                $errorAdminController = new ErrorAdminController();
                $errorAdminController->displayError();
                exit;
            }

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
    }
