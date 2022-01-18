<?php

namespace Pomodoro\Controllers;
require __DIR__ . '/../../../vendor/autoload.php';

use Ninja\Authentication as Au;
use ZxcvbnPhp\Zxcvbn;

class loginController
{
    private $usersTable;
    private $authentication;
    public function __construct(Au $authentication, $usersTable)
    {
        include __DIR__ . '/../../../includes/DatabaseConnection.php';
        $this->usersTable = $usersTable;
        $this->authentication = $authentication;
    }
    public function login(){
        $errors = [];
        if(!empty($_POST)){
            $valid = true;
            if (empty($_POST['email'])){
                $valid = false;
                $errors[] = 'email can not be empty';
            }
            if (empty($_POST['password'])){
                $valid = false;
                $errors[] = 'password can not be empty';
            }
            if($valid){
                if (count($this->usersTable->findById($_POST['email'])) == 0){
                    $valid = false;
                    $errors[] = 'no such email';
                }
                else if(!password_verify($_POST['password'], $this->usersTable->findById($_POST['email'])[0]['password'])){
                    echo $_POST['password'];
                    $valid = false;
                    $errors[] = 'wrong password';
                }
            }
            if ($valid){
                if (password_needs_rehash($this->usersTable->findById($_POST['email'])[0]['password'], PASSWORD_BCRYPT, ['cost'=>12])){
                    $newHash = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost'=>12]);
                    $data = ['set' => ['password' => $newHash], 'conditions' => ['email' => $_POST['email']]];
                    $this->usersTable->updateValuesInDb($data, 'update');
                }
                $user = $this->usersTable->findById($_POST['email'])[0];
                $this->authentication->logUser($user['email'], $user['password'], $user['tableId']);
                header("Refresh:0");
            }
        }

        return ['title' => 'login',
            'templates' => ['output' => ['template' => 'login.html.php', 'variables' => ['errors' => $errors]]]];
    }
    public function register(){
        $errors = [];
        $valid = true;
        if (!empty($_POST)){
            $valid = true;
            if (empty($_POST['email'])){
                $valid = false;
                $errors[] = 'email can not be empty';
            }
            elseif (count($this->usersTable->findById($_POST['email'])) > 0){
                $errors[] = 'email already exists';
                $valid = false;
            }
            elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $valid = false;
                $errors[] = 'invalid email';
            }
            if (empty($_POST['password'])){
                $valid = false;
                $errors[] = 'password can not be empty';
            }
            elseif ($_POST['password'] !== $_POST['password_repeat']){
                $valid = false;
                $errors[] = 'passwords do not match';
            }
            if ($valid){
                $zxcvbn = new Zxcvbn();
                $pass = $zxcvbn->passwordStrength($_POST['password']);
                if($pass['score'] < 4) {
                    $errors[] = 'password too weak';
                    $errors[] = $pass['feedback']['warning'];
                }
                else{
                    $uniqTableId = str_replace('.', '_', uniqid('', true));
                    $userStatisticsTable = 'userStatistics' . $uniqTableId;
                    $userTasksTable = 'userTasks' . $uniqTableId;
                    $data = ['email' => $_POST['email'], 'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), 'tableId' => $uniqTableId];
                    $this->usersTable->insertIntoDb($data);
                    $this->authentication->logUser($_POST['email'], $this->usersTable->findById($_POST['email'])[0]['password'], $uniqTableId);

                    include __DIR__ . '/../../../includes/DatabaseConnection.php';
                    $sql1 = "CREATE TABLE " . $userStatisticsTable .  " (`task` VARCHAR(45) NOT NULL,`time` INT NOT NULL,`date` DATE NOT NULL,PRIMARY KEY (`task`));";
                    $sql2 = "CREATE TABLE " . $userTasksTable .  " (`task` VARCHAR(45), `goal_time` int, goal_deadline date,  PRIMARY KEY (`task`));";
                    $stmt1 = $pdo->prepare($sql1);
                    $stmt2 = $pdo->prepare($sql2);
                    $stmt1->execute();
                    $stmt2->execute();
                    header('location: https://trying-to-make-shit-work.herokuapp.com/');
                }
            }
        }
        return ['title' => 'register',
            'templates' => ['output' => ['template' => 'register.html.php', 'variables' => ['errors' => $errors]]]];
    }
    public function logout(){
        $this->authentication->logout();
    }

}