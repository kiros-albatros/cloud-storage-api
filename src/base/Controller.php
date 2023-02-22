<?php

class Controller
{
// Load model
    public function model($model){
        // Require model file
        require_once 'src/Models/' . $model . '.php';

        // Instatiate model
        return new $model();
    }
}