<?php

class Controller
{
    public function model($model)
    {
        require_once 'src/Models/' . $model . '.php';
        return new $model();
    }
}