<h2>Active goals</h2>
<?php foreach ($goalsActive as $goal): ?>
<p><b>Task: </b><?=$goal['task']?> <b>Goal: </b> <?=$goal['goal']?>  <b>Completed: </b>
    <?=$goal['completionPercentage']?>% <b>Days left: </b> <?=$goal['daysLeft']?></p>
<?php endforeach; ?>

<h2>Completed goals</h2>
<?php foreach ($goalsCompleted as $goal): ?>
    <p><b>Task: </b><?=$goal['task']?> <b>Goal: </b> <?=$goal['goal']?></p>
<?php endforeach; ?>

<h2>Failed goals</h2>
<?php foreach ($goalsFailed as $goal): ?>
    <p><b>Task: </b><?=$goal['task']?> <b>Goal: </b> <?=$goal['goal']?>  <b>Completed percentage: </b>
        <?=$goal['completionPercentage']?>%</p>
<?php endforeach; ?>