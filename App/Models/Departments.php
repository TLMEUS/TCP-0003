<?php
/**
 * This file contains the Models/Departments.php file for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Models
 *  File Name: Departments.php
 *  Author: Troy L. Marker
 *  Language: PHP 8.2
 *  File Authored on: 07/20/2023
 *  File Copyright: 07/2023
 */
namespace App\Models;

/**
 * Import required classes
 */

use Core\Error;
use Core\Model;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Category Model
 *
 * This model provided access to the department table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Departments extends Model {

    /**
     * create Method
     *
     * This method creates a department in the database
     *
     * @param array $data The department data to insert
     * @return bool Value indicating success of the insert
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function create(array $data):bool {
        try {
            $department = $data['department'];
            $db = static::getDB();
            $stmt = $db->prepare(query: "INSERT INTO tbl_departments (col_name) VALUES (:department)");
            $stmt->bindValue(param: ":department", value: $department);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * readAll Method
     *
     * This method returns a list of all departments as an array
     *
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function readAll(): array {
        try {
            $db = static::getDB();
            $stmt = $db->query(query: "SELECT * FROM tbl_departments ORDER BY col_id");
            return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * readOne Method
     *
     * This method return a single department based on ID number
     *
     * @param string $col_id
     * @return array|false
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public  static function readSingle(string $col_id): false|array {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_departments WHERE col_id = :col_id");
            $stmt->bindValue(param: ":col_id", value: $col_id);
            $stmt->execute();
            return $stmt->fetch(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * update Method
     *
     * This method will update a department in the database
     *
     * @param array $data Array containing the new department data
     * @return bool true is updated, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function update(array $data): bool {
        $id = $data['col_id'];
        $name = $data['col_name'];
        try {
            $db = static::getDB();
            $stmt = $db->prepare("UPDATE tbl_departments SET col_name = :col_name WHERE col_id = :col_id");
            $stmt->bindValue(param: "col_name", value: $name);
            $stmt->bindValue(param: "col_id", value: $id);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message:$ex->getMessage(), code: "PDO");
        }
    }

    /**
     * deleteDepartment Method
     *
     * This method will delete a department from the database
     *
     * @param array $data
     * @return bool true is updated, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function deleteDepartment(array $data): bool {
        $id = $data['col_id'];
        $db = static::getDB();
        try {
            $stmt = $db->prepare(query: "DELETE FROM tbl_departments WHERE col_id = :col_id");
            $stmt->bindValue(param: ":col_id", value: $id);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
         }
    }

    /**
     * checkName Method
     *
     * This method check if a department in already in the database
     *
     * @param string $name The department name to check
     * @return bool true if department is in the database, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function checkName(string $name): bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_departments WHERE col_name = :name");
            $stmt->bindValue(param: ":name", value: $name);
            $stmt->execute();
            $result = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
            if(count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * displayError Method
     *
     * This method displays an error message when an error is encountered.
     * @param string $message
     * @param string $code
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @noinspection PhpSameParameterValueInspection
     */
    #[NoReturn] private static function displayError(string $message, string $code): void {
        Error::displayError(title: "Department Database Error", message: $message, code: $code);
    }
}