<?php

//
// Экранные методы параметров
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_param  extends mif_mr_part_core {
    
    function __construct()
    {

        parent::__construct();
        
        global $explanation;
        
        $explanation = apply_filters( 'mif-mr-param-explanation', array(
        
            'users' => 'Имя пользователя',
            'parents' => 'Идентификатор страницы программы',
            'references' => 'Идентификатор страницы справочника',
            'specifications' => 'Идентификатор страницы параметров метаданных',
        
        ) );

        $this->save();


    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        global $mr;

        if ( ! $mr->user_can( 3 ) ) {
            
            echo $mr->access_denied();
            return false;
        
        }


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
        
        if ( isset( $_REQUEST['edit'] ) ) return $this->edit_textarea( 'parents', 'param' );

        $out = '';
        
        if ( isset( $tree['param']['parents']['data'] ) ) {
            
            foreach ( (array) $tree['param']['parents']['data'] as $item ) {

                if ( preg_match( '/^#.*/', $item ) ) continue;                 
                preg_match_all( '/\d+|\(.*\)/', $item, $m );

                $arr = $m[0];

                if ( empty( $m[0] ) ) continue;

                $out .= '<div class="col-12 bg-light p-2 mt-3 border border-light">' . $this->get_link_post( $arr[0], 'opop' ) . '</div>';
            
                unset( $arr[0] );
                foreach ( $arr as $item2 ) $out .= '<div class="col-12 p-2">' . $item2 . '</div>';

            }

        } else {
            
            $out .= '<div class="col-12 p-2">none</div>';

        }

        return apply_filters( 'mif_mr_part_get_item_parents', $out );
    }
    

   
    
    // 
    // Пользователи 
    // 
    
    public function get_item_user( $key = 'admins' )
    {
        global $tree;
        
        if ( isset( $_REQUEST['edit'] ) ) return $this->get_edit_textarea( $key, 'param' );

        $out = '';
        $out .= '<div class="col-12 mt-2">';
        
        if ( isset( $tree['param'][$key]['data'] ) ) {
            
            foreach ( (array) $tree['param'][$key]['data'] as $item ) {

                if ( preg_match( '/^#.*/', $item ) ) continue; 
                
                $out .= '<div class="col-12 mt-2">' . $this->get_link_user( $item ) . '</div>'; // ###!!!
                
            }
            
        } else {
            
            $out .= 'none';
            
        }
        
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_item_text', $out, $key );
    }
    
  


    // 
    //  
    //
    
    private function save()
    {
        if ( ! isset( $_REQUEST['save'] )) return false;
        
        global $post;
        global $tree;
        global $mif_mr_opop;

        $t = $tree;
        
        $arr = array();

        foreach ( $t as $main_key => $item ) {
            
            if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
            if ( isset( $item['from_id'] ) && $item['from_id'] != $post->ID ) continue;

            foreach ( $item as $key => $item2 )
                if ( isset( $item2['data'] ) ) $arr[$main_key][$key] = implode( "\n", (array) $item2['data'] );

        }
                
        foreach ( $_REQUEST as $main_key => $item ) {
            
            if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;

            foreach ( $item as $key => $item2 ) $arr[$main_key][$key] = sanitize_textarea_field( $this->remove_at( $item2 ) );
            
        }
        
        $out = '';

        foreach ( $arr as $main_key => $item ) {
            
            $out .= '@@' . $main_key . "\n";
            $out .= "\n";

            foreach ( $item as $key => $item2 ) {

                $out .= '@' . $key . "\n";
                $out .= "\n";
                $out .= $item2;
                $out .= "\n\n";

            }

        }

        $res = wp_update_post( array(

                                    'ID' => $post->ID,
                                    'post_content' => $out,

                                ) );


        global $messages;

        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 101', 'danger' );

        wp_cache_delete( 'get_param_and_meta', $post->ID );

        if ( $res ) {

            $tree = array();
            $tree = $mif_mr_opop->get_tree();

        }

        return $res;
    }


}

?>