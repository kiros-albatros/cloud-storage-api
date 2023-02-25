<?php

const ROUTES = [
    '' => ['GET' => 'MainController::main()'],

    'user/' => ['GET' => 'User::list()', 'POST' => 'User::add()'],
    'users/{id}' => ['GET' => 'User::show()'],
    'user/{id}' => ['PUT' => 'User::update()', 'DELETE' => 'User::delete()'],

    'user/login' => ['POST' => 'User::login()'],
    'user/logout' => ['GET' => 'User::logout()'],
    'user/reset_password' => ['GET' => 'User::reset_password()'],

    'user/search/{email}' => ['GET' => 'User::search()'],

    'admin/user/' => ['GET' => 'Admin::usersList()'],
    'admin/user/{id}' => ['GET' => 'Admin::showUser()', 'PUT' => 'Admin::updateUser()', 'DELETE' => 'Admin::deleteUser()'],

    'file/' => ['GET' => 'File::list()', 'POST' => 'File::add()'],
    'file/{id}' => ['GET' => 'File::show()', 'PUT' => 'File::update()', 'DELETE' => 'File::delete()'],

    'directory/' => ['POST' => 'File::addDirectory()'],
    'directory/{id}' => ['GET' => 'File::infoDirectory()', 'PUT' => 'File::renameDirectory()', 'DELETE' => 'File::deleteDirectory()'],

    'files/share/{id}' => ['GET' => 'File::shareList()'],
    'files/share/{id}/{user_id}' => ['PUT' => 'File::shareFile()', 'DELETE' => 'File::unshareFile()'],
];