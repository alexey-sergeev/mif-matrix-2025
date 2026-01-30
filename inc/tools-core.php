<?php

//
// Ядро Инструменты разработки ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_tools_core {

    
    // private $param_map = array();
    // // protected $parents_arr = array();
    
    
    function __construct()
    {
        // p($tree);
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
    


    
    
    // // 
    // // 
    // // 
    
    // protected function get_param_and_meta( $opop_id = NULL )
    // {
    //     // global $post;
    //     // p($opop_id);        
    //     // if ( $opop_id === NULL ) $opop_id = get_the_ID();
    //     if ( $opop_id === NULL ) $opop_id = $this->current_opop_id;
    //         // $opop_id = ( isset( $_REQUEST['opop'] ) ) ? (int) $_REQUEST['opop'] : get_the_ID();

    //     return apply_filters( 'mif_mr_core_opop_get_param_and_meta', $t, $opop_id );
    // }
    
    
    
    

    

}

?>