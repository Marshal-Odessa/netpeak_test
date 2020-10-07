<?php 

	namespace application\classes;

	class Viewer {

		private $data = [];
		public $rendered = "";

		public function SetData ($key, $data) {
			$this->data[$key] = $data;
		}

		public function Render ($template) {
			if (!Engine::TemplateExists($template)) {
				$template = '404' ? Engine::TemplateExists('404') : false;
			}
			extract($this->data);
			ob_start();
			if ($template != false)
				require 'application/views/'.$template.'.html';
			else {
				print('<h1 style="display:block;width:100%;margin: auto 0">Sorry, service temporary unavalable!</h1>');
				Engine::$DB->Query("INSERT INTO `errors` (`id`, `error_str`, `date`) VALUES (NULL, 'Template not found, and template 404 not created!', NOW());");
				exit();
			}
			$this->rendered = ob_get_contents();
			ob_end_clean();
			return $template === true ? $this->rendered : false;
		}

		public function View () {
			if (strlen($this->rendered) > 0) {
				echo $this->rendered;
			}
			else {
				echo "Техническая ошибка! Мы уже разбираемся с проблемой, сайт заработает с минуты на минуту...";
			}
		}

	}