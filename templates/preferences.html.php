<div class="container">
    <form method="post" name="updateSettings">
        <label for="pomodoroDuration">Pomodoro Duration</label>
        <div><input type="text" name="pomodoroDuration" value=<?=$pomodoroDuration?>></div>
        <label for="breakDuration">Break Duration</label>
        <div><input type="text" name="breakDuration" value=<?=$breakDuration?>></div>
        <label for="longBreakDuration">Long Break Duration</label>
        <div><input type="text" name="longBreakDuration" value=<?=$longBreakDuration?>></div>
        <label for="pomodorosUntilBreak">Pomodoros until long break</label>
        <div><input type="text" name="pomodoroUntilBreak" value=<?=$pomodorosUntilBreak?>></div>
        <label for="breakAuto">Start beak automatically</label>
        <div><input type="text" name="breakAuto" value=<?=$breakAuto?>></div>
        <label for="pomodoroAuto">Start pomodoro automatically</label>
        <div><input type="text" name="pomodoroAuto" value=<?=$pomodoroAuto?>></div>
        <div><input type="submit"></div>
    </form>
</div>