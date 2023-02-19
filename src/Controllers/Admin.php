<?php

class Admin extends User
{
    public function usersList(){
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->list();
            echo "admin list";
        } else {
            echo 'only admins can do it';
        }
    }

    public function showUser(int $id) {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->show($id);
            echo "admin user info";
        } else {
            echo 'only admins can do it';
        }
    }

    public function updateUser(int $id) {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->update($id);
            echo "admin updates user";
        } else {
            echo 'only admins can do it';
        }
    }

    public function deleteUser(int $id) {
        if ((!empty($_SESSION['role'])) && ($_SESSION['role'] === 'admin')) {
            $this->delete($id);
            echo "admin deletes user";
        } else {
            echo 'only admins can do it';
        }
    }

}