<?php
/**
 * This file contains the /Controllers/Home.php class for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Controllers
 *  File Name: Home.php
 *  Author: Troy L. Marker
 *  Language: PHP 8.2
 *  File Authored on: 04/05/2023
 *  File Copyright: 04/2023
 */
namespace App\Controllers;

/**
 * Import required classes
 */
use Core\Controller;
use Core\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * This file contains the Home Controller
 */
class Home extends Controller {

    /**
     * index Action
     *
     * This action will display the index page
     *
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function indexAction(): void {
        View::render(template: "Home/index.twig");
    }
}