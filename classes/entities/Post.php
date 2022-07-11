<?php
    namespace App\Classes\Entities;

    class Post
    {
        public	$id_post;
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
