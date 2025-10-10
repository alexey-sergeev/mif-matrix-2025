<?php

//
// Экранные методы параметров
// 
//


defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_param  extends mif_mr_part_core {
    

    function __construct()
    {

        parent::__construct();
        
    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-param.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-param.php', false );

        }
    }
    



    // 
    // Родительские ОПОП
    // 
    
    public function get_item_parents()
    {
        global $tree;
        // global $mr;
        
        if ( isset( $_REQUEST['edit'] ) ) return $this->get_edit_textarea( 'parents', 'param' );

        $out = '';
        
        if ( isset( $tree['param']['parents']['data'] ) ) {
            
            foreach ( (array) $tree['param']['parents']['data'] as $item ) {

                if ( preg_match( '/^#.*/', $item ) ) continue; 

                $arr = explode( ' ', $item );
                $out .= '<div class="col-12 bg-light p-2 mt-3 border border-light">' . $this->get_link_post( $arr[0], 'opop' ) . '</div>';
            
                unset( $arr[0] );
                foreach ( $arr as $item2 ) $out .= '<div class="col-12 p-2">' . $item2 . '</div>';

            }

        } else {
            
            $out .= '<div class="col-12 p-2">none</div>';

        }

        // p($tree);

        return apply_filters( 'mif_mr_part_get_item_parents', $out );
    }
    

    

    
    // 
    // Обычный текст (справочники, параметры для метаданных, ...) 
    // 
    
    public function get_item_text( $key )
    {
        global $tree;
        // global $mr;

        if ( isset( $_REQUEST['edit'] ) ) return $this->get_edit_textarea( $key, 'param' );
        
        $out = '';
        $out .= '<div class="col-12 p-2">'; 
        
        if ( isset( $tree['param'][$key]['data'] ) ) {
            
            foreach ( (array) $tree['param'][$key]['data'] as $item ) {

                if ( preg_match( '/^#.*/', $item ) ) continue; 
            
                // $out .= '<div class="col-12 p-2 mt-3">' . $mr->get_link_post( (int) $item, $key ) . '</div>';
                // $out .= '<div class="col-12 p-2">' . $this->get_link_post( (int) $item, '' ) . '</div>'; // ###!!!
                $out .= $this->get_link_post( (int) $item, '' ); // ###!!!
                
            } 
            
        } else {
            
            $out .= 'none';
            
        }
        
        $out .= '</div>'; 
        // p($tree);

        return apply_filters( 'mif_mr_part_get_item_text', $out, $key );
    }

    
    
    
    // 
    // Пользователи 
    // 
    
    public function get_item_user( $key = 'admins' )
    {
        global $tree;
        // global $mr;
        
        if ( isset( $_REQUEST['edit'] ) ) return $this->get_edit_textarea( $key, 'param' );

        $out = '';
        $out .= '<div class="col-12 mt-2">';
        
        if ( isset( $tree['param'][$key]['data'] ) ) {
            
            // p($tree['param'][$key]['data']);
            
            foreach ( (array) $tree['param'][$key]['data'] as $item ) {

                if ( preg_match( '/^#.*/', $item ) ) continue; 
                
                $out .= $this->get_link_user( $item ); // ###!!!
                // $out .= '<div class="col-12 mt-2">' . $this->get_link_user( $item ) . '</div>'; // ###!!!
                
            }
            
        } else {
            
            $out .= 'none';
            
        }
        
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_item_text', $out, $key );
    }
    
  

}

?>