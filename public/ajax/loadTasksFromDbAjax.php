<?php
session_start();
include __DIR__ . '/../../includes/autoload.php';
include __DIR__  . '/../../includes/DatabaseConnection.php';
$userTasksTable = new \Ninja\DatabaseTable($pdo,  'userTasks'. $_SESSION['tableId'], 'email');
$tasksAjax = $userTasksTable->selectDataFromDb();
echo '<select id="selectedTask" onchange="updateSelectedTask()">';
echo '<option value="unassigned">unassigned</option>';
foreach ($tasksAjax as $task){
    $line = '<option value="' . $task['task'] . '"' . '>task: ' . $task['task'] . '</option>';
    echo $line;
}
echo '</select>';
?>