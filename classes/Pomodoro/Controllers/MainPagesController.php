<?php

namespace Pomodoro\Controllers;

use DatePeriod;
use Ninja\DatabaseTable as Db;

class MainPagesController
{
    private $settingsTable;
    private $userStatisticsTable;
    private $userTasksTable;
    public function __construct(Db $userStatisticsTable, Db $userTasksTable = null, $settingsTable = null)
    {
        $this->settingsTable = $settingsTable;
        $this->userStatisticsTable = $userStatisticsTable;
        $this->userTasksTable = $userTasksTable;
    }
    private function getTasksArray(){
        $dataTask = $this->userTasksTable->selectDataFromDb();
        $taskArr = [];
        foreach ($dataTask as $task){
            $taskArr[] = $task['task'];
        }
        return $taskArr;
    }
    public function home(){
        if (count($this->settingsTable->findById($_SESSION['username'])) == 0){
            $data = ['email' => $_SESSION['username'], 'pomodoro_duration' => 25, 'break_duration' => 5, 'long_break_duration' => 15,
                'pomodoros_until_break' => 4, 'break_auto_start' => 1, 'pomodoro_auto_start' => 0];
            $this->settingsTable->insertIntoDb($data);
        }
        $dataSettings = $this->settingsTable->findById($_SESSION['username'])[0];
        $dataTask = $this->userStatisticsTable->selectDataFromDb();
        return ['title' => 'pomodoro',
            'templates' => [
                    'output' => ['template' => 'timer.html.php', 'variables' => ['pomodoroDuration' => $dataSettings['pomodoro_duration'],
                    'breakDuration' => $dataSettings['break_duration'], 'longBreakDuration' => $dataSettings['long_break_duration'],
                    'pomodorosUntilBreak' => $dataSettings['pomodoros_until_break'], 'breakAuto' => $dataSettings['break_auto_start'],
                        'pomodoroAuto' => $dataSettings['pomodoro_auto_start'], 'countOfPomodoro' => 0, 'tasks' => $this->getTasksArray()]],
                'logged' => ['template' => 'logged.html.php']
            ]
        ];
    }
    public function getReports(){
        $date = new \DateTime();
        $resultDay = $this->userStatisticsTable->selectDataFromDb(['date' => $date->format('Y-m-d')]);
        $dayPomodoroTime = 0;
        if(count($resultDay) > 0){
            foreach ($resultDay as $taskTime){
                $dayPomodoroTime += $taskTime['time'];
            }
        }
        return ['title' => 'reports',
            'templates' =>[
                'output' =>['template' => 'reports.html.php', 'variables' => ['dayPomodoroTime' => $dayPomodoroTime, 'tasks'=> $this->getTasksArray()]],
                'logged' =>['template' => 'logged.html.php']
            ]];
    }
    public function getTasks() {

        return ['title' => 'tasks',
            'templates' =>[
                'output' =>['template' => 'tasks.html.php', 'variables' => ['tasks'=> $this->userTasksTable->selectDataFromDb()]],
                'logged' =>['template' => 'logged.html.php']
            ]];
    }
    public function editTask() {
        $now = (new \DateTime())->format('Y-m-d');
        if (isset($_POST['deleteTaskFromDb'])) {
            $delete = ['task' => $_POST['taskToDelete']];
            $this->userTasksTable->deleteFromDb($delete);
        }
        if (isset($_POST['setGoal'])){
            $update = ['set' => ['goal_time' => $_POST['goalTime'], 'goal_start_date' => $now,
                'goal_deadline' => $_POST['goalDeadline'], 'is_goal_set' => 1],
                'conditions' => ['task' => $_POST['taskToSetGoalOnTo']]];
               $this->userTasksTable->updateValuesInDb($update, 'change');
        }
        if (isset($_POST['deleteGoal'])){
            $update = ['set' => ['goal_time' => 'null', 'goal_start_date' => 'null',  'goal_deadline' => 'null', 'is_goal_set' => 0],
                'conditions' => ['task' => $_POST['taskToSetGoalOnTo']]];
            $this->userTasksTable->updateValuesInDb($update, 'change');
        }
        return ['title' => 'tasks',
            'templates' =>[
                'output' =>['template' => 'tasks.html.php', 'variables' => ['tasks'=> $this->userTasksTable->selectDataFromDb()]],
                'logged' =>['template' => 'logged.html.php']]];
    }
    public function displayGoalStatistics(){
        $condition = ['is_goal_set' => 1];
        $goals = $this->userTasksTable->selectDataFromDb($condition);
        $now = new \DateTime();
        $goalsCompletedArr = [];
        $goalsActiveArr = [];
        $goalsFailedArr = [];
        foreach ($goals as $goal){
            $dateOne = new \DateTime($goal['goal_start_date']);
            $dateTwo = new \DateTime($goal['goal_deadline']);
            $timeGoal = $goal['goal_time'];
            $task =  $goal['task'];
            if ($dateTwo > $now){
                $daysLeft = $now->diff($dateTwo)->d;
                $dateTwo = $now;
            }
            $timeSpent = $this->getTimeSpentFormDateOneToDateTwo($dateOne->format('Y-m-d'),
                $dateTwo->format('Y-m-d'), $task);
            $completionPercentage = round(($timeSpent / $timeGoal) * 100);
            if ($timeSpent >= $timeGoal){
                $goalsCompletedArr[] = [
                    'task' => $task,
                    'goal' => $timeGoal
                ];
            } elseif($dateTwo >= $now){
                $goalsActiveArr[] = [
                    'task' => $task,
                    'goal' => $timeGoal,
                    'completionPercentage' => $completionPercentage,
                    'daysLeft' => $daysLeft
                ];
            }else{
                $goalsFailedArr[] = [
                    'task' => $task,
                    'goal' => $timeGoal,
                    'completionPercentage' => $timeSpent
                ];
            }
        }
        return ['title' => 'goals',
            'templates' =>[
                'output' =>['template' => 'goals.html.php', 'variables' => ['goalsCompleted'=> $goalsCompletedArr,
                    'goalsActive' => $goalsActiveArr, 'goalsFailed' => $goalsFailedArr]],
                'logged' =>['template' => 'logged.html.php']]];
    }
    public function getTimeSpentFormDateOneToDateTwo($dateOne, $dateTwo, $task = null){
        $datePeriod = new DatePeriod(new \DateTime($dateOne), new \DateInterval('P1D'),
            (new \DateTime($dateTwo))->add(\DateInterval::createFromDateString('1 day')));
        $finalTime = 0;
        foreach ($datePeriod as $date) {
            if ($task === null) {
                $condition = ['date' => $date->format('Y-m-d')];
            } else {
                $condition = ['date' => $date->format('Y-m-d'), 'task' => $task];
            }
            $dayResult = $this->userStatisticsTable->selectDataFromDb($condition);
            if (count($dayResult) > 0) {
                foreach ($dayResult as $taskResult) {
                    $finalTime += $taskResult['time'];
                }
            }
        }
        return $finalTime;
    }
}