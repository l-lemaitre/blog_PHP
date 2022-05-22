<?php
    namespace App\Controllers;

    use App\Classes\Controller\FrontEndController;
    use App\Classes\HomeController;

    class FrontEndHomeController extends FrontEndController
    {
        public function __construct()
        {
            parent::__construct();
        }

        function renderView()
        {
            $homeController = new HomeController();
            $homeController->displayHomePage();
        }
    }
