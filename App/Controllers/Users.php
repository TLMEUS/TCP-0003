<?php
/**
 * This file contains the Controllers/Users.php file for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Controllers
 *  File Name: Users.php
 *  Author: Troy L. Marker
 *  Language: PHP 8.2
 *  File Authored on: 07/29/2023
 *  File Copyright: 07/2023
 */
namespace App\Controllers;

/**
 * Import Required Classes
 */

use Core\Controller;
use App\Models\Users as UserModel;
use Core\Error;
use Core\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * This file contains the Users Controller
 */
class Users extends Controller {

    /**
     * indexAction Method
     *
     * This method show a list of users in the database
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function indexAction(): void {
        $users = UserModel::readAll();
        View::render(template: "Users/index.twig", args: ['users' => $users]);
    }

    /**
     * createAction method
     *
     * This method shows the create user screen
     *
     * @return void
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function createAction(): void {
        $departments = UserModel::getDepartments();
        $roles = UserModel::getRoles();
        View::render(template: "Users/create.twig", args: ['departments' => $departments, 'roles' => $roles]);
    }

    /**
     * createUserAction Method
     *
     * This method creates a user in the database
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function createUserAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: 'Method not allowed.', code: '405');
        }
        $this->validateCreateData($_POST);
        if(!UserModel::create($_POST)) {
            self::displayError(message: 'Unable to save user', code: '500');
        }
        $this->indexAction();
    }

    /**
     * updateAction Method
     *
     * This method  will update a user record in the database
     *
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function updateAction(): void {
        $old_user = UserModel::readSingle($this->route_params['id']);
        $departments = UserModel::getDepartments();
        $roles = UserModel::getRoles();
        if(!$old_user) {
            self::displayError(message: "Unable to locate record", code: "404");
        }
        View::render(template: 'Users/update.twig',
            args: ["user" => $old_user, "departments" => $departments, "roles" => $roles]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function updateUserAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: 'Method not allowed.', code: '405');
        }
        $this->validateUpdateData($_POST);
        if(!UserModel::update($_POST)) {
            self::displayError(message: 'Unable to update user', code: '500');
        }
        $this->indexAction();
    }

    /**
     * deleteAction method
     *
     * This method presents the delete department option
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function deleteAction(): void {
        $old_user = UserModel::readSingle($this->route_params['id']);
        if(!$old_user) {
            self::displayError(message: "Unable to locate record", code: "404");
        }
        View::render(template: 'Users/delete.twig',
            args: ["col_id" => $old_user['col_id'], "col_username" => $old_user['col_username']]);
    }

    /**
     * deleteUserAction Method
     *
     * This method deletes a user from the database
     *
     * @returns void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     */
    public function deleteUserAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: 'Method not allowed', code: '405');
        }
        $this->validateDeleteData($_POST);
        if(!UserModel::delete($_POST)) {
            self::displayError(message: "Unable to delete user", code: "500");
        }
        $this->indexAction();
    }

    /**
     * validateCreateData method
     *
     * Method to validate create data
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateCreateData(array $data): void {
        if(empty($data['col_username'])) {
            self::displayError(message: "Username must be provided", code: 406);
        }
        if(UserModel::checkName(username: $data['col_username'])) {
            self::displayError(message: "Username exists in database", code: 406);
        }
        if(empty($data['col_password'])) {
            self::displayError(message: "Password must be provided", code: 406);
        }
        if(empty($data['col_department'])) {
            self::displayError(message: "Department must be provided", code: 406);
        }
        if(empty($data['col_role'])) {
            self::displayError(message: "Role must be provided", code: 406);
        }
    }

    /**
     * validateUpdateData method
     *
     * This method will validate the data submitted for an update
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateUpdateData(array $data): void {
        if(empty($data['col_department'])) {
            self::displayError(message: "Department must be provided", code: 406);
        }
        if(empty($data['col_role'])) {
            self::displayError(message: "Role must be provided", code: 406);
        }
    }
    /**
     * validateDeleteData Method
     *
     * This method validate the delete data
     *
     * @param array $data
     * @return void
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function validateDeleteData(array $data): void {
        if(empty($data['col_id'])) {
            self::displayError(message: "User ID can not be empty", code: "406");
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
     */
    #[NoReturn] private static function displayError(string $message, string $code): void {
        Error::displayError(title: 'User Entry Error', message: $message, code: $code);
    }
}