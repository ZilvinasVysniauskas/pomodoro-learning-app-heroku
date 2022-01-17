<?php

include __DIR__ . "/../../includes/DatabaseConnection.php";
include __DIR__ . "/../../includes/autoload.php";
session_start();
$userStatisticsTable = new \Ninja\DatabaseTable($pdo, 'userStatistics' . $_SESSION['tableId'], 'task');
$mainPagesController = new \Pomodoro\Controllers\MainPagesController($userStatisticsTable);
$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
$task = $_POST['task'];

if ($tas = 'allTasks'){
    echo 'Time spent: ' . $mainPagesController->getTimeSpentFormDateOneToDateTwo($dateStart, $dateEnd);
}
else{
    echo 'Time spent on '. $task .':'. $mainPagesController->getTimeSpentFormDateOneToDateTwo($dateStart, $dateEnd, $task);
}

//TODO use DateInterval