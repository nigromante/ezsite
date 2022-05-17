<?php
/*
CREATE TABLE `Session` (
    `Session_Id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `Session_Create` datetime NOT NULL,
    `Session_Expires` datetime NOT NULL,
    `Session_Data` text COLLATE utf8_unicode_ci,
    PRIMARY KEY (`Session_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SELECT * FROM mydatabase.Session;
*/

/*
$handler_session = new SessionManager( $configMain->getDatabase( 'session' ) );
session_set_save_handler(
    [ $handler_session, 'open' ],
    [ $handler_session, 'close' ],
    [ $handler_session, 'read' ],
    [ $handler_session, 'write' ],
    [ $handler_session, 'destroy' ] ,
    [ $handler_session, 'gc' ]
);

session_name( "example" ) ;    
session_start();
*/

namespace nigromante\EzSite ; 


class SessionManager
{
    private $link;
    private $bdd_config ; 
    private $prefix = '' ; 
    private $espera ; 

    public function __construct( $bdd_config,  $prefix = '' )
    {
        $this->bdd_config = $bdd_config  ;
        $this->espera = $bdd_config[ 'espera' ] ;
        if( $prefix != '' )  $prefix .= "@" ;  
        $this->prefix  = $prefix ;  
    }


    public function open($savePath, $sessionName)
    {
        $link = mysqli_connect( $this->bdd_config['server'] , $this->bdd_config['user'], $this->bdd_config['password'] , $this->bdd_config['database']);
        if($link){
            $this->link = $link;
            return true;
        }
        return false;
    }

    public function close()
    {
        mysqli_close( $this->link );
        return true;
    }

    public function read($id)
    {
        $id = "{$this->prefix}{$id}" ; 
        $sql = "SELECT Session_Data FROM Session WHERE Session_Id = '{$id}' AND Session_Expires > now() " ;
        $result = mysqli_query(  $this->link, $sql  );
        if($row = mysqli_fetch_assoc($result)){
            return $row['Session_Data'];
        }else{
            return "";
        }
    }

    public function write($id, $data)
    {
        $id = "{$this->prefix}{$id}" ; 
        $sql = "INSERT INTO  
                Session 
                (Session_Id,Session_Expires,Session_Data,Session_Create) 
                VALUES ( '{$id}' , date_add( now(),interval {$this->espera} minute) , '{$data}' , now())  
                ON DUPLICATE KEY UPDATE  Session_Expires=date_add( now(),interval {$this->espera} minute),Session_Data='{$data}' 
                ";

        $result = mysqli_query(  $this->link , $sql );
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function destroy($id)
    {
        $id = "{$this->prefix}{$id}" ; 
        $sql = "DELETE FROM Session WHERE Session_Id ='{$id}' " ;
        $result = mysqli_query(  $this->link, $sql );
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function gc($maxlifetime)
    {
        $sql = "DELETE FROM Session WHERE now() > Session_Expires " ;
        $result = mysqli_query( $this->link, $sql );
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function info($_id)
    {
        $id = "{$this->prefix}{$_id}" ; 
        $sql = "SELECT '{$_id}' id , Session_Create created, Session_Expires expires, timestampdiff( minute, Session_Create, now()) elapsed   FROM Session WHERE Session_Id = '{$id}' AND Session_Expires > now() " ;
        $result = mysqli_query(  $this->link, $sql  );
        if($row = mysqli_fetch_assoc($result)){
            return $row;
        }
        return false;
    }


}



?>
