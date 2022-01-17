<?php
include __DIR__  . '/../includes/DatabaseConnection.php';
?>
<div class="container">
    <h1 class="text-center">Pomodoro App</h1>
    <div class="row ">
        <div class="container">
            <div class="row d-flex justify-content-between">
                <div class="col-4">
                    <!--TODO fix task selecter, on changing selection to first option, task none disappears-->
                    <label for="task">Choose task</label>
                    <div id="tasksFromDb">
                        <?php
                            $userTasksTable = new \Ninja\DatabaseTable($pdo, 'userTasks' . $_SESSION['tableId'], 'email');
                            $tasksAjax = $userTasksTable->selectDataFromDb();
                            echo '<select id="selectedTask" onchange="updateSelectedTask()">';
                            echo '<option value="unassigned">unassigned</option>';
                            $taskArr = [];
                            foreach ($tasksAjax as $task){
                                $taskArr[] = $task['task'];
                                $line = '<option value="' . $task['task'] . '"' . '>task: ' . $task['task'] . '</option>';
                                echo $line;
                            }

                            echo '</select>';
                        ?>
                    </div>
                    <p id="task"></p>
                </div>
                <div class="col-3">
                    <button id="addTask">add a new task</button>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <button id="preferences">preferences</button>
                </div>
            </div>
            <div class="container" id="updateSettings">
                    <label for="pomodoroDuration">Pomodoro Duration</label>
                    <div><input id="pomodoroDuration" type="text" name="pomodoroDuration" value=<?=$pomodoroDuration?>></div>
                    <label for="breakDuration">Break Duration</label>
                    <div><input id="breakDuration" type="text" name="breakDuration" value=<?=$breakDuration?>></div>
                    <label for="longBreakDuration">Long Break Duration</label>
                    <div><input id="longBreakDuration" type="text" name="longBreakDuration" value=<?=$longBreakDuration?>></div>
                    <label for="pomodorosUntilBreak">Pomodoros until long break</label>
                    <div><input id="pomodorosUntilBreak" type="text" name="pomodoroUntilBreak" value=<?=$pomodorosUntilBreak?>></div>
                    <label for="breakAuto">Start beak automatically</label>
                    <div><input id="breakAuto" type="text" name="breakAuto" value=<?=$breakAuto?>></div>
                    <label for="pomodoroAuto">Start pomodoro automatically</label>
                    <div><input id="pomodoroAuto" type="text" name="pomodoroAuto" value=<?=$pomodoroAuto?>></div>
                    <div><input type="submit" value="save" id="saveSettings"></div>
            </div>
            <div class="container" id="addTaskForm">
                <label for="task">New Task</label>
                <input id="userTaskInput" name="task" type="text">
                <input type="submit" id="saveNewTask">
            </div>
        </div>
        <p id="test"></p>
        <form id="timeDataForm" name="timeDataForm" method="post" action="http://localhost:8000/">
            <input type="hidden" name="secondsToAdd" id="secondsToAdd" value="">
            <input type="hidden" name="countOfPomodoro" id="countOfPomodoro" value="">
            <input type="hidden" name="isCompleted" id="isCompleted" value="">
        </form>
    </div>
    <p id="session"></p>
    <h1 id="timerStarter" class="text-center blue">Start pomodoro</h1>
    <h1 id="breakStarter" class="text-center blue">Start break</h1>
    <div class="timer">
        <div class="row">
            <h4 id="minutes"></h4>
        </div>
    </div>
    <div id="buttons" class="container d-flex justify-content-center align-items-center">
        <button class="btn" id="resume_pause" onclick="pause()"><i id="icon" onclick="changeIcon()" class="fas fa-pause fa-2x"></i></button>
        <button class="btn" id="end_session" onclick="endSession()"><i class="fas fa-stop fa-2x"></i></button>
    </div>
</div>
<!--style css-->
<style>
    <?php
        include __DIR__ . "/../style/style.css.php";
    ?>
</style>
<!--end style css-->
<!--javascript-->
<script>
    <?php
        include __DIR__ . "/../javascript/timer.js.php";
    ?>
</script>

