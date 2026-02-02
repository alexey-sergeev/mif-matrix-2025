<?php

//
// Настройки справочников
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_references extends mif_mr_set_core {
    
    function __construct()
    {
        parent::__construct();
        
    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-references.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-references.php', false );
            
        }
    }
    
    


    //
    // Показать cписок references
    //
    
    public function show_set_references()
    {
        global $tree;
        
        $arr = $tree['content']['references']['data'];
        $m = new modules();
        $arr = $m->get_courses_tree( $arr );
        
        $out = '@2@';
        // $out = '';
        
        // if ( isset( $_REQUEST['edit'] ) ) {
            
        //     $out .= $this->companion_edit( 'set-courses' );
            
        // } else {
            
        //     $out .= '<div class="row fiksa">';
        //     $out .= '<div class="col p-0">';
        //     $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Дисциплины в ОПОП:</h4>';
        //     $out .= '</div>';
        //     $out .= '</div>';
            
            
        //     $out .= '<div class="row">';
        //     $out .= '<div class="col p-0 mb-3">';
        //     $out .= mif_mr_companion_core::get_show_all();
        //     $out .= '</div>';
        //     $out .= '</div>';
        //     // $arr = $this->get_courses_arr(); 
        //     // return '@2';
            
        //     // $out .= '<div class="content-ajax col-12 p-0">';
        //     $out .= '<div class="row">';
        //     $out .= '<div class="col p-0">';
        //     $out .= $this->get_table_html( $arr );       
        //     $out .= '</div>';
        //     $out .= '</div>';
        //     // $out .= '</div>';
           
        // }
        
        
        
        return apply_filters( 'mif_mr_show_set_references', $out );
    }
    
    
}

?>