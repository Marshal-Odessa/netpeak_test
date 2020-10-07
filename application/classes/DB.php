<?php

	namespace application\classes;

	class DB {

		public static $mysqli;
		public static $is_connect;
		public static $error;

		public static function Init () {
			$config = Config::Get('db');
			if ($config) {
				self::$mysqli = mysqli_connect($config['host'], $config['user'], $config['password'], $config['db']);
				if (!self::$mysqli)
					return false;
				self::$is_connect = true;
			}
			else
				return false;
		}

		public static function Id () {
			return mysqli_insert_id(self::$mysqli);
		}

		public function Query ($sql) {
			if (self::$is_connect == false) return false;
			$q = mysqli_query(self::$mysqli, $sql);
			if (!$q) {
				self::$error = mysqli_error(self::$mysqli);
				return false;
			}
			if (stristr($sql, "select")) {
				$num = mysqli_num_rows($q);
				$result = [];
				for ($i = 0; $i < $num; $i++)
					$result [] = mysqli_fetch_assoc($q);
				return $result;
			}
			else {
				return true;
			}
		}

		public function __destruct () {
			mysqli_close(self::$mysqli);
		}

	}