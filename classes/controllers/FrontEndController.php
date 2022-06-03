<?php
    namespace App\Classes\Controllers;

    class FrontEndController extends Controller {
        public function showHomePage() {
            $this->render('index.html.twig');
        }

        public function showBlogPosts() {
            $this->render('blog_posts.html.twig');
        }

        public function showPost($slug, $id, $page = null) {
            if($page): $page = ", vous êtes page ".$page; endif;

            echo "Je suis l'article ".$slug."-".$id.$page.".";
        }

        public function showError() {
            $this->render('error.html.twig');
        }
    }
