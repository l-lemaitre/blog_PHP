<?php
    namespace App\Classes\Models;

    define("PUBLISHED", "Published");

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

        public static function getArticlesPusblished() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT * FROM `article` WHERE `status` = ?";
            $resultSet = $bdd->query($query, array(PUBLISHED));
            return $resultSet->fetchAll();
        }

        public static function getArticleById($id)
        {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT * FROM `article` WHERE `id_article` = ?";
            $resultSet = $bdd->query($query, array($id));
            return $resultSet->fetch();
        }
    }
