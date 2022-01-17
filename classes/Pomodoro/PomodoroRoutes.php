<?php
namespace Pomodoro;
use \Ninja\DatabaseTable as Db;
use Ninja\Authentication as Au;

use Pomodoro\Controllers\GoalsController;
use Pomodoro\Controllers\LoginController;
use Pomodoro\Controllers\MainPagesController;

class PomodoroRoutes
{
    private $settingTable;
    private $userStatistics;
    private $userTasks;
    private $userTable;
    private $authentication;
    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';
        $this->settingTable = new Db($pdo, 'settings', 'email');
        $this->userTable = new Db($pdo, 'user', 'email');
        $this->authentication = new Au($this->userTable, 'email', 'password');
    }


    public function getRoutes(){
        $LoginController = new LoginController($this->authentication, $this->userTable);

        if (!$this->authentication->isLogged()){
            $routes = [
                "" => [
                    'GET' => [
                        'controller' => $LoginController,
                        'action' => 'login'
                    ],
                    'POST' => [
                        'controller' => $LoginController,
                        'action' => 'login'
                    ]
                ],
                "register" => [
                    'GET' => [
                        'controller' => $LoginController,
                        'action' => 'register'
                    ],
                    'POST' => [
                        'controller' => $LoginController,
                        'action' => 'register'
                    ]
                ]
            ];
        }
        else {
            include __DIR__ . '/../../includes/DatabaseConnection.php';

            $this->userStatistics = new Db($pdo, 'userStatistics' . $_SESSION['tableId'], 'task');
            $this->userTasks = new Db($pdo, 'userTasks' . $_SESSION['tableId'], 'task');
            $mainPagesController = new MainPagesController($this->userStatistics, $this->userTasks, $this->settingTable);

            $routes = [
                "" => [
                    'GET' => [
                        'controller' => $mainPagesController,
                        'action' => 'home'
                    ],
                    'POST' => [
                        'controller' => $mainPagesController,
                        'action' => 'updateTimer'
                    ]
                ],
                "logout" => [
                    'GET' => [
                        'controller' => $LoginController,
                        'action' => 'logout'
                    ]
                ],
                'reports' => [
                    'GET' => [
                        'controller' => $mainPagesController,
                        'action' => 'getReports'
                    ]
                ],
                'tasks' => [
                    'GET' => [
                        'controller' => $mainPagesController,
                        'action' => 'getTasks'
                    ],
                    'POST' => [
                        'controller' => $mainPagesController,
                        'action' => 'editTask'
                    ]
                ],
                'goals' => [
                    'GET' => [
                        'controller' => $mainPagesController,
                        'action' => 'displayGoalStatistics'
                    ]
                ]
            ];
        }

        return $routes;
    }
}