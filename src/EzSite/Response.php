<?php  
namespace nigromante\EzSite ; 

define( 'TWIG' , 'twig') ; 

class Response {

    private $validResponseMethod = ['html', 'json' , 'raw', 'csv', 'xls' ] ;
    private $responseMethod = 'html' ;
    private $data = [] ;
    private $view = null ;
    private $layout = null ;

    private $controller = '' ; 
    private $method = '' ; 
    private $templateManager = null ; 


    public function __construct()
	{
        if( isset( $_SERVER ) && isset( $_SERVER['REQUEST_URI'] )  ) {
            list( $ruta , $basura ) = explode( "?" , $_SERVER['REQUEST_URI'] ) ;
            list( $basura , $this->controller , $this->method ) = explode( "/" , $ruta );

            $this->controller = strtolower( $this->controller );
            $this->method = strtolower( $this->method );
        }
	}


    public function setResponseMethod( $responseMethod ) {
        if( in_array($responseMethod , $this->validResponseMethod ) ) {
            $this->responseMethod = $responseMethod ; 
        }
        return $this ;  
    }

    public function getResponseMethod() {
        return $this->responseMethod ; 
    }

    public function setView( $view ) {
        $this->view = $view  ;
    }

    public function setLayout( $layout ) {
        $this->layout = $layout  ;
    }

    public function set( $key, $data ) {
        $this->data[ $key ] = $data  ;
    }

    public function logger( $key, $msg ) {
        if( isset( $this->data[ "_debug_" ] ) ) {
            $this->data[ "_debug_" ]["Logger"][ $key ] = $msg  ;
        }
    }

    public function TimeController( $time ) {
        if( isset( $this->data[ "_debug_" ] ) ) {
            $this->data[ "_debug_" ]["Time"][ "Controller" ] = $time . " ms" ;
        }
    }

    public function setTemplateManager( $templateManager ) {
        $this->templateManager = $templateManager; 
        return $this ; 
    }

    public function render() {

        global $time_inicio ;

        header_remove('x-powered-by');
        
        $this->templateManager->Configure( DIR_VIEW ,  DIR_TMP .  DS . TWIG ) ; 

        $view_file = DIR_VIEW . DS . $this->controller . DS . $this->method . ".twig"  ; 

        if( file_exists( $view_file ) ) {

            $debug_info_raw = '';
            if( isset( $this->data["_debug_"])) {
                $debug_info = $this->data["_debug_"] ; 
                unset( $this->data["_debug_"] );
    
                $debug_info["Files"] = get_included_files() ;
                $debug_info["View"] = [ "file" => $view_file ] ; 
                
                $time_final = round(microtime(true) * 1000); 
                $time_demora = $time_final - $time_inicio ;
                $debug_info["Time"]["Total"] = $time_demora . " ms";
                $debug_info_raw =  $this->dbg( $debug_info , "Debug Info" ) ; 
            }
    
            echo $this->templateManager->render( 
                $this->controller . DS . $this->method , 
                [ 
                    'data' => $this->data, 
                    'debug' => $debug_info_raw 
                ]  
            );
        }


    }



    private function _dbg( $t, $_t ) {
            $ret = "" ; 
        if( is_array( $t ) ) {
            $ret .=   "<a href='#'> $_t </a>" ; 
            $ret .=  "<ul>" ;
            foreach( $t as $k => $v ) {
                $ret .=  $this->_dbg( $v , $k ) ;
            } 
            $ret .=  "</ul>" ; 
        } 
        else {
            $ret .=   "<li> <i> {$_t} </i> <b> {$t} </b> </li>"  ; 
        }
        return $ret ;
    }

    private  function dbg( $t , $tit = '' ) {
            $ret = "<div class='debug_info'>" ; 
            if( $tit != '' ) {
                $ret .= "<h1>{$tit}</h1>"  ;
            }
            $ret .= $this->_dbg( $t , '' ) ;

            $ret .= "</div>";
            return $ret ;
    }

}

?>