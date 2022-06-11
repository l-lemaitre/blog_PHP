<?php
    namespace App\Classes\Models;

    class User
    {
        public	$id_user;
        public	$username;
        public	$email;
        public	$password;
        public	$lastname;
        public	$firstname;
        public	$is_admin;
        public	$registration_date;

        public static function getUsernameById($id)
        {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT `username` FROM `user` WHERE `id_user` = ?";
            $resultSet = $bdd->query($query, array($id));
            return $resultSet->fetch();
        }
    }
