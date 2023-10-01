<?php
/**
 * This file contains the Core/Error.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Core
 * File Name: Error.php
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
use App\Config;
use ErrorException;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Error and exception handler
 */
class Error
{

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     * @return void
     * @throws ErrorException
     */
    public static function errorHandler(int $level, string $message, string $file, int $line): void
    {
        if (error_reporting() !== 0) {
            throw new ErrorException($message, code: 0, severity: $level, filename: $file, line: $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function exceptionHandler(Exception $exception): void
    {
        $code = $exception->getCode();
        http_response_code($code);
        if (Config::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' in line " . $exception->getLine() . "</p>";
        } else {
            $log = dirname(path: __DIR__) . '/logs/' . date(format: 'Y-m-d') . '.txt';
            ini_set(option: 'error_log', value: $log);
            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            $message .= "--------------------------------------------------------------------------------------";
            error_log($message);
            \Core\View::render(template: "$code.twig");
        }
    }

    /**
     * @param string $title
     * @param string $message
     * @param string $code
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[NoReturn] public static function displayError(string $title, string $message, string $code): void
    {
        \Core\View::render(template: 'Error.twig', args: ['title' => $title, 'code' => $code, 'message' => $message]);
        exit(0);
    }
}
