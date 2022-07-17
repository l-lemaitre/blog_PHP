<?php
    namespace App\Classes\Models;

    use \PDO;
    use App\Classes\Entities\User;

    class UserRepository
    {
        public static function getUsernameById($id)
        {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT `username`
                      FROM `user`
                      WHERE `id_user` = ?";
            $resultSet = $bdd->query($query, array($id));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function getHashAdmin($mailUsername)
        {
            $bdd = DataBaseConnection::getConnect();

            // We request the hash for this user from our database
            $query = "SELECT `password`
                      FROM `user`
                      WHERE (`username` = ? OR `email` = ?)";
            $resultSet = $bdd->query($query, [$mailUsername, $mailUsername]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function checkAdminCredentials($mailUsername) {
            $bdd = DataBaseConnection::getConnect();

            // We make a request to know if the name of the admin exists because it is unique
            $query = "SELECT *
                      FROM `user`
                      WHERE (`username` = ? OR `email` = ?)";
            $resultSet = $bdd->query($query, [$mailUsername, $mailUsername]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function getUsers() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`";
            $resultSet = $bdd->query($query);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetchAll();
        }
    }
