var date = new Date();
let format = [{year: 'numeric'}, {month: '2-digit'}, {day: '2-digit'}];
var currentInputDate = new Date();
var currentDate = new Date();
var currentTask = 'allTasks';
var currentTimePeriod = 'day';
var currentEnd = new Date();
var tempDate = currentInputDate;

//TODO #1 do month functionality
//TODO fix functions


function join(time, format, joiner) {
    function formDate(mapPart) {
        let f = new Intl.DateTimeFormat('en', mapPart);
            return f.format(time);
        }
    return format.map(formDate).join(joiner);
}

function setInputDate(){
    document.getElementById('dateInputEnd').style.display = 'block';
    document.getElementById('dateInput').value = join(currentInputDate, format, '-');
    if (currentTimePeriod === 'month'){
        document.getElementById('dateInputEnd').value = join(currentEnd.setFullYear(currentInputDate.getFullYear(),
            currentInputDate.getMonth(), currentInputDate.getDate() + daysInCurrentMonth() - 1), format, '-');
        if (currentInputDate.getTime() + (86400000 * daysInCurrentMonth())  <= currentDate.getTime()){
            document.getElementById('buttonNext').style.display = 'block';
        }else{
            console.log('Im here');
            document.getElementById('buttonNext').style.display = ' none';
        }
    }
    else if (currentTimePeriod === 'week'){
        document.getElementById('dateInputEnd').value = join(currentEnd.setFullYear(currentInputDate.getFullYear(),
            currentInputDate.getMonth(), currentInputDate.getDate() + 6), format, '-');
        console.log(currentEnd);
        if (currentInputDate.getTime() + (86400000 * 7) < currentDate.getTime()){
            document.getElementById('buttonNext').style.display = 'block';
        }else{
            console.log('Im here2');
            document.getElementById('buttonNext').style.display = 'none';
        }
    }
    else {
        currentEnd.setFullYear(currentInputDate.getFullYear(), currentInputDate.getMonth(), currentInputDate.getDate());
        document.getElementById('dateInputEnd').style.display = 'none';
        if (currentInputDate.getTime() + 86400000  < currentDate.getTime()){
            document.getElementById('buttonNext').style.display = 'block';
        }else{
            console.log('Im here3');
            document.getElementById('buttonNext').style.display = 'none';
        }
    }

}
function loadDataFromDb(){
    $("#statisticsDataResult").load('/ajax/statisticsAjax.php', {dateStart: join(currentInputDate, format, '-'),
    dateEnd: join(currentEnd, format, '-'),task: currentTask})
    console.log(currentInputDate);
    console.log(currentEnd);
}
function updateSelectedTask(){
    currentTask = document.getElementById('selectedTask').value;
    loadDataFromDb();
}
function updateSelectedTimePeriod(){
    currentTimePeriod = document.getElementById('timePeriod').value;
    previousPeriod()
    //loadDataFromDb();
}
window.onload = function () {
    setInputDate();
    loadDataFromDb();
}
document.getElementById('buttonPrevious').onclick = function () {
    previousPeriod();
}
function daysInPreviousMonth () {
    return  new Date(currentInputDate.getFullYear(), currentInputDate.getMonth(), 0).getDate();
}
function daysInCurrentMonth (month, year) {
    return new Date(currentInputDate.getFullYear(), currentInputDate.getMonth() + 1, 0).getDate();
}
function daysInNextMonth (month, year) {
    return  new Date(currentInputDate.getFullYear(), currentInputDate.getMonth() + 2, 0).getDate();
}
function previousPeriod(){
    if(currentTimePeriod === 'day'){
        daysToSubtract = 1;
    }
    else if (currentTimePeriod === 'week'){
        if (currentInputDate.getDay() !== 1 && currentInputDate.getDay() !== 0) {
            daysToSubtract = currentInputDate.getDay() - 1;
        }
        else if (currentInputDate.getDay() === 0){
            daysToSubtract = 6;
        }
        else {
            daysToSubtract = 7;
        }
    }
    else {
        if (currentInputDate.getDate() !== 1) {
            daysToSubtract = currentInputDate.getDate() - 1;
        }
        else {
            daysToSubtract = daysInPreviousMonth();
        }
    }
    currentInputDate.setDate(currentInputDate.getDate() - daysToSubtract);
    setInputDate()
    loadDataFromDb();
}
document.getElementById('buttonNext').onclick = function () {nextPeriod()}

function nextPeriod(){
    if(currentTimePeriod === 'day'){
        daysToAdd = 1;
    }
    else if (currentTimePeriod === 'week'){
        if (currentInputDate.getDay() !== 1) {
            daysToAdd =  3 - currentInputDate.getDay();
        }
        else {
            daysToAdd = 7;
        }
    }
    else {
        if (currentInputDate.getDate() !== 1) {
            daysToAdd =  daysInCurrentMonth() - currentInputDate.getDate();
        }
        else {
            daysToAdd = daysInCurrentMonth();
        }
    }
    currentInputDate.setDate(currentInputDate.getDate() + daysToAdd);
    setInputDate()
    loadDataFromDb();
}
