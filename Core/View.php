<?php
/**
 * This file contains the C:/TLME/Projects/TLME-Framework/Core/View.php class for the TLME-Framework
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-19
 */

namespace Core;

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
 *Core View class definition
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
            $loader = new FilesystemLoader('../App/Views');
            $twig = new Environment($loader);
        }
        echo $twig->render($template, $args);
    }
}