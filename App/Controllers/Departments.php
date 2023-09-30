<?php
/**
 * This file contains the Controllers/Departments.php file for the TLME-User Manager App
 *
 *  File Information:
 *  Project Name: TLME-User Manger
 *  Module Name: Controllers
 *  File Name: Departments.php
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
use App\Models\Departments as DepartmentModel;
use Core\Error;
use Core\View;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * This file contains the Departments Controller
 */
class Departments extends Controller {

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
        $departments = DepartmentModel::readAll();
        View::render(template: 'Departments/index.twig', args: ['departments' => $departments]);
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
        View::render(template: "Departments/create.twig");
    }

    /**
     * createDepartmentAction
     *
     * This action will add a department to the database
     *
     * @return void
     * @throws Exception
     */
    public function createDepartmentAction(): void {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            self::displayError(message: "Method not allowed", code: "405");
        }
        $this->validateCreateData($_POST);
        if(!DepartmentModel::create($_POST)) {
            self::displayError(message: "Unable to save department", code: "500");
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
        $old_dep = DepartmentModel::readSingle($this->route_params['id']);
        if(!$old_dep) {
            self::displayError(message: "Record not found", code: "404");
        } else {
            View::render(template: 'Departments/update.twig', args: ["col_id" => $old_dep['col_id'],
                "col_name" => $old_dep['col_name']]);
        }
    }

    /**
     * updateDepartmentAction
     *
     * This method updates the department name in the database
     *
     * @return void
     * @throws Exception
     */
    public function updateDepartmentAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed.", code: "405");
        }
        $this->validateUpdateData($_POST);
        if(!DepartmentModel::update($_POST)) {
            self::displayError(message: "Unable to update department", code: "500");
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
        $old_dep = DepartmentModel::readSingle($this->route_params['id']);
        if(!$old_dep) {
            self::displayError(message: "Unable to locate record", code: "404");
        }
        View::render(template: 'Departments/delete.twig', args: ["col_id" => $old_dep['col_id'],
                "col_name" => $old_dep['col_name']]);
    }

    /**
     * deleteDepartmentAction Method
     *
     * This method deletes a department from the database
     *
     * @throws Exception
     */
    public function deleteDepartmentAction(): void {
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            self::displayError(message: "Method not allowed", code: "405");
        }
        $this->validateDeleteData($_POST);
        if(!DepartmentModel::deleteDepartment($_POST)) {
            self::displayError(message: "Unable to delete department", code: "500");
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
        $department = $data["department"];
        if(empty($department)) {
            self::displayError(message: "Department name can not be empty", code: "406");
        }
        if(DepartmentModel::checkName($department)) {
            self::displayError(message: "Department exists in database.", code: "406");
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
        $department = $data['col_name'];
        if(empty($department)) {
            self::displayError(message: "Department name can not be empty", code: "406");
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
            self::displayError(message: "Department ID can not be empty", code: "406");
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
        Error::displayError(title: "Department Entry Error", message: $message, code: $code);
    }
}