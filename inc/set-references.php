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
        
        // add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        // add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        // add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );

        // $this->save( 'set-courses' );

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
        // p($arr);
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
    
    
    
    
    // //
    // //
    // //
    
    // public function get_course_ifno( $course )
    // {
    //     global $tree;
        
    //     $arr = array();

    //     $arr_raw = $tree['content']['courses']['data'];
    //     $arr_set = $tree['content']['set-courses']['data'];

    //     // p($arr_raw);
    //     // p($arr_set);

    //     foreach ( $arr_raw as $item ) {

    //         foreach ( $item['courses'] as $key2 => $item2 ) {

    //             // p($key2);

    //         }

    //         // p($item);

    //     }


    //     return apply_filters( 'mif_mr_get_course_ifno', $arr );
    // }
        



  
    
}

?>