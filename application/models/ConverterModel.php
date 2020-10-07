<?php

	namespace application\models;

	use application\classes\Model;
	use application\classes\Engine;
	use application\classes\DB;

	class ConverterModel extends Model {

		private $url = "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5";
/*
		private $url = "https://free.currconv.com/api/v7/convert?q=%i_%o&compact=ultra&apiKey=";
		private $apiKey = "35a3294809bb5f29d616";
*/

		public function GetHistory ($ssid) {
			if (strlen($ssid) <= 0) return false;
			$data = DB::Query("SELECT * FROM `history` WHERE ssid='{$ssid}'");
			if ($data == true)
				return $data;
			else
				return false;
		}

		public function SetHistory ($ssid, $array) {
			if (strlen($ssid) <= 0 || !is_array($array) || count($array) != 4) {
				return false;
			}
			if (DB::Query("INSERT INTO `history` (`id`, `ssid`, `in_currency`, `out_currency`, `count`, `result`, `date`) VALUES (NULL, '$ssid', '{$array['in']}', '{$array['out']}', '{$array['count']}', '{$array['result']}', NOW());"))
			return true;

			else
				return false;
		}

		private function GetRate () {

			return Engine::JsonDecode(file_get_contents($this->url));
		}

		private function GetCurrsInUrl () {
			$url = trim($_SERVER['REQUEST_URI'], '/');
			$a = explode('/', $url);
			if (count($a) == 4)
				return ['in'=>$a[2], 'out'=>$a[3]];
			return false;
		}

		public function Convert ($count) {
			if ($count < 0) return false;
			$currs = $this->GetCurrsInUrl();
			if (!$currs)
				return false;
			if ($currs['in'] == $currs['out'] || $count == 0)
				return ['result'=>floatval($count), 'in'=>$currs['in'], 'out'=>$currs['out']];
			$rate = $this->GetRate();
			foreach ($rate as $key => $value) {
				$rate[$value['ccy']] = $value;
			}
			if ($currs['in'] != $rate[0]['base_ccy'] && $currs['out'] != $rate[0]['base_ccy'])
				$convert = $rate[$currs['in']]['buy'] * $count / $rate[$currs['out']]['buy'];
			elseif ($currs['out'] == $rate[0]['base_ccy'] && $currs['in'] != $rate[0]['base_ccy'])
				$convert = $count * $rate[$currs['in']]['buy'];
			else
				$convert = $count / $rate[$currs['out']]['buy'];
				
			return ['result'=>round($convert, 2), 'in'=>$currs['in'], 'out'=>$currs['out']];
		}

	}