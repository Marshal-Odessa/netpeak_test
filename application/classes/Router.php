<?php 

	namespace application\classes;

	class Router {

		private $routes;
		private $params;

		function __construct () {
			$routes = Config::Get('routes');
			if (!is_array($routes) || count($routes) <= 0) {
				exit('<h1 style="display:block;width:100%;margin: auto 0">Sorry, service temporary unavalable!</h1>');
			}
			foreach ($routes as $key => $value) {
				$this->AddRoute($key, $value);
			}
		}

		private function AddRoute ($route, $params) {
			$route = '#^'.$route.'$#';
			$this->routes[$route] = $params;
		}

		private function MatchRoute () {
			$url = trim($_SERVER['REQUEST_URI'], '/');
			foreach ($this->routes as $route => $params) {
				if (preg_match($route, $url, $matches)) {
					$this->params = $params;
					return true;
				}
			}
		}

		public function Run () {
			$view = new Viewer;
			if ($this->MatchRoute() && Engine::CheckController($this->params['controller'], $this->params['action'])) {
				$controller = Engine::Controller($this->params['controller']);
				$action = ucfirst($this->params['action']).'Action';
				$view->SetData('controller', $controller->$action());
				$view->Render($controller->tpl);
				$view->View();
				exit();
			}
			else {
				$view->Render('404');
				$view->View();
			}
		}

	}