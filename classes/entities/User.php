<?php
    namespace App\Classes\Entities;

    class User {
        public	$id_user;
        public	$username;
        public	$email;
        public	$password;
        public	$lastname;
        public	$firstname;
        public	$is_admin;
        public	$registration_date;

        const IS_ADMIN = 1;
        const NOT_ADMIN = 0;
        const DELETED = 1;
        const NOT_DELETED = 0;
    }
