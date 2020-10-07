<?php

	namespace application\classes;

	class Config {

		public static function Get ($file) {
			$path = 'application/config/'.$file.'.json';
			$data = file_exists($path) ? file_get_contents($path) : false;
			if ($data == false) 
				return false;
			return Engine::JsonDecode($data);
		}

		public static function Set ($file, $array) {
			$path = 'application/config/'.$file.'.json';
			if (is_array($array)) {
				file_put_contents($path, json_encode($array));
				return true;
			}
			else
				return false;
		} 

	}