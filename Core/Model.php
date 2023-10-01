<?php
/**
 * This file contains the Core/Model.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Core
 * File Name: Model.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 03/23/23
 * File Authored on: 03/23/2023
 * File Copyright: 3/2023
 */

/**
 *  Import needed classes
 */
use App\Config;
use PDO;
use PDOException;

/**
 *  Core Model class definition
 */
abstract class Model {

    /**
     * Get the PDO database connection
     *
     * @return PDO|null
     */
    protected static function getDB(): ?PDO
    {
        static $db = null;
        if ($db === null) {
            try {
                $dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8";
                $db = new PDO($dsn, username: Config::DB_USERNAME, password: Config::DB_PASSWORD);
                $db->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $db;
    }
}