<h3>route</h3>
<h4>Php model to define routes with a wide range of possibilities, like routing of laravel, but without relying on anyone, you can adapt this model to your model view controller system very easily.</h4>

<p>Install with composer</p>
<pre>
	composer require hispanicode/route
</pre>

<p>In the downloaded files you will find a folder named example, from there you can see different routing tests.</p>
<p>Important. The .htaccess is required, in the example folder is the .htaccess, simply copy it to the root of your project.</p>

<h3>routes.php example</h3>

<pre>
&lt;?php 
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
//Route::put(...) include in the form the next field &lt;input type="hidden" name="_method" value="put" /&gt;
//Route::delete(...) include in the form the next field &lt;input type="hidden" name="_method" value="delete" /&gt;

//Route::verb(array("get", "post", "put", "delete"), ...)
Route::verb(array("get"), "home/login", "TestController@login");
Route::verb(array("post"), "home/check_login", "TestController@check_login");

//Route::group(array("auth"), ... auth is a var session, for example: $_SESSION["auth"] = true, if the sessions exists these routes are availables
Route::group(array("auth"), function(){
	Route::get("home/dashboard", "TestController@dashboard");
});

//Pass arguments in the routes and filter with regular expressions
$filter_arguments = array("arg1" =&gt; "/^[0-9]+$/", "arg2" =&gt; "/^[a-z\s]+$/i");
Route::get("home/arguments/{arg1}/{arg2}", "TestController@arguments", $filter_arguments);
//Pass optional argument
Route::get("home/optional_argument/{arg=hello world}", "TestController@optional_argument");

/* csrf token securiry */
Route::csrf_token(true);
//if the route not exists
if (array_search(1, Route::get_collection()) === false) {
	header("HTTP/1.0 404 Not Found", true, 404);
	echo "&lt;h2&gt;ERROR 404&lt;/h2&gt;";
	exit;
}
</pre>

<h3>TestController.php example</h3>
<pre>
&lt;?php

use Hispanic\Route;

class TestController{
	
	public function home()
	{
		include "Views/home/home.php";
	}
	
	public function get()
	{
		return "The value is: " . $_GET["name"];
	}
	
	public function login()
	{
		//Example if you like show a view, create your custom model view, the include is not the best solution
		include "Views/home/login.php";
	}
	
	public function check_login()
	{
		if (isset($_POST["name"]) && isset($_POST["password"])) {
			if ($_POST["name"] == "demo" && $_POST["password"] == "demo") {
				$_SESSION["auth"] = true;
				$_SESSION["user_name"] = $_POST["name"];
				header("location: " . Route::url("home/dashboard"));
				exit;
			}
		}
		header("location:" . Route::url("home/login"));
		exit;
	}
	
	public function dashboard()
	{
		return "Welcome " . $_SESSION["user_name"];
	}
	
	public function arguments()
	{
		return "Arg1 = " . $_GET["arg1"] . "| Arg2 = " . urldecode($_GET["arg2"]);
	}
	
	public function optional_argument()
	{
		return "Arg = " . $_GET["arg"];
	}
	
}
</pre>

<h2>public static methods</h2>
<p>get($route, $ControllerAction, $regex = array()) - <small>generate get route</small></p>
<p>post($route, $ControllerAction, $regex = array()) - <small>generate post route</small></p>
<p>put($route, $ControllerAction, $regex = array()) - <small>generate put route</small></p>
<p>delete($route, $ControllerAction, $regex = array()) - <small>generate delete route</small></p>
<p>verb($verb = array(), $route, $ControllerAction, $regex = array()) - <small>generate diferents requests to one route. Example: array("get", "post", "put", "delete")</small></p>
<p>group(array $middleware, $group) - <small>grouping routes in a middleware</small></p>
<p>get_collection() - <small>get array with routes collection with two possibles values 0 or 1, If 1 is found, then the route is valid</small></p>
<p>get_controller() - <small>get controller associated with the route</small></p>
<p>base_url() - <small>get base url</small></p>
<p>request_uri() - <small>get request uri</small></p>
<p>is_ssl() - <small>check if is ssl (https)</small></p>
<p>get_route() - <small>get the current route</small></p>
<p>request_method() - <small>get the request method</small></p>
<p>csrf_token($bool) - <small>Create csrf_token session</small></p>
<p>get_csrf_token() - <small>get csrf_token</small></p>
<p>url($route, $args = array()) - <small>generate url from a route</small></p>
