<?php
class Controller
{
    protected $data = [];

    private $language;

    public function __construct($locale) {
        $this->language = new Language($locale);
    }

    protected function load_language($file) {
        $this->data = array_merge($this->data, $this->language->load($file));
    }

    protected function model($model) {
        require_once MODEL_PATH . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {

        require_once  VIEW_PATH . $view . '.php';
    }

}