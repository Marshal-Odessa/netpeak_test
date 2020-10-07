<?php

	namespace application\classes;

	class Controller {

		protected $model;
		protected $view;
		public $tpl;

		protected function Init () {
			$this->view = new Viewer;
		}

		protected function LoadModel ($model) {
			if (Engine::ModelExists($model)) {
				$class = 'application\models\\'.ucfirst($model).'Model';
				$this->model = new $class;
				return true;
			}
			else
				return false; 
		}

	}