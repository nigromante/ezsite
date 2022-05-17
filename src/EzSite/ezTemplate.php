<?php
namespace nigromante\EzSite ;


class ezTemplate {

    private $twig = null  ; 


    public function Configure( $dir_templates , $dir_temp ) {

        $loader = new \Twig\Loader\FilesystemLoader( $dir_templates );
        $this->twig = new \Twig\Environment($loader, [
            // 'cache' => $dir_temp  ,
            'cache' => false ,
        ]);

    }

    public function  render( $view , $data ) {
        return $this->twig->render( $view . ".twig" , $data );      
    }

}
?>