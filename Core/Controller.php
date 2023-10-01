<?php
/**
 * This file contains the Core/Controller.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Core
 * File Name: Controller.php
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
use Exception;

/**
 * Core Controller class definition
 */
abstract class Controller {

    /**
     * Parameters from the matched route
     *
     * @var array
     */
    protected array $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     * @return void
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name Method name
     * @param array $args Arguments passed to the method
     * @return void
     * @throws Exception
     */
    public function __call(string $name, array $args): void
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new Exception(message: "Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     * @return void
     */
    protected function before(): void
    {
    }

    /**
     * After filter - called after an action method.
     * @return void
     */
    protected function after(): void
    {
    }
}