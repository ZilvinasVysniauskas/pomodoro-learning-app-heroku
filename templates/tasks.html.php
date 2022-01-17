<?php
$idDefiner1 = 1;
$idDefiner2 = 1;
//TODO add goals statistics functionality
//TODO show existing goal if is set
$noGoalsTaskArray = [];
$withGoalsTaskArray = [];
foreach ($tasks as $task) {
    if ($task['goal_time'] === null) {
        $noGoalsTaskArray[] = $task;
    } else {
        $withGoalsTaskArray[] = $task;
    }
}
?>
<h3>Tasks Without Goals</h3>
<?php foreach ($noGoalsTaskArray as $task): ?>
    <form action="" method="post">
        <input type="text" name="taskToDelete" readonly value="<?=$task['task']?>">
        <input type="submit"  name="deleteTaskFromDb" value="delete"></form>
    <?php
    $setGoalId = 'setGoal' . $idDefiner1;
    $goalTableId = 'goalTable' . $idDefiner1;
    $idDefiner1++;
    ?>
    <button id="<?= $setGoalId ?>"> set goal</button>
    <div id="<?=$goalTableId?>">
        <form method="post" action="">
            <input type="text"  name="taskToSetGoalOnTo" hidden value="<?= $task['task'] ?>">
            <label for="setGoals" >Time goal</label>
            <input type="number" name="goalTime" required>
            <label for="goalDeadline" >Set deadline</label>
            <input type="date" name="goalDeadline" required>
            <input type="submit" value="save" name="setGoal">
        </form>
    </div>
    <script>
        document.getElementById("<?= $goalTableId ?>").style.display = "none";
        document.getElementById("<?= $setGoalId ?>").onclick = function () {
            if (document.getElementById("<?= $goalTableId ?>").style.display === "none"){
                document.getElementById("<?= $goalTableId ?>").style.display = "block";
            }
            else {
                document.getElementById("<?= $goalTableId ?>").style.display = "none";
            }
        }
    </script>
<?php endforeach; ?>
<br><br>
<h3>Tasks With Goals</h3>
<?php foreach ($withGoalsTaskArray as $task):?>
    <?php
        $editGoalButtonId = 'editGoalButton' . $idDefiner2;
        $editGoalForm = 'editGoalForm' . $idDefiner2;
        $idDefiner2++;
    ?>

    <p><b> Task: </b> <?= $task['task']?> <b> Goal time: </b> <?=$task['goal_time']?> <b> Start date: </b>
    <?=$task['goal_start_date']?> <b> Deadline: </b> <?=$task['goal_deadline']?></p>
    <button id="<?=$editGoalButtonId?>">edit Goal</button><br><br>
    <form action="" method="post" id="<?=$editGoalForm?>">
        <input type="text"  name="taskToSetGoalOnTo" hidden value="<?=$task['task']?>">
        <label for="goalTime">Set goal time</label>
        <input type="text" name="goalTime" value="<?=$task['goal_time']?>">
        <label for="goalTime">Set goal deadline</label>  
        <input type="date" name="goalDeadline" value="<?=$task['goal_deadline']?>">
        <input type="submit" name="setGoal" value="save changes">    
        <input type="submit" name="deleteGoal" value="delete goal">    
    </form>
    <script>
        document.getElementById("<?=$editGoalForm?>").style.display = "none";
        document.getElementById("<?=$editGoalButtonId?>").onclick = function () {
            if (document.getElementById("<?=$editGoalForm?>").style.display === "none"){
                document.getElementById("<?=$editGoalForm?>").style.display = "block";
            }
            else {
                document.getElementById("<?=$editGoalForm?>").style.display = "none";
            }
        }
    </script>
<?php endforeach; ?>