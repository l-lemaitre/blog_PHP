<?php
    namespace App\Classes\Models;

    use \PDO;
    use \PDOException;

    class DataBaseConnection {
        // We declare our attributes and we initialize them (except _connexion) by assigning them the following values
        private $_host = "localhost";
        private $_name = "blog_php";
        private $_user = "username";
        private $_pass = "password";
        private $_connexion;

        private static $obj;

        // We declare the constructor method to initialize the following attributes as soon as the $bdd object is created
        private final function __construct($_host = NULL, $_name = NULL, $_user = NULL, $_pass = NULL) {
            if($_host != NULL){
                $this->_host = $_host;
                $this->_name = $_name;
                $this->_user = $_user;
                $this->_pass = $_pass;
            }

            try {
                $this->_connexion = new PDO("mysql:host=" . $this->_host . ";dbname=" . $this->_name,
                    $this->_user, $this->_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES UTF8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            }

            catch(PDOException $error) {
                echo "Erreur : Impossible de se connecter à la base de données.";
            }
        }

        public static function getConnect() {
            if (!isset(self::$obj)) {
                self::$obj = new DataBaseConnection();
            }

            return self::$obj;
        }

        // We declare the query method which will serve as an accessor to display the database data
        public function query($sql, $data = array()) {
            $query = $this->_connexion->prepare($sql);
            $query->execute($data);
            return $query;
        }

        // We declare the insert method which will serve as a mutator to modify or insert data into the database
        public function insert($sql, $data = array()) {
            $query = $this->_connexion->prepare($sql);
            $query->execute($data);
        }
    }
