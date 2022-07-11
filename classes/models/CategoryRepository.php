<?php
    namespace App\Classes\Models;

    use \PDO;
    use App\Classes\Entities\Category;

    class CategoryRepository
    {
        public static function getCategories() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `category`";
            $resultSet = $bdd->query($query);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Category::class);
            return $resultSet->fetchAll();
        }
    }
