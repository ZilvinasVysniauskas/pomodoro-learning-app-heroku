<?php

namespace Ninja;

class Authentication
{
    private $usersTable;
    private $usernameColumn;
    private $passwordColumn;
    public function __construct(\Ninja\DatabaseTable $usersTable, $usernameColumn, $passwordColumn)
    {
        session_start();
        $this->usersTable = $usersTable;
        $this->usernameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn;
    }
    public function logUser($username, $password, $tableId)
    {
        session_regenerate_id();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['tableId'] = $tableId;
    }
    public function logout(){
        session_destroy();
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'];
        header('location: '. $actual_link);
    }
    public function isLogged(){
        if (!isset($_SESSION['username'])){
             return false;
        }
        if(count($this->usersTable->findById($_SESSION['username'])) === 0) {
            return false;
        }
        if ($_SESSION['password'] !== ($this->usersTable->findById($_SESSION['username'])[0][$this->passwordColumn])){
            return false;
        }
        return true;
    }
}