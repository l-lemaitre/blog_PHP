<?php
    namespace App\Classes\Entities;

    class Comment {
        public	$id_comment;
        public	$user_id;
        public	$post_id;
        public	$contents;
        public	$status;
        public	$date_add;
        public	$date_updated;

        const APPROVED = "approved";
        const PENDING = "pending";
        const REJECTED = "rejected";
        const DELETED = 1;
        const NOT_DELETED = 0;
    }
