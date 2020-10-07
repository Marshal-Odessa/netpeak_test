<?php 
	
	require 'application/autoload.php';

	use application\classes\Router;
	use application\classes\Engine;
	use application\classes\DB;

	DB::Init();
	Engine::Init();

	$router = new Router;
	$router->Run();