<?php
include __DIR__ . "/../../includes/DatabaseConnection.php";
include __DIR__ . "/../../includes/autoload.php";

use Ninja\DatabaseTable;
session_start();
$settingTable = new DatabaseTable($pdo, 'settings', 'email');
$userTable = new DatabaseTable($pdo, 'user', 'email');
$selectedTask = $_COOKIE['selectedTask'];

$userStatisticsTable = new \Ninja\DatabaseTable($pdo,  'userStatistics' . $_SESSION['tableId'], 'task');
$userTasksTable = new \Ninja\DatabaseTable($pdo,  'userTasks' . $_SESSION['tableId'], 'task');
$Date = new DateTime();
$currentDate = $Date->format('Y-m-d');

function ifTaskNotUnassigned(){
    global $selectedTask, $currentDate, $userStatisticsTable;
    if ($selectedTask !== 'unassigned'){
        $insert2 = ['task' => 'unassigned', 'time' => $_POST['secondsToAdd'], 'date' => $currentDate];
        $userStatisticsTable->insertIntoDb($insert2);
    }
}
if (!empty($_POST['pomodoroDuration'])){
    $data = [
        'set' => ['pomodoro_duration' => $_POST['pomodoroDuration'], 'break_duration' => $_POST['breakDuration'],
            'long_break_duration' => $_POST['longBreakDuration'], 'pomodoros_until_break' => $_POST['pomodoroUntilBreak'],
            'break_auto_start' => $_POST['breakAuto'], 'pomodoro_auto_start' => $_POST['pomodoroAuto']
        ],
        'conditions' => ['email' => $_SESSION['username']]
    ];

    $settingTable->updateValuesInDb($data, 'change');
}
if(!empty($_POST['secondsToAdd'])){
    $condition = ['task' => $selectedTask, 'date' => $currentDate];
    $taskData = $userStatisticsTable->selectDataFromDb($condition);
    if(count($taskData) === 0){
        $insert1 = ['task' => $selectedTask, 'time' => $_POST['secondsToAdd'], 'date' => $currentDate];
        $userStatisticsTable->insertIntoDb($insert1);
    }
    else{
        $update = [
            'set' => ['time' => $_POST['secondsToAdd']],
            'conditions' => ['task' => $_COOKIE['selectedTask']]
        ];
        $userStatisticsTable->updateValuesInDb($update, 'add');
    }

}
if(!empty(($_POST['taskToAdd']))){
    $taskToAdd = htmlentities($_POST['taskToAdd'], ENT_QUOTES, 'UTF-8');
    $data = ['task' => $taskToAdd, 'is_goal_set' => 0];
    $userTasksTable->insertIntoDb($data);
}