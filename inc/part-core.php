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
   
    
        
    // // 
    // // Выноска
    // // 
    
    // public function get_callout( $text, $class = 'body' )
    // {
    //     $out = '';

    //     $out .= '<div class="callout mt-4 mb-4 pt-2 pb-2 bg-' . $class . ' border-start border-5 border-' . $class . '">';
    //     $out .= $text;
    //     $out .= '</div>';

    //     return apply_filters( 'mif_mr_part_core_get_callout', $out, $text, $class );
    // }
    
    
    
    // 
    // 
    // 
    
    public function get_link_edit()
    {
        global $mr;

        $out = '';
    
        // if (  $mr->user_can(3) && ! isset( $_REQUEST['edit'] ) ) {
        if (  $mr->user_can(3) ) {

            $out .= '<div class="row mt-1">';
            $out .= '<div class="col-12 p-0 mb-3"><a href="?edit">Редактировать</a></div>';
            $out .= '</div>';
            // $out .= '<div class="mb-3"><a href="?edit">Редактировать</a></div>';

        }

        return apply_filters( 'mif_mr_part_core_link_edit', $out );
    }
    
    
    
    // 
    // 
    // 
    
    public function get_link_edit_visual()
    {
        global $mr;
        
        $out = '';
        
        // if (  $mr->user_can(3) && ! isset( $_REQUEST['edit'] ) ) 
        if (  $mr->user_can(3) ) {
            
            $out .= '<div class="row mt-1">';
            $out .= '<div class="col-12 p-0 mb-3"><a href="?edit=visual">Визуальное редактирование</a></div>';
            $out .= '</div>';
            // $out .= '<div class="mb-3"><a href="?edit=visual">Визуальное редактирование</a></div>';
            
        }
        
        return apply_filters( 'mif_mr_part_core_link_edit_visual', $out );
    }
    
        
    
    // 
    // 
    // 
    
    public function get_link_edit_easy()
    {
        global $mr;
        
        $out = '';
        
        if (  $mr->user_can(3) ) {
            
            $out .= '<div class="row mt-1">';
            $out .= '<div class="col-12 p-0 mb-3"><a href="?edit=easy">Простой редактор</a></div>';
            $out .= '</div>';
            
        }
        
        return apply_filters( 'mif_mr_part_core_link_edit_easy', $out );
    }
    
    
    
    // // 
    // // 
    // // 
    
    // public function get_link_edit_advanced( $type )
    // {
    //     global $mr;

    //     $out = '';
        
    //     $id 

    //     if (  $mr->user_can(3) ) {
            
    //         $out .= '<div class="row mt-1">';
    //         $out .= '<div class="col-12 p-0 mb-3"><a href="' . get_edit_post_link( $wp_query->queried_object->ID ) . '">Расширенный редактор</a></div>';
    //         $out .= '</div>';
    //         // $out .= '<div class="mb-3"><a href="?edit=visual"></a></div>';

    //     }

    //     return apply_filters( 'mif_mr_part_core_link_edit_advanced', $out );
    // }
    
    
    
    // 
    // Наследование от кого? 
    // 
    
    public function get_from_id( $key, $main_key = 'param' )
    {
        global $tree;

        $out = 'none';

        // if ( ! empty( $tree[$main_key][$key]['from_id'] ) ) $out = $tree[$main_key][$key]['from_id'];
        if ( ! empty( $tree[$main_key][$key]['from_id'] ) ) {
            
            $out = '';
            $f = $tree['main']['id'] != $tree[$main_key][$key]['from_id'];

            if ( $f ) $out .= '<a href="' . get_permalink( $tree[$main_key][$key]['from_id'] ) . '" ' .
                                'title="' . $this->mb_substr( get_the_title( $tree[$main_key][$key]['from_id'] ), 20 ) . '">';
            $out .= $tree[$main_key][$key]['from_id'];
            if ( $f ) $out .= '</a>';

        }

        return apply_filters( 'mif_mr_part_core_get_from_id', $out, $key, $main_key );
    }

    

    //
    // Получить ссылку поста
    //

    public function get_link_post( $id, $type = '' )
    {
        if ( (int) $id === 0 ) return $id;
        
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
        
        $out = $user->display_name . ' (' . $user->user_login . ')';
        
        return apply_filters( 'mif_mr_core_get_link_user', $out );
    }

    
    
    
    //
    //  
    //
    
    public function get_permalink_part()
    {
        global $wp_query;

        $out = get_permalink();
        if ( isset( $wp_query->query_vars['part'] ) ) $out .= $wp_query->query_vars['part'];
        
        return apply_filters( 'mif_mr_core_get_link_part', $out );
    }
    
    
    

    //
    // Форма textarea 
    //

    public function get_edit_textarea( $key, $main_key = 'param' )
    {
        $out = '';

        $text = $this->get_tree_to_text($key, $main_key, false);

        if ( ! empty( $text ) ) {
            
            $from_id = $this->get_tree_to_from_id($key, $main_key);

            // $out .= $this->get_callout( 
            $out .= mif_mr_functions::get_callout( 
                'Данные от страницы «<a href="' . get_the_permalink($from_id) . '">' . get_the_title($from_id) . '</a>»: <pre>' . $text . '</pre>', 
                'warning' );
        }

        $out .= '<textarea name="' . $main_key . '[' . $key . ']" class="edit textarea mt-4">';
        $out .= $this->get_tree_to_text($key, $main_key);
        $out .= '</textarea>';

        return apply_filters( 'mif_mr_core_get_edit_textarea', $out, $key, $main_key );
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
            
                $out .= $this->get_link_post( (int) $item, '' ); // ###!!!
                
            } 
            
        } else {
            
            $out .= 'none';
            
        }
        
        $out .= '</div>'; 

        return apply_filters( 'mif_mr_part_get_item_text', $out, $key );
    }
    
    



    //
    // Дерево ОПОП в from_id для редактирования 
    //

    public function get_tree_to_from_id( $key, $main_key = 'param' )
    {
        global $tree;

        $out = '';
        if ( isset( $tree[$main_key][$key]['from_id'] ) ) $out .= $tree[$main_key][$key]['from_id'];
        
        return apply_filters( 'mif_mr_core_get_tree_to_from_id', $out, $key, $main_key );
    }



    //
    // Дерево ОПОП в тексте для редактирования 
    //

    public function get_tree_to_text( $key, $main_key = 'param', $own = true )
    {
        global $tree;
        global $mif_mr_opop;
      
        $out = '';
        
        if ( isset( $tree[$main_key][$key]['from_id'] ) && isset( $tree[$main_key][$key]['data'] ) ) {
            
            if ( $own && $tree[$main_key][$key]['from_id'] == $mif_mr_opop->get_opop_id() ||
                 ! $own && $tree[$main_key][$key]['from_id'] != $mif_mr_opop->get_opop_id() )
                        $out .= implode( "\n", $tree[$main_key][$key]['data'] );

        }
  
        return apply_filters( 'mif_mr_core_get_tree_to_text', $out, $key, $main_key, $out );
    }
    
    
    

    //
    //  
    //
    
    public function get_form_begin()
    {
        if ( ! isset( $_REQUEST['edit'] )) return;

        $out = '<form method="POST" action="' . $this->get_permalink_part() . '">';
        return apply_filters( 'mif_mr_core_get_form_begin', $out );
    }
    
    
    

    //
    //  
    //
    
    public function get_form_end()
    {
        if ( ! isset( $_REQUEST['edit'] )) return;
        
        $out = '';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';
        $out .= '<input type="submit" name="save" value="Сохранить" class="btn btn-primary mt-6 mb-6 mr-3" />';
        $out .= '<input type="button" onclick="location.href=\'' . $this->get_permalink_part(). '\';"  value="Отмена" class="btn btn-light mt-6 mb-6 mr-3" />';
        $out .= '</form>';        

        return apply_filters( 'mif_mr_core_get_form_end', $out );
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
    

    //
    //  
    //
    
    public function remove_at( $s )
    {
        return preg_replace( '/@/', '', $s );
    }

}

?>