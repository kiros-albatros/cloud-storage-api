<?php


class User extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    public function search($email)
    {
        $user = $this->userModel->findUserByEmail($email);
        if ($user) {
            echo json_encode($user);
        } else {
            echo "Пользователь не найден";
        }
    }

    public function reset_password()
    {
        if (!empty($_SESSION['user_email'])) {
            $user = $this->userModel->findUserByEmail($_SESSION['user_email']);
            if ($user) {
                $subject = 'Сброс пароля';
                $body = 'Перейдите по ссылке для сброса пароля ...';
                try {
                    if (mail($user->email, $subject, $body, 'Content-Type: text/html; charset=UTF-8')) {
                        { echo "sent"; }
                    }
                } catch (Exception $e) {
                    echo "Не удалось отправить письмо";
                }
            }
        }
    }

    public function createUserSession($user)
    {
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        $session_id = sha1(random_bytes(100)) . sha1(random_bytes(100));
        setcookie('session_id', $session_id, 0, '/', '', false, true);
    }

    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                ];
                $user = $this->userModel->findUserByEmail($data['email']);
                if ($user) {
                    if ($user->password === $data['password']) {
                        $this->createUserSession($user);
                        echo "Добро пожаловать";
                    } else {
                        echo "Неверный пароль";
                    }
                } else {
                    echo "Пользователь с таким email не существует";
                }
            }
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_role']);
            session_destroy();
        } else {
            echo 'Вы не авторизованы';
        }
    }

    public function list()
    {
        $users = $this->userModel->findAllUsers();
        if ($users) {
           echo json_encode($users);
        }
    }

    public function show(int $id)
    {
        $user = $this->userModel->findOneUser($id);
        if ($user) {
            echo json_encode($user);
        }
    }

    public function add()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                ];
                $user = $this->userModel->findUserByEmail($data['email']);
                if ($user) {
                    echo "Пользователь с такой почтой уже существует";
                    return;
                }
                $this->userModel->addUser($data);
            } else {
                echo "Заполните поля";
            }
        }
    }

    public function update(int $id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
       // var_dump($_PUT);
        if (!empty(trim($_PUT['email']) && !empty(trim($_PUT['password'])))) {
            $data = [
                'email' => trim($_PUT['email']),
                'password' => trim($_PUT['password']),
            ];
            $this->userModel->updateUser($id, $data);
        } else {
            echo "Заполните поля";
        }
    }

    public function delete(int $id)
    {
        $this->userModel->deleteUser($id);
    }
}

