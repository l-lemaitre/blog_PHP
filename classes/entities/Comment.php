<?php
    namespace App\Classes\Entities;

    class Comment {
        private	$id_comment;
        private	$user_id;
        private	$post_id;
        private	$contents;
        private	$status;
        private	$deleted;
        private	$date_add;
        private	$date_updated;

        const APPROVED = "approved";
        const PENDING = "pending";
        const REJECTED = "rejected";
        const DELETED = 1;
        const NOT_DELETED = 0;

        /**
         * @return mixed
         */
        public function getIdComment()
        {
            return $this->id_comment;
        }

        /**
         * @param mixed $id_comment
         */
        public function setIdComment($id_comment): void
        {
            $this->id_comment = $id_comment;
        }

        /**
         * @return mixed
         */
        public function getUserId()
        {
            return $this->user_id;
        }

        /**
         * @param mixed $user_id
         */
        public function setUserId($user_id): void
        {
            $this->user_id = $user_id;
        }

        /**
         * @return mixed
         */
        public function getPostId()
        {
            return $this->post_id;
        }

        /**
         * @param mixed $post_id
         */
        public function setPostId($post_id): void
        {
            $this->post_id = $post_id;
        }

        /**
         * @return mixed
         */
        public function getContents()
        {
            return $this->contents;
        }

        /**
         * @param mixed $contents
         */
        public function setContents($contents): void
        {
            $this->contents = $contents;
        }

        /**
         * @return mixed
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * @param mixed $status
         */
        public function setStatus($status): void
        {
            $this->status = $status;
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
        public function getDateAdd()
        {
            return $this->date_add;
        }

        /**
         * @param mixed $date_add
         */
        public function setDateAdd($date_add): void
        {
            $this->date_add = $date_add;
        }

        /**
         * @return mixed
         */
        public function getDateUpdated()
        {
            return $this->date_updated;
        }

        /**
         * @param mixed $date_updated
         */
        public function setDateUpdated($date_updated): void
        {
            $this->date_updated = $date_updated;
        }
    }
