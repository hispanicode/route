<?php 
session_start();
require "../src/Route.php";
require "Controllers/TestController.php";
use Hispanic\Route;

Route::get("", "TestController@home");
Route::get("home/home", "TestController@home");
Route::get("home/get", "TestController@get");
Route::get("home/string", function(){
	return "Hello World";
});
//Route::post(...)
//Route::put(...) include in the form the next field <input type="hidden" name="_method" value="put" />
//Route::delete(...) include in the form the next field <input type="hidden" name="_method" value="delete" />

//Route::verb(array("get", "post", "put", "delete"), ...)
Route::verb(array("get"), "home/login", "TestController@login");
Route::verb(array("post"), "home/check_login", "TestController@check_login");

//Route::group(array("auth"), ... auth is a var session, for example: $_SESSION["auth"] = true, if the sessions exists these routes are availables
Route::group(array("auth"), function(){
	Route::get("home/dashboard", "TestController@dashboard");
});

//Pass arguments in the routes and filter with regular expressions
$filter_arguments = array("arg1" => "/^[0-9]+$/", "arg2" => "/^[a-z\s]+$/i");
Route::get("home/arguments/{arg1}/{arg2}", "TestController@arguments", $filter_arguments);
//Pass optional argument
Route::get("home/optional_argument/{arg=hello world}", "TestController@optional_argument");



/* csrf token securiry */
Route::csrf_token(true);
//if the route not exists
if (array_search(1, Route::get_collection()) === false) {
	header("HTTP/1.0 404 Not Found", true, 404);
	echo "<h2>ERROR 404</h2>";
	exit;
}