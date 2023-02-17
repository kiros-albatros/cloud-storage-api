<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    // GET /user/ Получить список пользователей (массив)
    public function list()
    {
        $this->db->query('SELECT * FROM `User`;');
        $users = $this->db->resultSet();
        var_dump(['users' => $users]);
    }

//     GET /users/{id} Получить JSON-объект с информацией о
// конкретном пользователе
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
        //   header('Content-Type: application/json'); // укажем MIME
    }

    // POST /user/ Добавить пользователя
    public function add()
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];
            $this->db->query('INSERT INTO `User` (email, password, role, auth_token) VALUES(:email, :password, :role, :auth_token)');
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $data['password']);
            $this->db->bind(":role", 'user');
            $this->db->bind(":auth_token", '123654789');

            if ($this->db->execute()) {
                echo 'created';
            } else {
                echo 'smth went wrong';
            }
        } else {
            echo "data is empty";
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
                echo 'smth went wrong';
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
                echo 'smth went wrong';
            }
        } else {
            echo "data is empty";
        }
    }

    // DELETE /user/{id} Удалить пользователя
    public function delete($id)
    {
        $this->db->query('DELETE FROM User WHERE id = :id');
        $this->db->bind(':id', $id);
        if ($this->db->execute()) {
            echo 'deleted';
        } else {
            echo 'smth went wrong';
        }
    }
}