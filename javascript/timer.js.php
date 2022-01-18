console.log(document.cookie);
var counter = 0;
var pomodoroDuration = document.getElementById('pomodoroDuration').value;
var shortBreakDuration = document.getElementById('breakDuration').value;
var longBreakDuration = document.getElementById('longBreakDuration').value;
var pomodorosUntilBreak = document.getElementById('pomodorosUntilBreak').value;
var breakAuto = document.getElementById('breakAuto').value;
var pomodoroAuto = document.getElementById('pomodoroAuto').value;
var selectedTask = document.getElementById('selectedTask').value;
var countOfPomodoro = 0;
var currentDirectory = '<?= __DIR__ ?>';



//saves information on forced reload.
window.onbeforeunload = function()
{
    if (getCookie('status') == 'running'){
        document.cookie = 'counter=' + counter;
    }
    document.cookie = 'countOfPomodoro=' + countOfPomodoro;
};
//sets value of countOfPomodoro if force reload was done.
if(getCookie('countOfPomodoro')){
    countOfPomodoro = parseInt(getCookie('countOfPomodoro'));
}

// controls preferences visibility
document.getElementById('updateSettings').style.display = 'none';
document.getElementById('preferences').onclick = function () {
    if(document.getElementById('updateSettings').style.display == 'none'){
        document.getElementById('updateSettings').style.display = 'block';
    }else {
        document.getElementById('updateSettings').style.display = 'none';
    }
}
document.getElementById('addTaskForm').style.display = 'none';
document.getElementById('addTask').onclick = function () {
    if(document.getElementById('addTaskForm').style.display == 'none'){
        document.getElementById('addTaskForm').style.display = 'block';
    }else {
        document.getElementById('addTaskForm').style.display = 'none';
    }
}
var loc = window.location.pathname;
console.log(loc);
//TODO changing pomodoro count to lower then current causes errors;
//TODO change terminate button appearance and make it visible all the time
//TODO review login problem with buttons display
//TODO add error message when already existatnt task been added
//this function loads on first load or is called on command
function template(){
    console.log('here1');
    //hides pause and terminate button when function is not running
    displayOnMultipleIds("resume_pause end_session", 'none');
    //Sets timer when force reload or change of preferences occur during countdown process.
    if (getCookie('status') == 'running') {
        counter = getCookie('counter');
        templateStatusRunning('pomodoro', pomodoroDuration, 'Done!', 'isPomodoro=true');
        templateStatusRunning('shortBreak', shortBreakDuration, 'Break ended!', 'isPomodoro=false');
        templateStatusRunning('longBreak', longBreakDuration, 'Long break ended!', 'isPomodoro=false');
        document.getElementById('breakStarter').style.display = "none";
        document.getElementById('timerStarter').style.display = "none";
    }
    //determining visibility of "Start Pomodoro" and "Start Break"
    else {
        console.log('here2');
        if (breakAuto == 1 && pomodoroAuto == 1) {
            document.getElementById('breakStarter').style.display = "none";
            document.getElementById('timerStarter').style.display = "none";
        } else if (breakAuto == 0 && pomodoroAuto == 0) {
            console.log('im here2');
            if (getCookie('isPomodoro') == 'true' && getCookie('status') == 'ended') {
                document.getElementById('breakStarter').style.display = "block";
                document.getElementById('timerStarter').style.display = "none";
            } else {
                document.getElementById('breakStarter').style.display = "none";
                document.getElementById('timerStarter').style.display = "block";
            }
        } else if (breakAuto == 1 && pomodoroAuto == 0) {
            console.log('here3');
            document.getElementById('breakStarter').style.display = 'none';
            if (getCookie('isPomodoro') == 'false'|| !getCookie('isPomodoro')  && getCookie('status') == 'ended' || !getCookie('status')) {
                console.log('im here');
                document.getElementById('timerStarter').style.display = "block";
            } else {
                document.getElementById('timerStarter').style.display = "none";
            }
        } else if (breakAuto == 0 && pomodoroAuto == 1) {
            document.getElementById('timerStarter').style.display = 'none';
            if (getCookie('isPomodoro') == 'false' && getCookie('status') == 'ended') {
                document.getElementById('breakStarter').style.display = "block";
            } else {
                document.getElementById('breakStarter').style.display = "none";
            }
        }
        //Setting time on regular conditions
        if (countOfPomodoro >= pomodorosUntilBreak){
            countOfPomodoro = 0;
            setTimerBreak(longBreakDuration, 'breakStarter', 'none', 'long break ended', "isPomodoro=false", 'longBreak');
        }else if(getCookie('isPomodoro') == 'true' && getCookie('status') == 'ended'){
            setTimerBreak(shortBreakDuration, 'breakStarter', 'none', 'Break Ended', "isPomodoro=false", 'shortBreak');
        }else {
            setTimerPomodoro(pomodoroDuration, 'timerStarter', 'none', 'Done!', "isPomodoro=true", 'pomodoro');
        }
    }
    //Setting selected task to default if not specified.
    if (!getCookie('selectedTask')){
        document.cookie = "selectedTask=none";
    }
    document.getElementById('task').innerHTML ="You are working on " +  getCookie('selectedTask') + " task";
    document.getElementById('timerStarter').innerHTML ="Start pomodoro Nr" +  (countOfPomodoro + 1);
}
//gets cookie value by name
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
//pomodoro timer
function startTimer(seconds, container, oncomplete, isPomodoro, type) {
    document.cookie = 'typeOfTimer=' + type;
    document.cookie = 'status=running';
    document.cookie = isPomodoro;
    console.log(document.cookie);
    displayOnMultipleIds("resume_pause end_session", 'block');
    var startTime, timer, obj, ms = seconds * 1000,
        display = document.getElementById(container);
    obj = {};
    obj.resume = function () {
        startTime = new Date().getTime();
        timer = setInterval(obj.step, 250);
    };
    obj.pause = function () {
        ms = obj.step();
        clearInterval(timer);
    };
    obj.step = function () {
        var now = Math.max(0, ms - (new Date().getTime() - startTime)),
            m = Math.floor(now / 60000), s = Math.floor(now / 1000) % 60;
        s = (s < 10 ? "0" : "") + s;
        display.innerHTML = m + ":" + s;
        if (now == 0) {
            document.cookie = 'status=ended';
            clearInterval(timer);
            console.log(document.cookie);
            obj.resume = function () {
            };
            if (oncomplete) oncomplete();
            if (getCookie('isPomodoro') == 'true') {
                update()
            }
            counter = 0;
            template()
        }
        counter++;
        return now;
    };
    obj.resume();
    return obj;
}
//pauses timer and changes resume_pause icon onClick function to resume
function pause(){
    timer.pause();
    document.getElementById( "resume_pause" ).setAttribute( "onClick", "javascript: resume();" );

}
//resumes timer and changes resume_pause icon onClick function to pause
function resume(){
    timer.resume();
    document.getElementById( "resume_pause" ).setAttribute( "onClick", "javascript: pause();" );
}
//changes icon of resume_pause on click
function changeIcon() {
    document.getElementById("icon").classList.toggle("fa-play");
}
//changes selected task when different is selected (onChange)
function updateSelectedTask(){
    document.cookie = "selectedTask=" + document.getElementById('selectedTask').value;
    document.getElementById('task').innerHTML ="You are working on " +  getCookie('selectedTask') + " task";
}

