<?php

//
// Матрица компетенций
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_attributes extends mif_mr_part_companion {
    
    function __construct()
    {

        parent::__construct();
       
        $this->save( 'attributes' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-attributes.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-attributes.php', false );

        }
    }
    
    
    
    
    // 
    // Показывает attributes
    // 
    
    public function get_attributes()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'attributes' );
        
        $arr = $this->get_companion_content( 'attributes' );

        p($arr);

        $out = '';

        $out .= '@@@@@@';
        
        return apply_filters( 'mif_mr_part_get_attributes', $out );
    }
    
    
   
    
    //

}

?>