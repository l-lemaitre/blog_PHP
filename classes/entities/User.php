<?php
    namespace App\Classes\Entities;

    class User {
        private	$id_user;
        private	$username;
        private	$email;
        private	$password;
        private	$lastname;
        private	$firstname;
        private	$is_admin;
        private	$deleted;
        private	$registration_date;
        private	$unsubscribe_date;

        const IS_ADMIN = 1;
        const NOT_ADMIN = 0;
        const DELETED = 1;
        const NOT_DELETED = 0;

        /**
         * @return mixed
         */
        public function getIdUser()
        {
            return $this->id_user;
        }

        /**
         * @param mixed $id_user
         */
        public function setIdUser($id_user): void
        {
            $this->id_user = $id_user;
        }

        /**
         * @return mixed
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * @param mixed $username
         */
        public function setUsername($username): void
        {
            $this->username = $username;
        }

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email): void
        {
            $this->email = $email;
        }

        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param mixed $password
         */
        public function setPassword($password): void
        {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getLastname()
        {
            return $this->lastname;
        }

        /**
         * @param mixed $lastname
         */
        public function setLastname($lastname): void
        {
            $this->lastname = $lastname;
        }

        /**
         * @return mixed
         */
        public function getFirstname()
        {
            return $this->firstname;
        }

        /**
         * @param mixed $firstname
         */
        public function setFirstname($firstname): void
        {
            $this->firstname = $firstname;
        }

        /**
         * @return mixed
         */
        public function getIsAdmin()
        {
            return $this->is_admin;
        }

        /**
         * @param mixed $is_admin
         */
        public function setIsAdmin($is_admin): void
        {
            $this->is_admin = $is_admin;
        }

        /**
         * @return mixed
         */
        public function getDeleted()
        {
            return $this->deleted;
        }

        /**
         * @param mixed $deleted
         */
        public function setDeleted($deleted): void
        {
            $this->deleted = $deleted;
        }

        /**
         * @return mixed
         */
        public function getRegistrationDate()
        {
            return $this->registration_date;
        }

        /**
         * @param mixed $registration_date
         */
        public function setRegistrationDate($registration_date): void
        {
            $this->registration_date = $registration_date;
        }

        /**
         * @return mixed
         */
        public function getUnsubscribeDate()
        {
            return $this->unsubscribe_date;
        }

        /**
         * @param mixed $unsubscribe_date
         */
        public function setUnsubscribeDate($unsubscribe_date): void
        {
            $this->unsubscribe_date = $unsubscribe_date;
        }
    }
