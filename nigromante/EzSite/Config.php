<?php

namespace nigromante\EzSite ; 

class Config {

    public $data = [] ; 

    public function getTitle() {
        return $this->data["title"] ; 
    }

    public function getVersion() {
        return $this->data["version"] ; 
    }
   
    public function getAuthor() {
        return $this->data["author"] ; 
    }

    public function getCopyright() {
        return $this->data["copyright"] ; 
    }

    public function getRoutes() {
        return $this->data["routes"] ; 
    }

    public function getDatabases() {
        return $this->data["databases"] ; 
    }

    public function getDatabase( $database ) {
        return $this->data["databases"][$database] ; 
    }

}
?>