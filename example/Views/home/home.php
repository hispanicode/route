
<h2>Hispanic\Route</h2>
<h2>Examples Routings</h2>
<p>These routes are set in the file <strong>routes.php</strong></p>
<p><strong>TestController.php</strong> is a controller test</p>
<h3>send something</h3>
<form method='get' action='<?php echo Hispanic\Route::url("home/get") ?>'>
	<input type='text' name='name' />
	<input type='hidden' name='csrf_token' value='<?php echo Hispanic\Route::get_csrf_token() ?>' />
	<button>send</button>
</form>
<h3>Other routes</h3>
<ul>
	<li><a href='<?php echo Hispanic\Route::url("home/login") ?>'><?php echo Hispanic\Route::url("home/login") ?></a></li>
	<li><a href='<?php echo Hispanic\Route::url("home/string") ?>'><?php echo Hispanic\Route::url("home/string") ?> - return simple string without call a controller</a></li>
	<li><a href='<?php echo Hispanic\Route::url("home/dashboard") ?>'><?php echo Hispanic\Route::url("home/dashboard") ?> - :) yes, if you are logged or then error 404 (middelware is present)</a></li>
	<li><a href='<?php echo Hispanic\Route::url("home/arguments/1/hello+world") ?>'><?php echo Hispanic\Route::url("home/arguments/1/hello+world") ?> - pass arguments and validate with regular expressions</a></li>
    <li><a href='<?php echo Hispanic\Route::url("home/optional_argument") ?>'><?php echo Hispanic\Route::url("home/optional_argument") ?> - pass optional argument</a></li>
</ul>

<h2>public static methods</h2>
<h4>get($route, $ControllerAction, $regex = array())</h4>
<h4>post($route, $ControllerAction, $regex = array())</h4>
<h4>put($route, $ControllerAction, $regex = array())</h4>
<h4>delete($route, $ControllerAction, $regex = array())</h4>
<h4>verb($verb = array(), $route, $ControllerAction, $regex = array())</h4>
<h4>group(array $middleware, $group)</h4>
<h4>get_collection()</h4>
<h4>get_controller()</h4>
<h4>base_url()</h4>
<h4>request_uri()</h4>
<h4>is_ssl()</h4>
<h4>get_route()</h4>
<h4>request_method()</h4>
<h4>csrf_token($bool)</h4>
<h4>get_csrf_token()</h4>
<h4>url($route, $args = array())</h4>
