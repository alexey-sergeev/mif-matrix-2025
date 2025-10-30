<?php

//
//  Список компетенций
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_list_comp extends mif_mr_companion_core {
    

    function __construct()
    {
        parent::__construct();

    }
  
        
    // 
    // Показывает компетенции
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-list-comp.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-list-comp.php', false );

        }
    }
    




    //
    // Показать cписок компетенций
    //

    public function show_list_comp()
    {
        global $wp_query;
     
        if ( isset( $wp_query->query_vars['id'] ) ) return;
                
        $out = '';
        
        $list = $this->get_list_companions( 'competencies' );
        
        // p($arr);
        
        
        $out .= '<div class="container bg-light pt-5 pb-5">';
        // $out .= '<div class="container no-gutters">';

        $out .= '<div class="row">';

        $out .= '<div class="col">';
        $out .= '<h4 class="">Библиотека компетенций</h4>';
        $out .= '</div>';
        
        $out .= '</div>';

        foreach ( $list as $item ) {
            
            $out .= '<div class="row mt-3 mb-3">';

            $out .= '<div class="col pt-1 pb-1">';
            // $out .= '<i class="fa-regular fa-file-lines fa-xl"  style="color: #606570;"></i> ';
            // $out .= '<i class="fa-regular fa-file-lines fa-xl"></i> ';
            // $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '"><i class="fa-regular fa-file-lines fa-xl"></i></a> ';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '"><i class="fa-solid fa-book"></i></a> ';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '">' . $item['title'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';

        }
        
        
        $out .= '</div>';


        return apply_filters( 'mif_mr_show_list_competencies', $out );
    }
    

 
}


?>