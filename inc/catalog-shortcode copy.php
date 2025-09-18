<?php

//
// Экранные методы каталога
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_catalog_shortcode extends mif_mr_catalog_core {
    
    function __construct()
    {
        parent::__construct();
        
        add_shortcode( 'opops_list', array( $this, 'opops_list' ) );
        
    }
    
    


    
    //
    // Список ОПОП
    //
    
    public function opops_list( $atts )
    {
        $out = '';
        
        $args = $this->get_catalog_args();
        $args['posts_per_page'] = -1;

        $opops = get_posts( $args );
                
        $out .= '<table>';
        
        $n = 1;

        foreach ( (array) $opops as $opop ) {
            
            $out .= '<tr>';
            
            $out .= '<td>' . $n++ . '</td>';
            
            $out .= '<td>';
            $out .= '<a href="' . get_permalink( $opop->ID ) . '" target="_blank" >';
            $out .= $opop->post_title;
            $out .= '</a>';
            $out .= '</td>';
            
            $flag = false;

            foreach ( array('department', 'degree', 'period', 'form' ) as $item ) {

                $arr = $this->get_the_category_opop( $opop->ID, $item, 'name' );
                $class = ( count( $arr ) == 1) ? '' : ( ( count( $arr ) == 0) ? ' class="mr-red"' : ' class="mr-yellow"' );
                if ( count( $arr ) == 0 ) { 
                    $arr[] = '???';
                    $flag = true;
                }
                $out .= '<td' . $class . '>';
                // $out .= '<a href="' . get_edit_post_link( $opop->ID ) . '" target="_blank">';
                $out .= implode( '<br />', $arr );
                // $out .= '</a>';
                $out .= '</td>';
                
            }
            
            
            $class = ( $flag ) ? ' class="mr-red"' : '';
            $out .= '<td' . $class . '>';
            $out .= '<a href="' . get_edit_post_link( $opop->ID ) . '" target="_blank">';
            // $out .= '<i class="fa fa-pencil-square fa-1x"></i>';
            $out .= '<i class="fa fa-pencil-square-o fa-1x"></i>';
            // $out .= '<i class="fa fa-pencil fa-1x"></i>';
            $out .= '</a>';
            $out .= '</td>';
            
            
            // $arr = $this->get_the_category_opop( $opop->ID, 'degree', 'name' );
            // $class = ( count( $arr ) == 1) ? '' : ( ( count( $arr ) == 0) ? ' class="bg-danger"' : ' class="bg-warning"' );
            // if ( count( $arr ) == 0 ) $arr[] = '???';
            // $out .= '<td' . $class . '>';
            // $out .= implode( '<br />', $this->get_the_category_opop( $opop->ID, 'degree', 'name' ) );
            // $out .= '</td>';
            
            // $arr = $this->get_the_category_opop( $opop->ID, 'period', 'name' );
            // $class = ( count( $arr ) == 1) ? '' : ( ( count( $arr ) == 0) ? ' class="bg-danger"' : ' class="bg-warning"' );
            // if ( count( $arr ) == 0 ) $arr[] = '???';
            // $out .= '<td' . $class . '>';
            // $out .= implode( '<br />', $this->get_the_category_opop( $opop->ID, 'period', 'name' ) );
            // $out .= '</td>';
            
            // $arr = $this->get_the_category_opop( $opop->ID, 'form', 'name' );
            // $class = ( count( $arr ) == 1) ? '' : ( ( count( $arr ) == 0) ? ' class="bg-danger"' : ' class="bg-warning"' );
            // if ( count( $arr ) == 0 ) $arr[] = '???';
            // $out .= '<td' . $class . '>';
            // $out .= implode( '<br />', $this->get_the_category_opop( $opop->ID, 'form', 'name' ) );
            // $out .= '</td>';

            // $out .= 
            
            $out .= '</tr>';
            
        }
        
        $out .= '</table>';

        return apply_filters( 'mif_mr_screen_opops_list_shortcode', $out );
    }
    
    
    
    //
    // Получить массив категорий
    //
    
    public function get_the_category_opop( $id, $which, $key = 'description' )
    {
        
        $term_id = get_term_by( 'slug', $which, 'opop_category' )->term_id;
        
        if ( empty( $term_id ) ) return;
        
        $categories = get_the_terms( $id, 'opop_category' );
        
        $arr = array();
        
        foreach ( (array) $categories as $item ) 
        if ( ! empty( $item ) && $item->parent === $term_id ) $arr[] = $item->$key;

        return apply_filters( 'mif_mr_screen_get_the_category_opop', $arr );
    }
        

    
}

?>