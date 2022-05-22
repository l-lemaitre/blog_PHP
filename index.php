<?php
    require "vendor/autoload.php";

    use App\Controllers\FrontEndHomeController;

    try {
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'listPosts') {
                listPosts();
            }
            elseif ($_GET['action'] == 'post') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    post();
                }
            }
            elseif ($_GET['action'] == 'addComment') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                        addComment($_GET['id'], $_POST['author'], $_POST['comment']);
                    }
                }
            }
        }
        else {
            $FrontEndHomeController = new FrontEndHomeController();
            $FrontEndHomeController->renderView();
        }
    }
    catch(Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
