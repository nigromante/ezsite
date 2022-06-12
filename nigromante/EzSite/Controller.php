<?php

namespace nigromante\EzSite ; 

class Controller {

    private $context = null ;

    public function __construct( $context )
    {
        $this->context = $context ; 
    }

    public function getRequest() {
        return $this->context->getRequest() ;
    }

    public function getSession() {
        return $this->context->getSession() ;
    }

    public function set( $key, $data ) {
        $this->context->getResponse()->set($key, $data) ;
    }

    public function logger( $key, $msg ) {
        $this->context->getResponse()->logger( $key, $msg ) ;
    }


}
?>