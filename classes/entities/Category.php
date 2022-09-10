<?php
    namespace App\Classes\Entities;

    class Category {
        private	$id_category;
        private	$title;
        private	$slug;
        private	$date_add;

        /**
         * @return mixed
         */
        public function getIdCategory()
        {
            return $this->id_category;
        }

        /**
         * @param mixed $id_category
         */
        public function setIdCategory($id_category): void
        {
            $this->id_category = $id_category;
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
    }
