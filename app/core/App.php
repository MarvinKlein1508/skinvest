<?php
class App
{

    protected $controller = 'home';
    protected $controller_path = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {

        $url = $this->parseUrl();

        if(!isset($url)){
            $url = [];
        }   
        
        // Prüfen ob der Controller existiert
        $tmp_path = "";
        $url_length = count($url);
        for($i = 0; $i < $url_length; $i++) {
            $tmp_path .= (empty($tmp_path) ? $url[$i] : "/" . $url[$i]);

            if(file_exists(CONTROLLER_PATH . $tmp_path . ".php")) {
                $this->controller = $url[$i];
                $this->controller_path = $tmp_path;
                break;
            }
        }


        // Initialize Language for Controller based on User
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if(isset($_COOKIE['lang'])):
            $lang = $_COOKIE['lang'];
        endif;

        if(isset($_GET['lang'])):
            $lang = $_GET['lang'];
        endif;

        if(!in_array($lang, array("de", "en"))):
            $lang = "en";
        endif;

        setcookie("lang", $lang, strtotime("+1 year"));
        // Controller einbinden
        require_once CONTROLLER_PATH . $this->controller_path . '.php';
        $this->controller = new $this->controller($lang);

    
        
        
  
        // Prüfen ob das Objekt des Controllers die Methode enthält
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
     
        call_user_func_array([$this->controller, $this->method], $this->params);
    }



    protected function parseUrl() {
        if(isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
?>