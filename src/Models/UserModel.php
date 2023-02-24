<?php

class UserModel
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db;
    }

    public function findOneUser($id)
    {
        return $this->db->getById($id, 'User');
    }

    public function findAllUsers()
    {
        $this->db->query('SELECT email FROM `User`;');
        return $this->db->resultSet();
    }

    public function deleteUser($id)
    {
        $user = $this->db->getById($id, 'User');
        if ($user) {
            $this->db->query('DELETE FROM User WHERE id = :id');
            $this->db->bind(':id', $id);
            if ($this->db->execute()) {
                echo 'Пользователь удалён';
            } else {
                echo 'Что-то пошло не так';
            }
        } else {
            echo "Нет такого пользователя";
        }
    }

    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM `User` WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function addUser($data)
    {
        $this->db->query('INSERT INTO `User` (email, password, role) VALUES(:email, :password, :role)');
        $this->db->bind(":email", $data['email']);
        $this->db->bind(":password", $data['password']);
        $this->db->bind(":role", 'user');
        if ($this->db->execute()) {
            echo 'Пользователь успешно создан';
        } else {
            echo 'Что-то пошло не так';
        }
    }

    public function updateUser($id, $data)
    {
        $user = $this->db->getById($id, 'User');
        if ($user) {
            $this->db->query('UPDATE User SET email = :email, password = :password WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(":email", $data['email']);
            $this->db->bind(":password", $data['password']);
            if ($this->db->execute()) {
                echo 'updated';
            } else {
                echo 'Что-то пошло не так';
            }
        } else {
            echo "Нет такого пользователя";
        }
    }
}