//updates timeTrack table (using ajax)
function update(){
    document.cookie = 'status=ended';
    countOfPomodoro++;
    $.post("/ajax/updateAjax.php", {
        secondsToAdd: Math.round(counter / 4),
        countOfPomodoro: countOfPomodoro },
    function (data){
        $("#test").html(data);
    })
}
//on click on terminate icon end session and resets all values for new session
function endSession(){
    update();
    document.cookie = "isPomodoro=false";
    countOfPomodoro = 0;
    window.location.reload();
}
//function for setting timer used for break
function setTimerBreak(minutes, clickID, displayMethod, alertMessage, isPomodoro, type){
    document.getElementById('minutes').innerHTML = minutes / 60 + ":00";
    //starts timer on click
    if (breakAuto == 0){
        document.getElementById(clickID).onclick = function () {
            document.getElementById(clickID).style.display = displayMethod;
            timer = startTimer(minutes, "minutes", function() {alert(alertMessage);}, isPomodoro, type);
        }
    }
    //starts timer automatically
    else {
        timer = startTimer(minutes, "minutes", function() {alert(alertMessage);}, isPomodoro, type);
    }
}
//function for setting timer used for pomodoro
function setTimerPomodoro(minutes, clickID, displayMethod, alertMessage, isPomodoro, type){
    document.getElementById('minutes').innerHTML = minutes / 60 + ":00";
    if (pomodoroAuto == 0){
        document.getElementById(clickID).onclick = function () {
            document.getElementById(clickID).style.display = displayMethod;
            timer = startTimer(minutes, "minutes", function() {alert(alertMessage);}, isPomodoro, type);
        }
    }else {
        timer = startTimer(minutes, "minutes", function() {alert(alertMessage);}, isPomodoro, type);
    }
}
//function for changing display of multiple id's
// example call: ("idNrOne idNrTwo idNrThree", "none")
function displayOnMultipleIds(ids, display) {
    var idList = ids.split(" ");
    for (var i = 0; i < idList.length; i++) {
        document.getElementById(idList[i]).style.display = display;
    }
}
//Function used to start timer when changes to preferences or reload was made during countdown
function templateStatusRunning(typeOfTimer, typeDuration, alertMessage, isPomodoro){
    if (getCookie('typeOfTimer') == typeOfTimer) {
        if (counter / 4 >= typeDuration) {
            document.cookie = 'status=ended';
            update();
            template()
        }
        else {
            timer = startTimer(typeDuration - (counter / 4), 'minutes', function () {
                alert(alertMessage);
            }, isPomodoro, typeOfTimer);
        }
    }
}
//ajax function used for changing setting
$(document).ready(function (){
    $("#saveSettings").click(function () {
        //changes current preferences to new ones on timer.js.php
        var pomodoroDurationSet = $("#pomodoroDuration").val();
        var breakDurationSet = $("#breakDuration").val();
        var longBreakDurationSet = $("#longBreakDuration").val();
        var pomodorosUntilBreakSet = $("#pomodorosUntilBreak").val();
        var breakAutoSet = $("#breakAuto").val();
        var pomodoroAutoSet = $("#pomodoroAuto").val();
        pomodoroDuration = pomodoroDurationSet;
        shortBreakDuration = breakDurationSet;
        longBreakDuration = longBreakDurationSet;
        pomodorosUntilBreak = pomodorosUntilBreakSet;
        breakAuto = breakAutoSet;
        pomodoroAuto = pomodoroAutoSet;
        //sends https post request  to updateSetting.php for updating preferences in database
        $.post("/ajax/updateAjax.php",{
            pomodoroDuration: pomodoroDurationSet,
            breakDuration: breakDurationSet,
            longBreakDuration: longBreakDurationSet,
            pomodoroUntilBreak: pomodorosUntilBreakSet,
            breakAuto: breakAutoSet,
            pomodoroAuto: pomodoroAutoSet
        },
            //writes output of updateSetting.php (optional, currently echo's sql to check for mistakes)
            function (data) {
            $("#test").html(data);
        });
        //reloads page if settings were changed when timer was running to adjust timer to changed preferences.
        if (getCookie('status') == 'running'){
            window.location.reload()
        }else {
            //updates timer based on changed preferences without refreshing if count down weren't running
            template();
        }
    });
});
$(document).ready(function (){
    $('#saveNewTask').click(function (){
        var taskToAdd = $("#userTaskInput").val();
        $.post("/ajax/updateAjax.php", {
            taskToAdd: taskToAdd
        });
        setTimeout(function(){$('#tasksFromDb').load('/ajax/loadTasksFromDbAjax.php')}, 50);

        document.getElementById('addTaskForm').style.display = 'none';
        document.getElementById('userTaskInput').value = '';
    });
});
//$(document).ready(function (){
//    $('#saveNewTask').click(function (){
//        $('#tasksFromDb').load('/ajax/loadTasksFromDbAjax.php');
//    })
//})

