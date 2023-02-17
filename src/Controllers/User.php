<?php

class User {
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }
    // GET /user/ Получить список пользователей (массив)
    public function list() {
        $users = $this->db->query('SELECT * FROM `User`;');
        var_dump(['users' => $users]);
    }

//     GET /users/{id} Получить JSON-объект с информацией о
// конкретном пользователе
    public function show(int $id) {
        $user = $this->db->query(
            'SELECT * FROM `User` WHERE id = :id;',
            [':id' => $id]
        );
        var_dump(['one-user'=> json_encode($user)]);
        return json_encode($user);
    }

    // POST /user/ Добавить пользователя
     public function add(){
        return '';
     }

     // PUT /user/ Обновить пользователя
    public function update($id)
    {
        return '';
    }

    // DELETE /user/{id} Удалить пользователя
    public function delete($id)
    {
        return '';
    }
}