<?php
/**
 * This file contains the Core/View.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Core
 * File Name: View.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 03/23/23
 * File Authored on: 03/23/2023
 * File Copyright: 3/2023
 */

/**
 * Import needed classes
 */
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Core View class definition
 */
class View
{

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function render(string $template, array $args = []): void {
        static $twig = null;
        if ($twig === null) {
            $loader = new FilesystemLoader(paths: '../App/Views');
            $twig = new Environment($loader);
        }
        echo $twig->render($template, $args);
    }
}