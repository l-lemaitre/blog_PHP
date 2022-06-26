<?php
    namespace App\Classes\Entities;

    class Article
    {
        public	$id_article;
        public	$category_id;
        public	$user_id;
        public	$title;
        public	$chapo;
        public	$contents;
        public	$slug;
        public	$status;
        public	$date_add;
        public	$date_updated;

        const PUBLISHED = "Published";
    }
