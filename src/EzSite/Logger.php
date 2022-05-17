<?php
namespace nigromante\EzSite ; 

class Logger {

    public static function  __callStatic($name, $arguments)
    {
        if( in_array( $name , ["info", "warning", "error", "critical"]) ) {
            self::doLogger( $name , $arguments ) ;
        }
        
    }

    private static  function  doLogger( $name , $arguments ) {
        echo $arguments[0] . "<br>\r\n" ; 
    }
}
?>