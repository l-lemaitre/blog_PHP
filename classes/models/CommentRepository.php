<?php
    namespace App\Classes\Models;

    use App\Classes\Entities\Post;
    use \PDO;
    use App\Classes\Entities\Comment;

    class CommentRepository {
        // FrontController
        public static function getApprovedCommentsById($id) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT c.*, u.`username` AS user_username
                      FROM `comment` c
                      LEFT JOIN `user` u ON (u.`id_user` = c.`user_id`)
                      WHERE c.`post_id` = ?
                      AND c.`status` = ?";
            $resultSet = $bdd->query($query, array($id, Comment::APPROVED));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Comment::class);
            return $resultSet->fetchAll();
        }

        public static function insertComment($user_Id, $post_id, $contents) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "INSERT INTO `comment` (`user_id`, `post_id`, `contents`, `status`, `date_add`, `date_updated`)
                      VALUES (?, ?, ?, ?, ?, ?)";
            $bdd->insert($query, array($user_Id, $post_id, $contents, Comment::PENDING, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        }

        // AdminController
        public static function getComments() {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT c.*, u.`username` AS user_username, p.`title` AS post_title
                      FROM `comment` c
                      LEFT JOIN `user` u ON (u.`id_user` = c.`user_id`)
                      LEFT JOIN `post` p ON (p.`id_post` = c.`post_id`)
                      WHERE c.`deleted` = ?
                      ORDER BY c.`id_comment` DESC";
            $resultSet = $bdd->query($query, array(Comment::NOT_DELETED));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Comment::class);
            return $resultSet->fetchAll();
        }

        public static function getCommentById($id) {
            $bdd = DataBaseConnection::getConnect();

            $query = "SELECT *
                      FROM `comment`
                      WHERE `id_comment` = ?";
            $resultSet = $bdd->query($query, array($id));
            $resultSet->setFetchMode(PDO::FETCH_CLASS, Comment::class);
            return $resultSet->fetch();
        }

        public static function setComment($contents, $status, $id) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "UPDATE `comment`
                      SET `contents` = ?, `status` = ?, `date_updated` = ?
                      WHERE `id_comment` = ?";
            $bdd->insert($query, array($contents, $status, date("Y-m-d H:i:s"), $id));
        }

        public static function resetComment($id) {
            date_default_timezone_set("Europe/Paris");

            $bdd = DataBaseConnection::getConnect();

            $query = "UPDATE `comment`
                      SET `deleted` = ?, `date_updated` = ?
                      WHERE `id_comment` = ?";
            $bdd->insert($query, array(Comment::DELETED, date("Y-m-d H:i:s"), $id));
        }
    }
