<?php

	namespace application\controllers;

	use application\classes\Controller;
	use application\classes\Config;

	class PageController extends Controller {

		function __construct () {
			$this->tpl = 'main';
			$this->Init();
		}

		public function MainAction () {
			return ['title'=>'Конвертер валют Онлайн - CURRCALC',
			'header'=>'Нужно ковертировать валюту?',
			'content' => 'Конвертер валют «<strong>CURRCALC</strong>» поможет вам ковертировать любую валюту по текущему курсу в один клик. Наш конвертер обладает памятью, если вы случайно обновите страницу или оборвется интернет соединение не беда, конвертер всегда будет помнить ваши последние конвертиции.'];
		}

		public function ConverterAction () {
			$ssid = session_id();
			if (!$this->LoadModel('converter')) {
				return ['title'=>'Error', 'header'=>'Model "Converter" not exists!', 'content'=>'Не удалось найти модуль конвертера!'];
			}

			$result = ['title'=>'Конвертировать валюту - CURRCALC', 'header'=>'Конвертировать валюту'];

			$currs = Config::Get('currencys');
			$history = $this->model->GetHistory(session_id());

			if (!is_array($currs) && count($currs) <= 0)
				$result['header'] = "Error";

			$this->view->SetData('config', $currs);
			$this->view->SetData('history', $history);
			$this->view->Render('converter');

			$result['content'] = $this->view->rendered;

			return $result;
		}

	}