<?php

require_once("src/Controllers/User.php");

class Admin extends User
{

    protected $userModel;

    public function __construct()
    {
        session_start();
       $_SESSION['user_role'] = 'admin';
       $this->userModel = $this->model('UserModel');
       $this->fileModel = $this->model('FileModel');
    }

    public function filesList()
    {
        $files = $this->fileModel->findAllAdminFiles();
        $this->view('file/fileList', $files);
    }

    public function dirsList()
    {
        $dirs = $this->fileModel->findAllAdminDirs();
        $this->view('file/directoriesList', $dirs);
    }

    public function adminUsersList()
    {
        if (isset($_SESSION['user_role'])) {
            if (($_SESSION['user_role'] === 'admin')) {
                $this->list();
            }
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function showUser(int $id)
    {
        if (isset($_SESSION['user_role'])) {
            if ((!empty($_SESSION['user_role'])) && ($_SESSION['user_role'] === 'admin')) {
                $this->show($id);
            }
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function updateUser($id)
    {
        if (isset($_SESSION['user_role'])) {
            if ((!empty($_SESSION['user_role'])) && ($_SESSION['user_role'] === 'admin')) {
                $this->update($id);
            }
        } else {
            echo 'Требуются права администратора';
        }
    }

    public function deleteUser($id)
    {
        if (isset($_SESSION['user_role'])) {
            if ((!empty($_SESSION['user_role'])) && ($_SESSION['user_role'] === 'admin')) {
                $this->delete($id);
                header('Location:http://cloud-storage.local/user');
            }
        } else {
            echo 'Требуются права администратора';
        }
    }
}