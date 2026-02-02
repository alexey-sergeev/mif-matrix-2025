<?php

//
// Ядро Инструменты разработки ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_tools_core {

    function __construct()
    {

    }
    
    

    // 
    // Показывает меню  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools.php', false );

        }
    }
    

}

?>