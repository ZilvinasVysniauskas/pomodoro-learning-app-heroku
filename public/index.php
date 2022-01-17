<?php
try {
    include __DIR__ . '/../includes/autoload.php';

    $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
    echo '<script>console.log(' . $route .')
var delayInMilliseconds = 10000; //1 second

setTimeout(function() {
  //your code to be executed after 1 second
}, delayInMilliseconds);

</script>';


    $entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Pomodoro\PomodoroRoutes());
    $entryPoint->run();
}
catch (PDOException $e) {
    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
    include  __DIR__ . '/../templates/layout.html.php';
}
