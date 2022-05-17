<?php  
namespace nigromante\EzSite ; 

class Request {

    private $requestMethod = '' ; 
    private $serverName = '' ; 
    private $controller = '' ; 
    private $method = '' ; 
    private $args = '' ;
    private $ipCliente = '' ; 

    public function __construct()
	{
        if( isset( $_SERVER ) && isset( $_SERVER['SERVER_NAME'] )  ) {
            $this->serverName = $_SERVER['SERVER_NAME']  ; 
        }
        if( isset( $_SERVER ) && isset( $_SERVER['REQUEST_METHOD'] )  ) {
            $this->requestMethod = $_SERVER['REQUEST_METHOD']  ; 
        }

        if( isset( $_SERVER ) && isset( $_SERVER['REQUEST_URI'] )  ) {
            list( $ruta , $args ) = explode( "?" , $_SERVER['REQUEST_URI'] ) ;

            $this->args = $args ;

            list( $basura , $this->controller , $this->method ) = explode( "/" , $ruta );
            
            $this->controller = strtolower( $this->controller );
            $this->method = strtolower( $this->method );

        }


	}

    public function getIpCliente() {
        Global  $IpCliente ;
        return  $IpCliente  ;
    }


    public function getServerName() {
        return  $this->serverName  ;
    }

    public function getRequestMethod() {
        return  $this->requestMethod  ;
    }

    public function getController() {
        return  $this->controller  ;
    }

    public function getMethod() {
        return  $this->method  ;
    }


    public function getArgs() {
        return  $this->args  ;
    }

    protected function get_client_ip() {
        $ipaddress = '';
         if (getenv('HTTP_CLIENT_IP'))
             $ipaddress = getenv('HTTP_CLIENT_IP');
         else if(getenv('HTTP_X_FORWARDED_FOR'))
             $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
         else if(getenv('HTTP_X_FORWARDED'))
             $ipaddress = getenv('HTTP_X_FORWARDED');
         else if(getenv('HTTP_FORWARDED_FOR'))
             $ipaddress = getenv('HTTP_FORWARDED_FOR');
         else if(getenv('HTTP_FORWARDED'))
             $ipaddress = getenv('HTTP_FORWARDED');
         else if(getenv('REMOTE_ADDR'))
             $ipaddress = getenv('REMOTE_ADDR');
         else
             $ipaddress = 'UNKNOWN';
 
         return $ipaddress;
     }
 

}

?>