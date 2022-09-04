<?php
    namespace App\Classes\Models;

    use \PDO;
    use App\Classes\Entities\User;

    class UserRepository {
        // FrontController
        public static function checkUserCredentials($username, $email) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`
                      WHERE `username` = ?
                      OR `email` = ?
                      AND `deleted` = ?";
            $resultSet = $bdd->query($query, [$username, $email, User::NOT_DELETED]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function insertCommentAuthor($username, $email) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "INSERT INTO `user` (`username`, `email`, `is_admin`, `registration_date`)
                      VALUES (?, ?, ?, ?)";
            $bdd->insert($query, [$username, $email, User::NOT_ADMIN, date("Y-m-d H:i:s")]);
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
                      WHERE (`username` = ? OR `email` = ?)
                      AND `deleted` = ?";
            $resultSet = $bdd->query($query, [$mailUsername, $mailUsername, User::NOT_DELETED]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function getUsers() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`
                      WHERE `deleted` = ?
                      ORDER BY `id_user` DESC";
            $resultSet = $bdd->query($query, [User::NOT_DELETED]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetchAll();
        }

        public static function getUserById($id) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`
                      WHERE `id_user` = ?
                      AND `deleted` = ?";
            $resultSet = $bdd->query($query, array($id, User::NOT_DELETED));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function checkUsername($username) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`
                      WHERE `username` = ?";
            $resultSet = $bdd->query($query, [$username]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function checkEmail($email) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `user`
                      WHERE `email` = ?";
            $resultSet = $bdd->query($query, [$email]);
            $resultSet->setFetchMode(PDO::FETCH_CLASS, User::class);
            return $resultSet->fetch();
        }

        public static function insertAdmin($username, $email, $hash, $lastname, $firstname) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "INSERT INTO `user` (`username`, `email`, `password`, `lastname`, `firstname`, `is_admin`, `registration_date`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $bdd->insert($query, array($username, $email, $hash, $lastname, $firstname, User::IS_ADMIN, date("Y-m-d H:i:s")));
        }

        public static function setUser($username, $email, $hash, $lastname, $firstname, $isAdmin, $id) {
            $bdd = DataBaseConnection::getConnect();

            $query = "UPDATE `user`
                      SET `username` = ?, `email` = ?, `password` = ?, `lastname` = ?, `firstname` = ?, `is_admin` = ?
                      WHERE `id_user` = ?";
            $bdd->insert($query, array($username, $email, $hash, $lastname, $firstname, $isAdmin, $id));
        }

        public static function resetUser($id) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            // We reset the columns containing the information of the post
            $query = "UPDATE `user`
                      SET `deleted` = ?, `unsubscribe_date` = ?
                      WHERE `id_user` = ?";
            $bdd->insert( $query, array(User::DELETED, date("Y-m-d H:i:s"), $id));
        }
    }
