<?php

const ROUTES = [
    '' => ['GET' => 'MainController::main()'],

    'user' => ['GET' => 'User::list()', 'POST' => 'User::add()'],
    'users/{id}' => ['GET' => 'User::show()'],
    'user/{id}' => ['PUT' => 'User::update()', 'DELETE' => 'User::delete()'],
    'user/delete/{id}' => ['GET' => 'Admin::deleteUser()'],
    'admin/files'=> ['GET' => 'Admin::filesList()'],
    'admin/directories'=> ['GET' => 'Admin::dirsList()'],

    'user/change_pass' => ['GET' => 'User::changePass()'],

    'user/login' => ['POST' => 'User::login()', 'GET' => 'User::loginForm()'],
    'user/logout' => ['GET' => 'User::logout()'],
    'user/register' => ['GET' => 'User::register()'],
    'user/reset_password' => ['GET' => 'User::reset_password()'],

    'user/search/{email}' => ['GET' => 'User::search()'],

//    'admin/user' => ['GET' => 'Admin::usersList()'],
//    'admin/user/{id}' => ['GET' => 'Admin::showUser()', 'PUT' => 'Admin::updateUser()', 'DELETE' => 'Admin::deleteUser()'],

    'file' => ['GET' => 'File::list()', 'POST' => 'File::add()'],
    'file/add' => ['GET' => 'File::addForm()'],
    'file/edit/{id}'=>['GET' =>'File::updateForm()'],
    'file/delete/{id}'=>['GET' =>'File::delete()'],
    'file/{id}' => ['GET' => 'File::show()', 'PUT' => 'File::update()'],

    'directory' => ['GET' => 'File::getDirsList()', 'POST' => 'File::addDirectory()'],
    'directory/add' => ['GET' => 'File::addDirectoryForm()'],
    'directory/delete/{id}' => ['GET' => 'File::deleteDirectory()'],
    'directory/{id}' => ['GET' => 'File::infoDirectory()', 'PUT' => 'File::renameDirectory()'],

    'files/share/{id}' => ['GET' => 'File::shareList()'],
    'files/shared' => ['GET' => 'File::getSharedFiles()'],
    'files/share/{id}/{user_id}' => ['GET' => 'File::shareFile()'],
    'files/unshare/{id}/{user_id}' => ['GET' => 'File::unshareFile()'],
];