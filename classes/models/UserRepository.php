<?php
    namespace App\Classes\Models;

    use \PDO;
    use App\Classes\Entities\User;

    class UserRepository {
        // FrontController
        public static function checkUserCredentials($username, $email) {
            $bdd = DataBaseConnection::getConnect();

            // We make a request to know if the name of the admin exists because it is unique
            $query = "SELECT *
                      FROM `user`
                      WHERE `username` = ?
                      OR `email` = ?";
            $resultSet = $bdd->query($query, [$username, $email]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        // À SUPPRIMER
        public static function setCommentAuthor($username, $email) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "UPDATE `user`
                      SET `username` = ?, `email` = ?
                      WHERE `username` = \"".$username."\"
                      OR `email` = \"".$email."\"";
            $bdd->insert($query, array($username, $email));
        }

        public static function insertCommentAuthor($username, $email) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "INSERT INTO `user` (`username`, `email`, `is_admin`, `registration_date`)
                      VALUES (?, ?, ?, ?)";
            $bdd->insert($query, array($username, $email, User::NOT_ADMIN, date("Y-m-d H:i:s")));
        }

        public static function getUserByEmail($email) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT `id_user`
                      FROM `user`
                      WHERE `email` = ?";
            $resultSet = $bdd->query($query, [$email]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        // AdminController
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

        // À SUPPRIMER
        public static function getUsers() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`";
            $resultSet = $bdd->query($query);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetchAll();
        }
    }
