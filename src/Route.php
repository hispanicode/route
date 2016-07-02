<?php

/**
Hispanicode Route
Copyright (C) 2016  http://hispanicode.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Php model to define routes with a wide range of possibilities, like routing of laravel, but without relying on anyone, you can adapt this model to your model view controller system very easily.
 *
 * @author manudavgonz@gmail.com
 */

namespace Hispanic;

class Route {
    
    static $collection = array();
    static $controller = null;
    static $stop = false;
    
    public static function get($route, $ControllerAction, $regex = array())
    {
            $parsePath = self::parse_path($route, $regex);
            $_route = $parsePath->route;
            $args = $parsePath->args;
            $valid = $parsePath->valid;
            if ($valid) {
                $r = self::base_url().$_route;
                $method = strtolower(self::request_method());
                if ($method == "get" && self::request_uri() == $r) {
                    self::process($args, $ControllerAction);
                }
            }
    }
    
    public static function post($route, $ControllerAction, $regex = array())
    {
        $parsePath = self::parse_path($route, $regex);
        $route = $parsePath->route;
        $args = $parsePath->args;
        $valid = $parsePath->valid;
        if ($valid) {
            $r = self::base_url().$route;
            $method = strtolower(self::request_method());
            if ($method == "post" && self::request_uri() == $r) {
                self::process($args, $ControllerAction);
            }
        }
    }
    
    public static function put($route, $ControllerAction, $regex = array())
    {
        $parsePath = self::parse_path($route, $regex);
        $route = $parsePath->route;
        $args = $parsePath->args;
        $valid = $parsePath->valid;
        if ($valid) {
            $r = self::base_url().$route;
            $method = strtolower(self::request_method());
            $_method = strtolower($_POST['_method']);
            if ($method == "post" && $_method == "put" && self::request_uri() == $r) {
                self::process($args, $ControllerAction);
            }
        }
    }
    
    public static function delete($route, $ControllerAction, $regex = array())
    {
        $parsePath = self::parse_path($route, $regex);
        $route = $parsePath->route;
        $args = $parsePath->args;
        $valid = $parsePath->valid;
        if ($valid) {
            $r = self::base_url().$route;
            $method = strtolower(self::request_method());
            $_method = strtolower($_POST['_method']);
            if ($method == "post" && $_method == "delete" && self::request_uri() == $r) {
                self::process($args, $ControllerAction);
            }
        }
    }
    
    public static function verb($verb = array(), $route, $ControllerAction, $regex = array())
    {
        $parsePath = self::parse_path($route, $regex);
        $route = $parsePath->route;
        $args = $parsePath->args;
        $valid = $parsePath->valid;
            if ($valid) {
            $method = strtolower(self::request_method());
			if (isset($_POST["_method"])) {
				$_method = strtolower($_POST['_method']);
			} else {
				$_method = null;
			}
            if (is_array($verb)) {
                foreach ($verb as $v) {
                    if (
                            $method == "get" && 
                            strtolower($v) == "get" && 
                            self::get_route() == $route
                        ) {
                        self::process($args, $ControllerAction);
                        break;
                    }
                    if (
                            $method == "post" && 
                            strtolower($v) == "post" && 
                            self::get_route() == $route
                        ) {
                        self::process($args, $ControllerAction);
                        break;
                    }
                    if (
                            $method == "post" && 
                            strtolower($v) == "put" && 
                            $_method == "put" && 
                            self::get_route() == $route
                        ) {
                        self::process($args, $ControllerAction);
                        break;
                    }
                    if (
                            $method == "post" && 
                            strtolower($v) == "delete" && 
                            $_method == "delete" && 
                            self::get_route() == $route
                        ) {
                        self::process($args, $ControllerAction);
                        break;
                    }
                }
            } else if (is_string($verb)) {
                if (
                        $method == "get" && 
                        strtolower($verb) == "get" && 
                        self::get_route() == $route
                    ) {
                    self::process($args, $ControllerAction);
                }
                if (
                        $method == "post" && 
                        strtolower($verb) == "post" && 
                        self::get_route() == $route
                    ) {
                    self::process($args, $ControllerAction);
                }
                if (
                        $method == "post" && 
                        strtolower($verb) == "put" && 
                        $_method == "put" && 
                        self::get_route() == $route
                    ) {
                    self::process($args, $ControllerAction);
                }
                if (
                        $method == "post" && 
                        strtolower($verb) == "delete" && 
                        $_method == "delete" && 
                        self::get_route() == $route
                    ) {
                    self::process($args, $ControllerAction);
                }
            }
        }
    }
    
    public static function group(array $middleware, $group)
    {
        foreach ($middleware as $a) {
            if (!isset($_SESSION[$a]) && array_search(1, self::get_collection()) === false) {
                return;
            }
        }
        $group();
    }
    
    protected static function process($args, $ControllerAction) {
		
		if (isset($_GET["csrf_token"])) {
			if ($_GET["csrf_token"] != $_SESSION["csrf_token"]) {
				header("HTTP/1.0 404 Not Found", true, 404);
				echo "<h2>ERROR 404</h2>";
				exit;
			}
		} else if (isset($_POST["csrf_token"])) {
			if ($_POST["csrf_token"] != $_SESSION["csrf_token"]) {
				header("HTTP/1.0 404 Not Found", true, 404);
				echo "<h2>ERROR 404</h2>";
				exit;
			}
		}
        foreach (array_keys($args) as $key) {
            $_GET[$key] = $args[$key];
            $_REQUEST[$key] = $args[$key];
        }
        if (is_string($ControllerAction)) {
            $split = explode("@", $ControllerAction);
            $controller = $split[0];
            $action = $split[1];
            $instance = new $controller();
            self::$controller = $instance;
            $data = call_user_func(array($instance, $action), (Object)$args);
			if (is_string($data)) {
				echo $data;
			}
        }
        if (is_callable($ControllerAction)) {
            echo $ControllerAction((Object)$args);
        }
        exit;
    }
    
