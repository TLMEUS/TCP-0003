<?php
/**
 * This file contains the Core/Router.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Core
 * File Name: Router.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 3/23/23
 * File Authored on: 3/23/2023
 * File Copyright: 3/2023
 */

/**
 * Import needed classes
 */
use Exception;

/**
 * The url router for the application
 */
class Router {

    /**
     * Associative array of routes (the routing table)
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Parameters from the matched route
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Add a route to the routing table
     *
     * @param string $route  The route URL
     * @param array  $params Parameters (controller, action, etc.)
     * @return void
     */
    public function add(string $route, array $params = []):void  {

        /**
         * Convert the route to a regular expression
         */
        $route = preg_replace(pattern: '/\//', replacement: '\\/', subject: $route);
        $route = preg_replace(pattern: '/\{([a-z]+)}/', replacement: '(?P<\1>[a-z-]+)', subject: $route);
        $route = preg_replace(pattern: '/\{([a-z]+):([^}]+)}/', replacement: '(?P<\1>\2)', subject: $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes(): array {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     * @return boolean  true if a match found, false otherwise
     */
    public function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, matches: $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams(): array {
        return $this->params;
    }

    /**
     * Dispatch the route, creating the controller object and running the
     * action method

     * @param string $url The route URL
     * @return void
     * @throws Exception
     */
    public function dispatch(string $url): void {
        $url = $this->removeQueryStringVariables($url);
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;
            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                if (preg_match(pattern: '/action$/i', subject: $action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new Exception(message: "Method $action (in controller $controller) not found.");
                }
            } else {
                throw new Exception(message: "Controller class $controller not found.");
            }
        } else {
            throw new Exception(message: "No route matched.", code: 404);
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     *
     * @param string $string The string to convert
     * @return string
     */
    protected function convertToStudlyCaps(string $string): string {
        return str_replace(search: ' ', replace: '', subject: ucwords(string: str_replace( search: '-', replace: ' ', subject: $string)));
    }

    /**
     * Convert the string with hyphens to camelCase
     *
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     * @return string
     */
    protected function convertToCamelCase(string $string): string {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any). As the
     * full query string is used for the route, any variables at the
     * end will need to be removed before the route is matched to the
     * routing table.
     *
     * A URL of the format localhost/?page (one variable name, no value)
     * won't work however. (NB. The .htaccess file converts the first ?
     * to a & when it's passed through to the $_SERVER variable).
     *
     * @param string $url
     * @return string
     */
    protected function removeQueryStringVariables(string $url): string {
        if ($url != '') {
            $parts = explode(separator: '&', string: $url, limit: 2);
            if (!str_contains($parts[0], needle: '=')) $url = $parts[0]; else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace
     * defined in the route parameters is added if present.
     *
     * @return string The request URL
     */
    protected function getNamespace(): string {
        $namespace = 'App\Controllers\\';
        if (array_key_exists(key: 'namespace', array: $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}