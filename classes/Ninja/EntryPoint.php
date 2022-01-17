<?php
namespace Ninja;

class EntryPoint
{
    private $route;
    private $method;
    private $routes;

    public function __construct($route, $method, \Pomodoro\PomodoroRoutes $routes) {
        $this->route = $route;
        $this->routes = $routes;
        $this->method = $method;
        $this->checkUrl();
    }

    private function checkUrl() {
        if ($this->route !== strtolower($this->route)) {
            http_response_code(301);
            header('location: ' . strtolower($this->route));
        }
    }
    private function loadTemplate($templateFileName, $variables = []) {

        extract($variables);

        ob_start();
        include  __DIR__ . '/../../templates/' . $templateFileName;

        return ob_get_clean();
    }

    public function run() {
        $routes = $this->routes->getRoutes();
        $controller = $routes[$this->route][$this->method]['controller'];
        $action = $routes[$this->route][$this->method]['action'];
        $page = $controller->$action();
        $title = $page['title'];
        foreach ($page['templates'] as $templateName => $value){
            if(isset($value['variables'])){
                $$templateName = $this->loadTemplate($value['template'],$value['variables']);
            }else{
                $$templateName = $this->loadTemplate($value['template']);
            }
        }
        include __DIR__ . '/../../templates/layout.html.php';

    }

}