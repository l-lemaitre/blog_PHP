<?php
    namespace App\Classes\Controllers;

    use App\Classes\Models\Article;
    use App\Classes\Models\User;

    class FrontController extends Controller {
        public function showHomePage() {
            $this->render('index.html.twig');
        }

        public function showBlogPosts($page = null) {
            $posts = Article::getArticlesPusblished();

            $this->render('blog_posts.html.twig',
                ['posts' => $posts]
            );
        }

        public function showPost($slug, $id) {
            $post = Article::getArticleById($id);

            $author = User::getUsernameById($post['user_id']);

            $this->render('blog_post.html.twig',
                ['post' => $post,
                'author' => $author]
            );
        }
    }
