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
 * Role Model
 *
 * This model provides access to the role table in the database
 *
 * @extends Core\Model
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
class Roles extends Model {

    /**
     * create Method
     *
     * This method creates a role in the database
     *
     * @param array $data The role data to insert
     * @return bool Value indicating success of the insert
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function create(array $data):bool {
        try {
            $role = $data['role'];
            $db = static::getDB();
            $stmt = $db->prepare(query: "INSERT INTO tbl_roles (col_name) VALUES (:role)");
            $stmt->bindValue(param: ":role", value: $role);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * readAll Method
     *
     * This method returns a list of all roles as an array
     *
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function readAll(): array {
        try {
            $db = static::getDB();
            $stmt = $db->query(query: "SELECT * FROM tbl_roles ORDER BY col_id");
            return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * readOne Method
     *
     * This method return a single role based on ID number
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
            $stmt = $db->prepare(query: "SELECT * FROM tbl_roles WHERE col_id = :col_id");
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
     * This method will update a role in the database
     *
     * @param array $data Array containing the new role data
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
            $stmt = $db->prepare(query: "UPDATE tbl_roles SET col_name = :col_name WHERE col_id = :col_id");
            $stmt->bindValue(param: "col_name", value: $name);
            $stmt->bindValue(param: "col_id", value: $id);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * deleteRole Method
     *
     * This method will delete a role from the database
     *
     * @param array $data
     * @return bool true is updated, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function deleteRole(array $data): bool {
        $id = $data['col_id'];
        $db = static::getDB();
        try {
            $stmt = $db->prepare(query: "DELETE FROM tbl_roles WHERE col_id = :col_id");
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
     * This method check if a role in already in the database
     *
     * @param string $name The role name to check
     * @return bool true if role is in the database, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function checkName(string $name): bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_roles WHERE col_name = :name");
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
        Error::displayError(title: "Role Database Error", message: $message, code: $code);
    }

}