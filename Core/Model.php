<?php
/**
 * This file contains the C:/TLME/Projects/TLME-Framework/Core/Model.php class for the TLME-Framework
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
use App\Config;
use PDO;
use PDOException;

/**
 * Abstract Core Model class definition
 */
abstract class Model {

    /**
     * Get the PDO database connection
     *
     * @return PDO|null
     */
    protected static function getDB(): ?PDO {
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