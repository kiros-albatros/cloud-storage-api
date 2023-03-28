<?php

const ROUTES = [
    '' => ['GET' => 'MainController::main()'],

    'user' => ['GET' => 'User::list()', 'POST' => 'User::add()'],
    'users/{id}' => ['GET' => 'User::show()'],
    'user/{id}' => ['PUT' => 'User::update()', 'DELETE' => 'User::delete()'],

    'user/change_pass' => ['GET' => 'User::changePass()'],

    'user/login' => ['POST' => 'User::login()', 'GET' => 'User::loginForm()'],
    'user/logout' => ['GET' => 'User::logout()'],
    'user/register' => ['GET' => 'User::register()'],
    'user/reset_password' => ['GET' => 'User::reset_password()'],

    'user/search/{email}' => ['GET' => 'User::search()'],

    'admin/user' => ['GET' => 'Admin::usersList()'],
    'admin/user/{id}' => ['GET' => 'Admin::showUser()', 'PUT' => 'Admin::updateUser()', 'DELETE' => 'Admin::deleteUser()'],

    'file' => ['GET' => 'File::list()', 'POST' => 'File::add()'],
    'file/add' => ['GET' => 'File::addForm()'],
    'file/edit/{id}'=>['GET' =>'File::updateForm()'],
    'file/delete/{id}'=>['GET' =>'File::delete()'],
    'file/{id}' => ['GET' => 'File::show()', 'PUT' => 'File::update()'],

    'directory' => ['POST' => 'File::addDirectory()'],
    'directory/{id}' => ['GET' => 'File::infoDirectory()', 'PUT' => 'File::renameDirectory()', 'DELETE' => 'File::deleteDirectory()'],

    'files/share/{id}' => ['GET' => 'File::shareList()'],
    'files/share/{id}/{user_id}' => ['PUT' => 'File::shareFile()', 'DELETE' => 'File::unshareFile()'],
];