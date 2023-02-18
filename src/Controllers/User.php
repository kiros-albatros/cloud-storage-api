<?php

class User
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function reset_password(){
        if (!empty($_SESSION['user_email'])) {
            $this->db->query('SELECT * FROM `User` WHERE email = :email');
            $this->db->bind(':email', $_SESSION['user_email']);
            $user = $this->db->single();

            if ($user) {
                $subject = 'Сброс пароля';
                $body = 'Перейдите по ссылке для сброса пароля ...';
                try {
                    if  ( mail("kirsa.prikolnaja@gmail.com", $subject ,$body , 'Content-Type: text/html; charset=UTF-8')){
                       {
                            echo "sent";
                        }
                    }

                } catch (Exception $e) {
                    echo "Поломка";
                }
            }
        }
    }

    public function createUserSession($user){
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
      //  $_SESSION['user_auth_token'] = $user->auth_token;
        $session_id = sha1(random_bytes(100)) . sha1(random_bytes(100));
        setcookie('session_id', $session_id, 0, '/', '', false, true);
    }

    public function login() {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $this->db->query('SELECT * FROM `User` WHERE email = :email');
            $this->db->bind(':email', $data['email']);
            $user = $this->db->single();

            if ($user) {
                if ($user->password === $data['password']) {
//                    $token = sha1(random_bytes(100)) . sha1(random_bytes(100));
//                    $user->auth_token = $token;
                    $this->createUserSession($user);
                    echo "Добро пожаловать";
                } else {
                    echo "Неверный пароль";
                }
            } else {
                echo "Пользователя с таким email не существует";
            }
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        session_destroy();
    }

    // GET /user/ Получить список пользователей (массив)
    public function list()
    {
        $this->db->query('SELECT * FROM `User`;');
        $users = $this->db->resultSet();
        var_dump(['users' => $users]);
    }

//     GET /users/{id} Получить JSON-объект с информацией о конкретном пользователе
    public function show(int $id)
    {
        $this->db->query('SELECT * FROM `User` WHERE id = :id');
        $this->db->bind(':id', $id);
        $user = $this->db->single();
        if ($user) {
            echo json_encode($user);
        } else {
            echo 'smth went wrong';
        }
    }

    // POST /user/ Добавить пользователя
    public function add()
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $this->db->query('SELECT * FROM `User` WHERE email = :email');
            $this->db->bind(':email', $data['email']);
            $user = $this->db->single();
            if ($user) {
                echo "Пользователь с такой почтой уже существует";
                return;
            }

            $this->db->query('INSERT INTO `User` (email, password, role, auth_token) VALUES(:email, :password, :role, :auth_token)');
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $data['password']);
            $this->db->bind(":role", 'user');
            $authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
            $this->db->bind(":auth_token", $authToken);

            if ($this->db->execute()) {
                echo 'Пользователь успешно создан';
            } else {
                echo 'Что-то пошло не так';
            }
        } else {
            echo "Заполните поля";
        }
    }

    // PUT /user/ Обновить пользователя
    public function update(int $id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        if (!empty($_PUT['email']) && !empty($_PUT['password'])) {
            $data = [
                'email' => trim($_PUT['email']),
                'password' => trim($_PUT['password']),
            ];
            $this->db->query('SELECT * FROM `User` WHERE id = :id');
            $this->db->bind(':id', $id);

            if ($this->db->single()) {
                var_dump(['user-to-update' => $this->db->single()]);
            } else {
                echo 'Что-то пошло не так';
            }
            $this->db->query('UPDATE User SET email = :email, password = :password WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $data['password']);
//            $this->db->bind(":role", 'user');
//            $this->db->bind(":auth_token", '123654789');

            if ($this->db->execute()) {
                echo 'updated';
            } else {
                echo 'Что-то пошло не так';
            }
        } else {
            echo "Заполните поля";
        }
    }

    // DELETE /user/{id} Удалить пользователя
    public function delete($id)
    {
        $this->db->query('DELETE FROM User WHERE id = :id');
        $this->db->bind(':id', $id);
        if ($this->db->execute()) {
            echo 'Пользователь удалён';
        } else {
            echo 'Что-то пошло не так';
        }
    }
}