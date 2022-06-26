<?php
    namespace App\Classes\Models;

    use App\Classes\Entities\Article;
    use \PDO;

    class ArticleRepository
    {
        public static function getArticlesPusblished() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT * FROM `article` WHERE `status` = ?";
            $resultSet = $bdd->query($query, array(Article::PUBLISHED));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Article::class);
            return $resultSet->fetchAll();
        }

        public static function getArticleById($id)
        {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT * FROM `article` WHERE `id_article` = ?";
            $resultSet = $bdd->query($query, array($id));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Article::class);
            return $resultSet->fetch();
        }
    }
