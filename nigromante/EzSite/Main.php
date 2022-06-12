<?php 
namespace nigromante\EzSite ; 


class Main {

    private $config = null ; 
    private $session = null ; 
    private $request = null ; 
    private $response = null ; 
    private $routes = null ; 
    private $databases = null ; 
    private $fecha = null ; 
    private $fechagmt = null ; 

    private $resume_info = null ; 

    public function __construct()
	{
        $this->fecha = date( 'Y-m-d H:i:s' ) ;
        $this->fechagmt = gmdate( 'Y-m-d H:i:s' ) ;
    }

    public function getDate(  ) {
        return $this->fecha  ; 
    }

    public function getDateGmt(  ) {
        return $this->fechagmt  ; 
    }
   
    public function setConfig( $config ) {
        $this->config = $config ; 
        $this->routes = $config->getRoutes() ; 
        $this->databases = $config->getDatabases() ; 
        return $this ; 
    }
    public function getConfig( ) {
        return $this->config  ; 
    }

    public function getRoutes( ) {
        return $this->routes  ; 
    }

    public function setRequest( $request ) {
        $this->request = $request ; 
        return $this ; 
    }

    public function getSession( ) {
        return $this->session  ; 
    }

    public function setSession( $session ) {
        $this->session = $session ; 
        return $this ; 
    }



    public function getRequest( ) {
        return $this->request  ; 
    }

    public function setResponse( $response ) {
        $this->response = $response ; 
        return $this ; 
    }

    public function getResponse(  ) {
        return $this->response  ; 
    }

    public function info( $opt = false ) {

        if( $opt === false)  return $this ;

        Global $sessionInfo ; 

        $this->resume_info = [
            "Sistema" => [
                "Fecha" =>  $this->getDate() ,
                "Techa UTC" => $this->getDateGmt() ,
                "Server" =>  $this->request->getServerName(),
                "Server software " => $_SERVER[ "SERVER_SOFTWARE" ] , 
                "PHP" => phpversion() ,
                "NODO_NOMBRE" => getenv( "NODO_NOMBRE" )  
            ],

            "Configuracion" => [

                "Aplicacion" => [
                    "Titulo"     =>  $this->config->getTitle()  ,
                    "Version"   =>  $this->config->getVersion()  ,
                    "Author"    =>  $this->config->getAuthor()  ,
                    "Copyright" =>  $this->config->getCopyright()   
                ] ,
                "Directorios" => [
                    "Root" =>  DIR_ROOT   ,
                    "Controller" =>  DIR_CONTROLLER   
                ],
                "databases" => $this->databases ,  
                "routes" =>  $this->routes , 
            ],

            "Session" => [
                "Ip Cliemte" =>  $this->request->getIpCliente()  ,  
                "Session Info" => $sessionInfo ,
                "Session Vars" => $_SESSION 
            ] , 

            "Globals" => array_keys( $GLOBALS ) , 

            "Server" => $_SERVER ,

            "MVC" => [
                "HTTP method" =>  $this->request->getRequestMethod() ,
                "controller" =>  $this->request->getController()  ,
                "method" =>  $this->request->getMethod()  ,
                "args" =>  $this->request->getArgs()  ,
                "response" =>  $this->response->getResponseMethod()   
        
            ] ,

            "Logger" => []

        ] ;

        $this->response->set( "_debug_" , $this->resume_info ) ;


        return $this ; 
    }

    public function run() {

        $controller_time_inicio = round(microtime(true) * 1000); 

        $html_method = $this->request->getRequestMethod() ; 
        $controller = $this->request->getController() ; 
        $method = $this->request->getMethod() ; 

        $class_name = "{$controller}Controller";
        $class_filename = $class_name . ".php";

        $class_file = DIR_CONTROLLER . DS . $class_filename ; 

        if( file_exists( $class_file ) ) {

            require $class_file ; 
            $obj = new $class_name( $this ) ; 
            $obj->$method( ) ;

        }
        $controller_time_final = round(microtime(true) * 1000); 
        $this->response->TimeController( $controller_time_final- $controller_time_inicio ) ;

        return $this ; 
    }

    public function response() {

        $this->response->render();

        return $this ; 
    }

}

?>
