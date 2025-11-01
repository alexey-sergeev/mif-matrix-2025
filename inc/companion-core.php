<?php

//
//  Связанная запись (дисциплины, компетенции или др.)
// 
//


defined( 'ABSPATH' ) || exit;


// class mif_mr_companion extends mif_mr_part_core {
class mif_mr_companion_core {
    

    function __construct()
    {

  
    }
  
 
    
     
    //
    // Список связанной записи
    //
    
    public function get_list_companions( $type = 'course', $opop_id = NULL )
    {
        // $content = '';
        
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // p($type);
        // p($opop_id);
        $post = get_posts( array(
            'post_type' => $type,
            'post_status' => 'publish',
            'post_parent' => $opop_id,
            'posts_per_page' => -1,
            'order' => 'ASC',
            ) );
        
        $arr = array();

        foreach ( $post as $item ) {

            // p($item);
            $arr[] = array( 
                'id' => $item->ID,
                'title' => $item->post_title,
                'parent' => $item->post_parent,
                'content' => $item->post_content
            );

        }    
        // p($arr);

        return apply_filters( 'mif_mr_core_get_list_companions', $arr, $type, $opop_id );
    }

   
    

    

   
    public function save( $sub_id, $comp_id, $opop_id )
    {
        // ####!!!!!

        $res = false;

        $arr = $this->get_sub_arr( $comp_id );

        if ( $sub_id == -1 ) $sub_id = (int) array_key_last( $arr ) + 1; 

        $arr[$sub_id] = sanitize_textarea_field( $_REQUEST['content'] );
        // p($_REQUEST);


        $res = wp_update_post( array(
            'ID' => $comp_id,
            'post_content' => implode( "\n", $arr ),
            ) );
                
       
        global $messages;
        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 103 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }
        


        return $res;
    }
   


    public function remove( $comp_id, $opop_id, $type = 'competencies' )
    {
        // ####!!!!!

        $res = false;

        $post = get_post( $comp_id );

        if ( $post->post_type == $type ) $res = wp_delete_post($comp_id);
       
        global $messages;
        $messages[] = ( $res ) ? array( 'Данные были удалены ', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 104 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }

        return $res;
    }
   


    public function create( $opop_id, $type = 'competencies' )
    {
        // ####!!!!!

        $res = false;
        
        if ( empty( $_REQUEST['title'] ) ) return;

        $res = wp_insert_post( array(
            'post_title'    => sanitize_textarea_field( $_REQUEST['title'] ),
            'post_content'  => ( isset( $_REQUEST['data'] ) ) ? sanitize_textarea_field( $_REQUEST['data'] ) : '',
            'post_type'     => $type,
            'post_status'   => 'publish',
            'post_parent'   => $opop_id,
            ) );


        // global $messages;
        // $messages[] = ( $res ) ? array( 'Данные были удалены', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 105 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;

            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }

        return $res;
    }



    
    
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'competencies', $opop_id );
       
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr', $arr );

    }

    
    
    



    
    
    //
    // Режим edit
    //

    public function get_edit( $sub_id, $comp_id, $opop_id )
    {
        // ####!!!!!

        $arr = $this->get_sub_arr( $comp_id );

        if ( isset( $arr[$sub_id] ) || $sub_id == '-1' ) {

            $out = '';

            // $out .= '<div class="content-ajax">';
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
            
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            $out .= '<textarea name="content[' . $sub_id . ']" class="edit textarea content" autofocus>';
            
            if ( isset( $arr[$sub_id] ) ) $out .= $arr[$sub_id];
            
            $out .= '</textarea>';
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';

            $out .= '<button type="button" class="btn btn-primary mt-4 mb-4 mr-3 save" data-sub="' . $sub_id . '">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $out .= '<button type="button" class="btn btn-light mt-4 mb-4 mr-3 cancel" data-sub="' . $sub_id . '">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';

            $out .= '</div>';
            $out .= '</div>';
            // $out .= '</div>';
            
        }

        return apply_filters( 'mif_mr_companion_get_edit', $out, $sub_id, $comp_id, $opop_id );
    }

   
    //
    //  
    //
    
    public function mb_substr( $s, $length )
    {
        // $out = mb_substr( $s, 0, $length - 3 );
        // if ( mb_strlen( $s ) >= $length ) $out .= '...';
        // return apply_filters( 'mif_mr_part_core_mb_substr', $out );
        return mif_mr_functions::mb_substr( $s, $length );
    }
    

}


?>