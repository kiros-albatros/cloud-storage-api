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

    public function changePass()
    {
        $data['email'] = $_SESSION['user_email'];
        $this->view('changePass', $data);
    }

    public function reset_password()
    {
        if (!empty($_SESSION['user_email'])) {
            $user = $this->userModel->findUserByEmail($_SESSION['user_email']);
            if ($user) {
                $subject = 'Сброс пароля';
                $body = "<a href='http://cloud-storage.local/user/change_pass'>Перейдите по ссылке для сброса пароля </a>" ;
                try {
                    if (mail($user->email, $subject, $body, 'Content-Type: text/html; charset=UTF-8')) {
                        { echo "<p>Email was sent</p><a href='/'>Main Page</a>"; }
                    }
                } catch (Exception $e) {
                    echo "<p>Email was not sent</p><a href='/'>Main Page</a>";
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
       // var_dump($_SESSION);
    }

    public function login()
    {
        $data = [
            'email' => '',
            'password' => '',
            'password_err' => '',
            'email_err' => '',
            'empty_err' => ''
        ];
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
                $data ['email'] = $this->sanitize($_POST['email']);
                $data['password'] = $this->sanitize($_POST['password']);
                $user = $this->userModel->findUserByEmail($data['email']);
                if ($user) {
                   // $user->password === $data['password']
                    if (password_verify($data['password'], $user->password)) {
                        $this->createUserSession($user);
                       // echo "Добро пожаловать";
                       header('Location: http://cloud-storage.local/file');
                    } else {
                      //  echo "Неверный пароль";
                    $data['password_err'] = "Неверный пароль";
                    $this->view('login', $data);
                    }
                } else {
                    // echo "Пользователь с таким email не существует";
                    $data['email_err'] = 'Пользователь с таким email не существует';
                    $this->view('login', $data);
                }
            }
        }
        $data['empty_err'] = 'Заполните поля';
        $this->view('login', $data);
    }

    public function loginForm (){
        $data = [
            'email' => '',
            'password' => '',
            'password_err' => '',
            'email_err' => '',
            'empty_err' => ''
        ];
        $this->view('login', $data);
    }

    public function logout()
    {
      //  var_dump($_SESSION);
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_role']);
            session_destroy();
            header('Location: http://cloud-storage.local/');
        } else {
            echo 'Вы не авторизованы';
        }
    }

    public function list()
    {

        $users = $this->userModel->findAllUsers();
        if ($users) {
            $data['users'] = $users;
            $this->view('usersList', $data);
          // echo json_encode($users);
        }
    }

    public function show(int $id)
    {
        $user = $this->userModel->findOneUser($id);
     //   var_dump($user);
        if ($user) {
            $this->view('user', $user);
        }
    }

    public function register()
    {
        $data = [
            'email' => '',
            'email_err' => '',
            'empty_err' => ''
        ];
        $this->view('register', $data);
    }

    // register
    public function add()
    {
        $data = [
            'email' => '',
            'email_err' => '',
            'empty_err' => ''
        ];
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                ];
                $user = $this->userModel->findUserByEmail($data['email']);
                if ($user) {
                    $data['email_err'] = "Пользователь с такой почтой уже существует";
                    $this->view('register', $data);
                    return;
                }
                $this->userModel->addUser($data);
                $user = $this->userModel->findUserByEmail($data['email']);
                $this->createUserSession($user);
                header('Location: http://cloud-storage.local/');
            } else {
                $data['empty_err'] = "Заполните поля";
                $this->view('register', $data);
            }
        }
        $data['empty_err'] = 'Заполните поля';
        $this->view('register', $data);
    }

    public function updateForm(){
        if(isset($_SESSION['user_email'])) {
            $data = [
                'email' => $_SESSION['user_email'],
            ];
            $this->view('update', $data);
        }
    }

    public function update(int $id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
       // var_dump($_PUT);
        if (!empty(trim($_PUT['email']) && !empty(trim($_PUT['password'])))) {
            $data = [
                'email' => trim($_PUT['email']),
               // password_hash(trim($_POST['password']), PASSWORD_DEFAULT)
                'password' => password_hash(trim($_PUT['password']), PASSWORD_DEFAULT)
            ];
            $this->userModel->updateUser($id, $data);
            header("Location: http://cloud-storage.local/users/$id");
        } else {
            echo "Заполните поля";
        }
    }

    public function delete(int $id)
    {
        $this->userModel->deleteUser($id);
    }

    function sanitize($str)
    {
        return trim(htmlspecialchars($str));
    }
}

