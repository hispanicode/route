<?php

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