<?php
    namespace App\Classes\Controllers;

    use App\Classes\Models\DatabaseConnection;

    class FrontController extends Controller {
        public function showHomePage() {
            $this->render('index.html.twig');
        }

        public function showBlogPosts($page = null) {
            $bdd = new DatabaseConnection;

            $query = "SELECT * FROM `article` WHERE `status` = ?";
            $resultSet = $bdd->query($query, array("Publié"));
            $posts = $resultSet->fetchAll();

            $this->render('blog_posts.html.twig',
                ['posts' => $posts]
            );
        }

        public function showPost($slug, $id) {
            $bdd = new DatabaseConnection;

            $query = "SELECT * FROM `article` WHERE `id_article` = ?";
            $resultSet = $bdd->query($query, array($id));
            $post = $resultSet->fetch();

            $query = "SELECT `username` FROM `user` WHERE `id_user` = ?";
            $resultSet = $bdd->query($query, array($post['user_id']));
            $author = $resultSet->fetch();

            $this->render('blog_post.html.twig',
                ['post' => $post,
                'author' => $author]
            );
        }
    }
