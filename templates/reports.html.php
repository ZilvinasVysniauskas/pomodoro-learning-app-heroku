<h1>this is reports page</h1>
<form action="">
    <select name="timePeriod" id="timePeriod" onchange="updateSelectedTimePeriod()">
        <option value="day">Day</option>
        <option value="week">Week</option>
        <option value="month">Month</option>
    </select>
    <br>
    <div>
        <?php
        echo '<select id="selectedTask" onchange="updateSelectedTask()">';
        echo '<option value="allTasks">All Tasks</option>';
        foreach ($tasks as $task){
            $line = '<option value="' . $task . '"' . '>task: ' . $task . '</option>';
            echo $line;
        }
        echo '</select>';
        ?>
    </div>
    <div class="d-flex">
        <div class="btn" id="buttonPrevious">Previous</div>
        <input type="text" name="dateInput" id="dateInput" readonly>
        <input type="text" name="dateInputWeekEnd" id="dateInputEnd" readonly>
        <div class="btn" id="buttonNext">Next</div>
    </div>

</form>
<p>time spent today: <?=$dayPomodoroTime?></p>
<div id="statisticsDataResult"></div>


<script>
    <?php
    include __DIR__ . "/../javascript/statistics.js.php";
    ?>
</script>