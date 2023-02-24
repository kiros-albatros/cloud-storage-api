<?php

require_once("src/Controllers/User.php");

class Admin extends User
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    public function usersList()
    {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->list();
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function showUser(int $id)
    {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->show($id);
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function updateUser($id)
    {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->update($id);
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function deleteUser($id)
    {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->delete($id);
        } else {
            echo 'Требуются права администратора';
        }
    }

}