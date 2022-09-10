<?php
    namespace App\Classes\Entities;

    class Post {
        private	$id_post;
        private	$category_id;
        private	$user_id;
        private	$title;
        private	$chapo;
        private	$contents;
        private	$slug;
        private	$published;
        private	$deleted;
        private	$date_add;
        private	$date_updated;

        const PUBLISHED = 1;
        const NOT_PUBLISHED = 0;
        const DELETED = 1;
        const NOT_DELETED = 0;

        /**
         * @return mixed
         */
        public function getIdPost()
        {
            return $this->id_post;
        }

        /**
         * @param mixed $id_post
         */
        public function setIdPost($id_post): void
        {
            $this->id_post = $id_post;
        }

        /**
         * @return mixed
         */
        public function getCategoryId()
        {
            return $this->category_id;
        }

        /**
         * @param mixed $category_id
         */
        public function setCategoryId($category_id): void
        {
            $this->category_id = $category_id;
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
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @param mixed $title
         */
        public function setTitle($title): void
        {
            $this->title = $title;
        }

        /**
         * @return mixed
         */
        public function getChapo()
        {
            return $this->chapo;
        }

        /**
         * @param mixed $chapo
         */
        public function setChapo($chapo): void
        {
            $this->chapo = $chapo;
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
        public function getSlug()
        {
            return $this->slug;
        }

        /**
         * @param mixed $slug
         */
        public function setSlug($slug): void
        {
            $this->slug = $slug;
        }

        /**
         * @return mixed
         */
        public function getPublished()
        {
            return $this->published;
        }

        /**
         * @param mixed $published
         */
        public function setPublished($published): void
        {
            $this->published = $published;
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
