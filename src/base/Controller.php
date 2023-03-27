<?php

class Controller
{
    public function model($model)
    {
        require_once 'src/Models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []){
        if(file_exists('src/Views/' . $view . '.php')){
            require_once 'src/Views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }
}