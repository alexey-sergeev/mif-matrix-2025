<?php

//
// Экранные методы параметров
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_part_core {
    

    function __construct()
    {

    }
    

        // 
    // Наследование от кого? 
    // 
    
    public function get_link_edit()
    {
        global $mr;

        $out = '';
    
        if (  $mr->user_can(3) && ! isset( $_REQUEST['edit'] ) ) {

            $out .= '<div class="row mt-5 mb-5">';
            $out .= '<div class="col-12 p-0"><a href="?edit">Редактировать</a></div>';
            $out .= '</div>';

        }

        return apply_filters( 'mif_mr_part_core_link_edit', $out );
    }
    
    
    
    // 
    // Наследование от кого? 
    // 
    
    public function get_from_id( $key, $main_key = 'param' )
    {
        global $tree;

        $out = 'none';
        if ( ! empty( $tree[$main_key][$key]['from_id'] ) ) $out = $tree[$main_key][$key]['from_id'];

        return apply_filters( 'mif_mr_part_core_get_from_id', $out, $key, $main_key );
    }

    
        
    // //
    // // Получить URL ОПОП
    // //

    // public function get_opop_url()
    // {
    //     global $tree;
    //     return get_permalink( $tree['main']['id'] );
    // }



    //
    // Получить ссылку поста
    //

    public function get_link_post( $id, $type = '' )
    {
        if ( (int) $id === 0 ) return $id;

        // $post = get_post( $id );
        
        // if ( empty( $post ) ) return $id;
        // if ( ! empty( $type ) && $post->post_type != $type ) return $id;
        
        if ( ! get_post_type( $id ) && get_post_type( $id ) != $type ) return $id;

        $out = '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';

        return apply_filters( 'mif_mr_core_get_link_post', $out, $id, $type );
    }



    //
    // Получить ссылку пользователей
    //

    public function get_link_user( $login )
    {

        $user = get_user_by( 'login', $login );

        if ( empty( $user ) ) return $login;
        
        // p($user);

        // if ( function_exists( 'bp_core_get_user_domain' ) ) {
        //     $user_url = bp_core_get_user_domain( $user_id );
        // } else {
        //     $user_url = get_the_author_link( $user_id );
        // }

        $out = $user->display_name . ' (' . $user->user_login . ')';

        return apply_filters( 'mif_mr_core_get_link_user', $out );
    }





    //
    // Форма textarea 
    //

    public function get_edit_textarea( $key, $main_key = 'param' )
    {
        $out = '';

        $out .= '<div class="mt-3 mb-3">123</div>';

        $out .= '<textarea name="' . $main_key . '[' . $key . ']" class="edit textarea">';
        $out .= $this->get_tree_to_text($key, $main_key);
        $out .= '</textarea>';


        // p($key);
        // p($main_key);

        return apply_filters( 'mif_mr_core_get_edit_textarea', $out, $key, $main_key );
    }




    //
    // Дерево ОПОП в тексте для редактирования 
    //

    public function get_tree_to_text( $key, $main_key = 'param', $own = true )
    {
        global $tree;
        global $mif_mr_opop;
        $out = '';
        
        if ( isset( $tree[$main_key][$key]['from_id'] ) &&
            ( $own && $tree[$main_key][$key]['from_id'] === $mif_mr_opop->get_opop_id() ) ) 
                if ( isset( $tree[$main_key][$key]['data'] ) )    
                    $out .= implode( "\n", $tree[$main_key][$key]['data'] );
        
        
        // p( $tree[$main_key][$key]['data'] );
        
        
        // p($out);
        
        
        // p($key);
        // p($main_key);
        
        return apply_filters( 'mif_mr_core_get_tree_to_text', $out, $key, $main_key );
    }
    
    
    

    //
    //  
    //
    
    public function get_form_begin()
    {
        global $wp_query;

        // p( $_REQUEST );
        // $this->get_save_to_opop();

        if ( ! isset( $_REQUEST['edit'] )) return;
        
        $part = ''; 
        if ( isset( $wp_query->query_vars["part"] ) ) $part = $wp_query->query_vars["part"];
        
        // p($wp_query);
        
        $out = '<form method="POST" action="' . get_permalink() . $part . '">';
        return apply_filters( 'mif_mr_core_get_form_begin', $out );
    }
    
    
    

    //
    //  
    //
    
    public function get_form_end()
    {
        if ( ! isset( $_REQUEST['edit'] )) return;
        
        $out = '';
        // $out .= '<input type="hidden" name="quiz_id" value="' . $quiz_id . '">';
        // $out .= '<input type="hidden" name="action" value="run">';
        // $out .= '<input type="hidden" name="start" value="yes">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';

        // $out .= '<button class="btn-primary btn-lg">' . $button . '</button>';
        
        $out .= '<input type="submit" name="save" value="Сохранить" class="btn btn-primary mt-6 mb-6 mr-3" />';
        $out .= '<input type="submit" name="cancel" value="Отмена" class="btn btn-light mt-6 mb-6 mr-3" />';
        $out .= '</form>';        

        return apply_filters( 'mif_mr_core_get_form_end', $out );
    }
    
    
    

    // //
    // //  
    // //
    
    // public function get_save_to_opop()
    // {
    //     if ( ! isset( $_REQUEST['save'] )) return false;
        
    //     global $tree;
    //     global $post;
    //     // global $mif_mr_opop;

    //     // p($tree);
    //     // p($_REQUEST);
    //     // $mif_mr_opop->get_tree_to_text();
        
    //     $arr = array();

    //     foreach ( $tree as $main_key => $item ) {
            
    //         if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
        
    //         foreach ( $item as $key => $item2 )
    //             if ( isset( $item2['data'] ) ) $arr[$main_key][$key] = implode( "\n", (array) $item2['data'] );

    //     }
        
    //     // p($arr);
        
    //     foreach ( $_REQUEST as $main_key => $item ) {
            
    //         if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
            
    //         foreach ( $item as $key => $item2 ) $arr[$main_key][$key] = sanitize_textarea_field( $item2 );
            
    //     }
        
    //     // p($arr);

    //     $out = '';

    //     foreach ( $arr as $main_key => $item ) {
            
    //         $out .= '@@' . $main_key . "\n";
    //         $out .= "\n";

    //         foreach ( $item as $key => $item2 ) {

    //             // if ( $item2['from_id'] != $tree['main']['id'] ) continue;
    //             // if ( ! isset( $item2['data'] ) ) continue;
    //             // p($item2['data']);

    //             $out .= '@' . $key . "\n";
    //             $out .= "\n";
    //             $out .= $item2;
    //             $out .= "\n\n";

    //             // $out .= $key . ' ' . $item2['data'] . "\n";

    //         }


    //     }

    //     $res = wp_update_post( array(

    //                                 'ID' => $post->ID,
    //                                 'post_content' => $out,

    //                             ) );

    //     p($out);

    //     return $res;
    //     // return apply_filters( 'mif_mr_core_get_save_to_opop', $out );
    // }



}

?>