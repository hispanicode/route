<h3>Login</h3>
<form method="post" action="<?php echo Hispanic\Route::url("home/check_login"); ?>">
	<div>
		<label for="name">Name: demo</label>
		<input type="text" name="name" />
	</div>
	<div>
		<label for="name">Password: demo</label>
		<input type="password" name="password" />
	</div>
	<input type="hidden" name="csrf_token" value="<?php echo Hispanic\Route::get_csrf_token() ?>" />
	<div>
		<button>Start</button>
	</div>
</form>