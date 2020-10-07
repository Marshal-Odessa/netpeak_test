<?php

	namespace application\classes;

	class Engine {

		private static $php_input;

		public static function Init () {
			session_start();
		}

		public static function Echo ($a) {
			if (isset($a))
				echo $a;
			else
				echo "";
		}

		public static function Controller ($name) {
			$class = 'application\controllers\\'.$name.'Controller';
			if (class_exists($class)) {
				$c = new $class;
				return $c;
			}
			else
				return false;
		}

		public static function CheckController ($name, $action) {
			$class = 'application\controllers\\'.ucfirst($name).'Controller';
			if (class_exists($class) && method_exists($class, ucfirst($action.'Action')))
				return true;
			else
				return false;
		} 

		public static function JsonDecode ($json) {
			$result = [];
			try {
				$result = json_decode($json, true);
				if (!is_array($result))
					throw new Exception ('Json not valid, decoding error!');
			}
			catch (Exception $e) {
				$result = [];
				if (DB::is_connect) {
					DB::Query("INSERT INTO `errors` (`id`, `error_str`, `date`) VALUES (NULL, '{$e->getMessage()}', NOW());");
				}
			}

			return $result;
		}

		public static function ModelExists ($model) {
			if (class_exists('application\models\\'.ucfirst($model).'Model'))
				return true;
			else
				return false;
		}

		public static function JsonEncode ($array) {
			if (is_array($array))
				return json_encode($array);
			else
				return null;
		}

		public static function TemplateExists ($tname) {
			if (file_exists('application/views/'.$tname.'.html'))
				return true;
			else
				return false;
		}

		public static function Jquery () {
			if (!is_array(self::$php_input))
				self::$php_input = self::JsonDecode(file_get_contents('php://input'));

			return self::$php_input;
		} 

	}