<?php
/**
 * This file contains the Controllers/Roles.php file for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Controllers
 *  File Name: Roles.php
 *  Author: Troy L. Marker
 *  Language: PHP 8.2
 *  File Authored on: 07/20/2023
 *  File Copyright: 07/2023
 */
namespace App\Controllers;

/**
 * Import required classes
 */

use Core\Controller;
use App\Models\Roles as RoleModel;
use Core\Error;
use Core\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
class Roles extends Controller {

    /**
     * index Action
     *
     * This action display a list of the current departs in the database
      *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(): void {
        $roles = RoleModel::readAll();
        View::render(template: 'Roles/index.twig', args: ['roles' => $roles]);
    }

    /**
     * create Action
     *
     * This action present to create department entry form
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createAction():void {
        View::render(template: "Roles/create.twig");
    }

    /**
     * createRoleAction
     *
     * This action will add a department to the database
     *
     * @return void
     * @throws Exception
     */
    public function createRoleAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateCreateData($_POST);
        if(!RoleModel::checkName($_POST['role'])) {
            if (!RoleModel::create($_POST)) {
                self::displayError(message: "Unable to save role", code: 500);
            }
        }
        $this->indexAction();

    }

    /**
     * updateAction
     *
     * This method displays the update department form.
     *
     * @throws Exception
     */
    public function updateAction(): void {
        $old_rol = RoleModel::readSingle($this->route_params['id']);
        if(!$old_rol) {
            self::displayError(message: "Unable to locate record", code: 404);
        } else {
            View::render(template: 'Roles/update.twig', args: ["col_id" => $old_rol['col_id'],
                "col_name" => $old_rol['col_name']
            ]);
        }
    }

    /**
     * updateRoleAction
     *
     * This method updates the role name in the database
     *
     * @return void
     * @throws Exception
     */
    public function updateRoleAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateUpdateData($_POST);
        if(!RoleModel::update($_POST)) {
            self::displayError(message: "Unable to update role", code: 500);
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
        $old_rol = RoleModel::readSingle($this->route_params['id']);
        if(!$old_rol) {
            self::displayError(message: "Unable to locate record", code: 404);
        }
        View::render(template: 'Roles/delete.twig', args: ["col_id" => $old_rol['col_id'],
            "col_name" => $old_rol['col_name']]);
    }

    /**
     * deleteRoleAction Method
     *
     * This method deletes a role from the database
     *
     * @throws Exception
     */
    public function deleteRoleAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: 405);
        }
        $this->validateDeleteData($_POST);
        if(!RoleModel::deleteRole($_POST)) {
            self::displayError(message: "Unable to delete role", code: 500);
        }
        $this->indexAction();
    }

    /**
     * Validate Create Data Method
     *
     * This method will validate the create data.
     *
     * @param array $data The department data to validate
     * @return void
     * @throws Exception
     */
    private function validateCreateData(array $data):void {
        $role = $data["role"];
        if(empty($role)) {
            self::displayError(message: "Role name can not be empty.", code: 406);
        }
    }

    /**
     * Validate Update Data Method
     *
     * This method validates the update data.
     *
     * @param array $data The department data to validate
     * @return void
     * @throws Exception
     */
    private function validateUpdateData(array $data): void {
        $role = $data['col_name'];
        if(empty($role)) {
            self::displayError(message: "Role name can not be empty.", code: 406);
        }
    }

    /**
     * Validate Delete Data Method
     *
     * This method validates the delete data array
     *
     * @param array $data The department date to validate
     * @return void
     * @throws Exception
     */
    private function validateDeleteData(array $data): void {
        $id = $data['col_id'];
        if(empty($id)) {
            self::displayError(message: "Role ID can not be empty.", code: 406);
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
        Error::displayError(title: "Role Entry Error", message: $message, code: $code);
    }
}