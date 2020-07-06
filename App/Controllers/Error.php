<?php

namespace App\Controllers;

class Error extends Controller
{
    protected object $view;

    public function __construct(\Exception $error)
    {
        parent::__construct();
        $this->view->error = $error;
    }

    protected function action()
    {
        $this->view->display(__DIR__ . '/../templates/error.php');
    }
}