<?php
    namespace App\Classes\Controllers\Public;

    use App\Classes\Controllers\Controller;
    use App\Classes\Controllers\ErrorController;
    use App\Classes\Exceptions\NotFoundException;
    use App\Classes\Models\CommentRepository;
    use App\Classes\Models\PostRepository;

    class PostFrontController extends Controller {
        public function displayPosts($page = null) {
            $posts = PostRepository::getPusblishedPosts();

            $this->render('views/templates/front',
                'posts.html.twig',
                ['posts' => $posts]
            );
        }

        public function displayPost($slug, $id) {
            $post = PostRepository::getPublishedPostById($id);

            if (!$post) {
                throw new NotFoundException();
            }

            $comments = CommentRepository::getApprovedCommentsById($id);

            if (isset($_SESSION["admin_id"])) {
                $admin = $_SESSION["admin"];
            } else {
                $admin = false;
            }

            if (isset($_GET["reply"])) {
                $reply = htmlspecialchars($_GET["reply"]);
            } else {
                $reply = false;
            }

            $this->render('views/templates/front',
                'post.html.twig',
                ['post' => $post,
                'comments' => $comments,
                'admin' => $admin,
                'reply' => $reply]
            );
        }
    }
