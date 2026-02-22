<?php

//
// Настройки дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_courses extends mif_mr_set_core {
    
    function __construct()
    {

        parent::__construct();
        
        // add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        // add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        // add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );

        $this->save( 'set-courses' );

    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-courses.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-courses.php', false );
            
        }
    }
    
    


    //
    // Показать cписок дисциплин
    //
    
    public function show_set_courses()
    {
        global $tree;
        
        $out = '';

        if ( isset( $_REQUEST['edit'] ) ) {
                
            $out .= $this->companion_edit( 'set-courses' );
            
        } else {
            
            $arr = $tree['content']['courses']['index'];
            // ksort( $arr );
            // p($arr);
        
        }
        
        
        return apply_filters( 'mif_mr_show_set_courses', $out );
    }
    
    
    

}

?>