<?php
/**
 * This file contains the Models/Users.php file for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Models
 *  File Name: Users.php
 *  Author: Troy L. Marker
 *  Language: PHP 8.2
 *  File Authored on: 07/29/2023
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

class Users extends Model {
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
            $password = password_hash($data['col_password'], PASSWORD_DEFAULT);
            $db = static::getDB();
            $stmt = $db->prepare(query: "INSERT INTO tbl_users (col_username, col_password, col_department, col_role) VALUES (:col_username, :col_password, :col_department, :col_role)");
            $stmt->bindValue(param: ":col_username", value: $data['col_username']);
            $stmt->bindValue(param: ":col_password", value: $password);
            $stmt->bindValue(param: ":col_department", value: $data['col_department']);
            $stmt->bindValue(param: ":col_role", value: $data['col_role']);
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
            $stmt = $db->query(query: "SELECT col_id, col_username, (SELECT col_name FROM tbl_departments WHERE 
                    col_id = Users.col_department) AS col_dname, (SELECT col_name FROM tbl_roles WHERE 
                    col_id = Users.col_role) AS col_rname FROM tbl_users AS Users ORDER BY Users.col_id");
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
            $stmt = $db->prepare(query: "SELECT col_id, col_username, (SELECT col_name FROM tbl_departments WHERE col_id = Users.col_department) AS col_dname, (SELECT col_name FROM tbl_roles WHERE col_id = Users.col_role) AS col_rname FROM tbl_users AS Users WHERE Users.col_id = :col_id");
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
        try {
            $db = static::getDB();
            $stmt = $db->prepare("UPDATE tbl_users SET col_username = :col_username, col_department = :col_department, col_role = :col_role WHERE col_id = :col_id");
            $stmt->bindValue(param: "col_username", value: $data['col_username']);
            $stmt->bindValue(param: "col_department", value: $data['col_department']);
            $stmt->bindValue(param: "col_role", value: $data['col_role']);
            $stmt->bindValue(param: "col_id", value: $data['col_id']);
            $stmt->execute();
            return true;
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
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
    public static function delete(array $data): bool {
        $db = static::getDB();
        try {
            $stmt = $db->prepare(query: "DELETE FROM tbl_users WHERE col_id = :col_id");
            $stmt->bindValue(param: ":col_id", value: $data['col_id']);
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
     * @param string $username The username to check
     * @return bool true if department is in the database, false otherwise
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function checkName(string $username): bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_users WHERE col_username = :col_username");
            $stmt->bindValue(param: ":col_username", value: $username);
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
     * getDepartments Method
     *
     * This method will get a list of the departments
     *
     * @return array|bool
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function getDepartments(): array|bool {
        try {
            $db = static::getDB();
            $stmt = $db->prepare(query: "SELECT * FROM tbl_departments");
            $stmt->execute();
            return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            self::displayError(message: $ex->getMessage(), code: "PDO");
        }
    }

    /**
     * getRoles Method
     *
     * This method will get a list of roles
     *
     * @return array|bool
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function getRoles(): array|bool {
         try {
             $db = static::getDB();
             $stmt = $db->query(query: "SELECT * FROM tbl_roles");
             $stmt->execute();
             return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
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
        Error::displayError(title: 'User Database Error', message: $message, code: $code);
    }
}