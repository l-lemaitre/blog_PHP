<?php
    namespace App\Classes\Controllers;

    use App\Classes\Models\CategoryRepository;
    use App\Classes\Models\DataBaseConnection;
    use App\Classes\Models\PostRepository;
    use App\Classes\Models\UserRepository;

    class AdminController extends Controller {
        public function displayLoginBackOffice() {
            // If an administrator is logged in then we no longer return to this page
            if(isset($_SESSION["admin_id"])) {
                header("location:/blog_php/backoff/dashboard");
                exit;
            }

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

        public function renderFormLoginBackOffice() {
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

                $hash = UserRepository::getHashAdmin($mailUsername);

                if($hash) {
                    // We check if the password used corresponds to this hash using password_verify
                    $correctPassword = password_verify($password, $hash->password);
                }
                else {
                    $correctPassword = false;
                }

                if($correctPassword) {
                    $user = UserRepository::checkAdminCredentials($mailUsername);

                    // If there is a result then we load the admin session using the session variables
                    if($valid) {
                        $_SESSION["admin_id"] = htmlspecialchars($user->id_user);
                        $_SESSION["admin"] = htmlspecialchars($user->username);
                        $_SESSION["admin_email"] = htmlspecialchars($user->email);

                        //die($_SESSION["admin"]);

                        // Send to the back office homepage
                        header("location:/blog_php/backoff/dashboard");
                    }
                }
                // Or if we have no result after the verification with password_verify() it means that there is no user corresponding to the couple username or e-mail + password
                else {
                    $loginError = true;
                }
            }

            $this->render('views/templates/admin',
                'login.html.twig',
                ['mailUsername' => $mailUsername,
                'password' => $password,
                'emptyIdentifier' => $emptyIdentifier,
                'emptyPass' => $emptyPass,
                'loginError' => $loginError,
                'admin_id' => $_SESSION["admin_id"],
                'admin' => $_SESSION["admin"],
                'admin_email' => $_SESSION["admin_email"]]
            );
        }

        public function displayDashboard() {
            // If no administrator is logged in then we do not go to this page
            if(!isset($_SESSION["admin_id"])) {
                // The user is sent to the login page
                header("location:/blog_php/backoff/login");
                exit;
            }

            if(isset($_SESSION["admin"])) {
                $this->render('views/templates/admin',
                    'dashboard.html.twig',
                    ['admin' => $_SESSION["admin"]]
                );
            } else {
                $this->render('views/templates/admin',
                    'dashboard.html.twig'
                );
            }
        }

        public function displayPosts() {
            if(!isset($_SESSION["admin_id"])) {
                header("location:/blog_php/backoff/login");
                exit;
            }

            $posts = PostRepository::getPosts();

            $this->render('views/templates/admin',
                'posts.html.twig',
                ['posts' => $posts]
            );
        }

        public function displayEditPost($id) {
            if(!isset($_SESSION["admin_id"])) {
                header("location:/blog_php/backoff/login");
                exit;
            }

            $post = PostRepository::getPostById($id);

            $categories = CategoryRepository::getCategories();

            $users = userRepository::getUsers();

            $this->render('views/templates/admin',
                'post.html.twig',
                ['post' => $post,
                'categories' => $categories,
                'users' => $users]
            );
        }

        public function renderFormEditPost($id) {
            if(isset($_POST["editPost"])) {
                $category = htmlspecialchars(trim($_POST["category"]));
                $author = strip_tags(trim($_POST["author"]));
                $title = strip_tags(trim($_POST["title"]));
                $chapo = strip_tags(trim($_POST["chapo"]));
                $contents = strip_tags(trim($_POST["contents"]));
                $slug = strip_tags(trim($_POST["slug"]));
                $status = htmlspecialchars(trim($_POST["status"]));
                $valid = true;
                $emptyCategory = false;
                $invalidCategory = false;
                $emptyAuthor = false;
                $invalidAuthor = false;
                $emptyTitle = false;
                $emptyChapo = false;
                $emptyContents = false;
                $emptySlug = false;
                $emptyStatus = false;

                if (empty($category)) {
                    $valid = false;
                    $emptyCategory = true;
                } elseif (!preg_match("/^[0-9]+$/", $category)) {
                    $valid = false;
                    $invalidCategory = true;
                }

                if (empty($author)) {
                    $valid = false;
                    $emptyAuthor = true;
                } elseif (!preg_match("/^[0-9]+$/", $author)) {
                    $valid = false;
                    $invalidAuthor = true;
                }

                if (empty($title)) {
                    $valid = false;
                    $emptyTitle = true;
                }

                if (empty($chapo)) {
                    $valid = false;
                    $emptyChapo = true;
                }

                if (empty($contents)) {
                    $valid = false;
                    $emptyContents = true;
                }

                if (empty($slug)) {
                    $valid = false;
                    $emptySlug = true;
                }

                if (empty($status)) {
                    $valid = false;
                    $emptyStatus = true;
                }

                if ($valid) {
                    PostRepository::setPost($category, $author, $title, $chapo, $contents, $slug, $status, $id);

                    header("location:/blog_php/backoff/posts?page=1");
                    exit;
                }

                $post = PostRepository::getPostById($id);

                $categories = CategoryRepository::getCategories();

                $users = userRepository::getUsers();

                $this->render('views/templates/admin',
                    'post.html.twig',
                    ['post' => $post,
                    'categories' => $categories,
                    'users' => $users,
                    'category' => $category,
                    'author' => $author,
                    'title' => $title,
                    'chapo' => $chapo,
                    'contents' => $contents,
                    'slug' => $slug,
                    'status' => $status,
                    'emptyCategory' => $emptyCategory,
                    'invalidCategory' => $invalidCategory,
                    'emptyAuthor' => $emptyAuthor,
                    'invalidAuthor' => $invalidAuthor,
                    'emptyTitle' => $emptyTitle,
                    'emptyChapo' => $emptyChapo,
                    'emptyContents' => $emptyContents,
                    'emptySlug' => $emptySlug,
                    'emptyStatus' => $emptyStatus]
                );
            }
        }

        public function renderFormResetPost($id) {
            if(isset($_POST["resetPost"])) {
                PostRepository::resetPost($id);

                header("location:/blog_php/backoff/posts?page=1");
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
