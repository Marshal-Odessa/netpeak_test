<?php

	namespace application\controllers;

	use application\classes\Controller;
	use application\classes\Config;
	use application\classes\Engine;
	use application\classes\DB;

	class CalcController extends Controller {

		function __construct () {
			$this->Init();
		}

		public function ConvertAction () {
			if (!$this->LoadModel('converter')) {
				exit('{}');
			}

			$config = Config::Get('currencys');
			$response = Engine::Jquery();
			if (!is_array($config) || count($config) <= 0 || !is_array($response) || count($response) <= 0) exit('{}');
			$result = $response['from']['count'] >= 0 ? $this->model->Convert(floatval($response['from']['count'])) : false;
			if (is_array($result)) {
				$this->model->SetHistory(session_id(), [
					'in'=>$result['in'],
					'out'=>$result['out'],
					'count'=>floatval($response['from']['count']),
					'result'=>$result['result']
				]);
				$buffer = DB::Query("SELECT id, date, count FROM `history` WHERE id='".DB::Id()."'");
				if (is_array($buffer) && count($buffer) > 0)
					$result = array_merge($result, $buffer[0]);

				exit(Engine::JsonEncode($result));
			}
			else
				exit('{"error":"count null"}');
			
		}

		public function ClearhistoryAction () {
			$ssid = session_id();
			if (DB::Query("DELETE FROM `history` WHERE ssid='$ssid'")) {
				exit(Engine::JsonEncode(['success'=>'true']));
			}
			else
				exit(Engine::JsonEncode(['success'=>'false']));
		}

	}