<?php

class MainController extends Controller
{
    public function main()
    {
        $this->view('mainPage');
    }

    public function notFound()
    {
        $this->view('404');
    }
}
