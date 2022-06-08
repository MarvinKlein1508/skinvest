<?php
class Home extends Controller
{

    public function index() {

        $this->load_language("home");
        $this->view('home', $this->data);
    }
}