    protected static function parse_path($route, $regex)
    {
        $onlyRoute = self::get_route();
        
        if (preg_match("/\?/", $onlyRoute)) {
            $divider = explode("?", $onlyRoute);
            $params = $divider[1];
            $onlyRoute = $divider[0];
        }
        
        $args = array();
        $object = array();
        $object["args"] = array();
        $object["route"] = "";
        $object["valid"] = false;
        $validRegex = true;
        $redirect = false;
        
        if (preg_match("/\//", $route)) {
            $split1 = explode("/", $route);
            $split2 = explode("/", $onlyRoute);
            foreach(array_keys($split1) as $key) {
                
                if (@$split1[$key] == @$split2[$key]) {
                    $route = str_replace(@$split1[$key], @$split2[$key], $route);
                }
                
                if (
                        preg_match("/\{/", $split1[$key])
                    ) {
                    $arg = str_replace("{", "", $split1[$key]);
                    $arg = str_replace("}", "", $arg);
                    if ($split1[$key] != "") {
                        if (preg_match("/\=/", $arg)) 
                        {
                            $value = explode("=", $arg)[1];
                            $_value = explode("=", $arg)[1];
                            $arg = explode("=", $arg)[0];
                            
                            if (isset($_GET[$arg])) {
                                $value = $_GET[$arg];
                                $redirect = true;
                            } else if (@$split2[$key] != "") {
                                $value = @$split2[$key];
                            } else if (@$split2[$key] == "") {
                                $value = "";
                            }
                            if ($value == "") $args[$arg] = $_value;
                            else $args[$arg] = $value;
                        } else {
                            $_value = "";
                            if (isset($_GET[$arg])) {
                                $value = $_GET[$arg];
                                $redirect = true;
                            } else if (@$split2[$key] != "") {
                                $value = @$split2[$key];
                            } else if (@$split2[$key] == "") {
                                $value = "";
                            } 
                            if ($value == "") $args[$arg] = $_value;
                            else $args[$arg] = $value;
                        }
                        
                        if (array_key_exists($arg, $regex) && $value != "") {
                            if (!preg_match($regex[$arg], urldecode($value))) {
                                $validRegex = false;
                            }
                        }
          
                        $route = str_replace($split1[$key], $value, $route);
                    } else if (isset($split2[$key]) && $split2[$key] == "") {
                        if (preg_match("/\=/", $arg)){
                            $arg = explode("=", $arg)[0];
                            $value = explode("=", $arg)[1];
                            if (isset($_GET[$arg])) {
                                $redirect = true;
                                $args[$arg] = $_GET[$arg];
                            } else {
                                $args[$arg] = $value;
                            }
                            $route = str_replace($split1[$key], $value, $route);
                        }
                    }
                }
            }
            if ($route[strlen($route)-1] == "/") {
                
                $split = explode("/", $route);
                $str = "";
                foreach ($split as $r) {
                    if ($r != "") {
                        $str .= $r . "/";
                    }
                }
                
                $route = substr($str, 0, -1);
            }
            if ($redirect) {
                Redirect::to_route($route);
            }
        }
        
        $object["args"] = $args;
        $object["route"] = $route;

        if($route == $onlyRoute && $validRegex){
            $object["valid"] = true;
            array_push(self::$collection, 1);
        } else {
            $object["valid"] = false;
            array_push(self::$collection, 0);
        }
        return (Object)$object;
    }
    
    public static function get_collection() 
    {
        return self::$collection;
    }
    
    public static function get_controller()
    {
        return self::$controller;
    }
	
    public static function base_url()  
    {
        self::is_ssl() ? $pro = "https://" : $pro = "http://";
        
        $str = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
        $str = explode("index.php", $str);
        $base_url = $pro.$str[0];
        return $base_url;
    }

    public static function request_uri()
    {
        self::is_ssl() ? $pro = "https://" : $pro = "http://";
        return $pro . $_SERVER["HTTP_HOST"] 
                . str_replace("index.php", "", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
    }
	
    public static function is_ssl()
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            return true;
        }
        return false;
    }
	
    public static function get_route()
    {
        $route = str_replace(self::base_url(), "", self::request_uri());
        return $route;
    }
	
    public static function request_method()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }
	
	public static function csrf_token($bool)
	{
		if ($bool) {
			if (isset($_SESSION["csrf_token"])) {
				$_SESSION["csrf_token"] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);
			}
			if (isset($_POST["csrf_token"]) || isset($_GET["csrf_token"])) {
				$_SESSION["csrf_token"] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);
			}
		}
	}
	
	public static function get_csrf_token()
	{
		if (isset($_SESSION["csrf_token"])) {
			return $_SESSION["csrf_token"];
		}
	}
	
	public static function url($route, $args = array())
	{
		$params = "";
		if (is_array($args)) {
			if (count($args) > 0) {
				$params = "?";
				foreach($args as $key => $val) {
					$params .= $key . "=" . $val . "&";
				}
			}
		}
		if ($params != "") {
			$params = substr($params, 0, count($params)-2);
		}
		return self::base_url().$route.$params;
	}

}
