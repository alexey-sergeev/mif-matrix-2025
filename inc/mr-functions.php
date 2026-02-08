<?php

//
// Ядро инит (это разные функции)
// 
//


defined( 'ABSPATH' ) || exit;




class mif_mr_functions { 


    
    function __construct()
    {
  


    }





    function level_access( $post = NULL ) 
    {
        //  http://codex.wordpress.org/Roles_and_Capabilities
        
        // 0 - ничего не умеет
        // 1 - просмотр общедоступной информации
        // 2 - просмотр всей информации ОПОП
        // 3 - изменение информации ОПОП
        // 4 - редактор
        // 5 - админ
    
        // if ( $post == NULL ) global $post;
    
        if ( ! is_user_logged_in() ) return 0;
        
        if ( current_user_can( 'administrator' ) ) return 5;
        if ( current_user_can( 'editor' ) ) return 4;
        
        // !!!
        if ( current_user_can( 'author' ) ) return 3;
        if ( current_user_can( 'contributor' ) ) return 2;
        if ( current_user_can( 'subscriber' ) ) return 1;
        // !!!
    
    
    
        // if ( current_user_can( 'manage_options' ) ) return 5;
        // if ( current_user_can( 'edit_pages' ) ) return 4;
        
        
        // // !!!
        // if ( current_user_can( 'edit_post' ) ) return 3;
        // if ( current_user_can( 'read' ) ) return 2;
        // if ( current_user_can( 'read' ) ) return 1;
        // // !!!
        
    }
    
    
    function user_can( $level_access, $post = NULL )
    {
        if ( $this->level_access( $post ) >= $level_access ) return true;
        return false;
    }
    
    
    
    function access_denied()
    {
        return '<div class="alert alert-warning" role="alert">
                    Доступ ограничен. Возможно, надо просто <a href="' . get_site_url() . '/wp-login.php?redirect_to=' . get_permalink() . '">войти на сайт</a>.
                    </div>';
    }
    


            
    // 
    // Выноска
    // 
    
    public static function get_callout( $text, $class = 'body' )
    {
        $out = '';

        // $out .= '<div class="callout mt-4 mb-4 pt-2 pb-2 bg-' . $class . '-subtle border-start border-5 border-' . $class . '-subtle">';
        $out .= '<div class="callout mt-4 mb-4 p-3 bg-' . $class . '-subtle border-start border-5 border-' . $class . '-subtle">';
        $out .= $text;
        $out .= '</div>';

        return apply_filters( 'mif_mr_part_core_get_callout', $out, $text, $class );
    }
    


    // //
    // // Получить URL ОПОП
    // //

    // public function get_opop_url()
    // {
    //     global $tree;
    //     return get_permalink( $tree['main']['id'] );
    // }



    // //
    // // Получить ссылку поста
    // //

    // public function get_link_post( $id, $type = '' )
    // {
    //     if ( (int) $id === 0 ) return $id;

    //     // $post = get_post( $id );
        
    //     // if ( empty( $post ) ) return $id;
    //     // if ( ! empty( $type ) && $post->post_type != $type ) return $id;
        
    //     if ( ! get_post_type( $id ) && get_post_type( $id ) != $type ) return $id;

    //     $out = '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';

    //     return apply_filters( 'mif_mr_core_get_link_post', $out, $id, $type );
    // }



    // //
    // // Получить ссылку пользователей
    // //

    // public function get_link_user( $login )
    // {

    //     $user = get_user_by( 'login', $login );

    //     if ( empty( $user ) ) return $login;
        
    //     // p($user);

    //     // if ( function_exists( 'bp_core_get_user_domain' ) ) {
    //     //     $user_url = bp_core_get_user_domain( $user_id );
    //     // } else {
    //     //     $user_url = get_the_author_link( $user_id );
    //     // }

    //     $out = $user->display_name . ' (' . $user->user_login . ')';

    //     return apply_filters( 'mif_mr_core_get_link_user', $out );
    // }



    
    // 
    // link_edit_advanced
    // 
    
    public function get_link_edit_advanced( $id = NULL )
    {
        $out = '';

        $f = ( empty( get_edit_post_link( $id ) ) ) ? false : true;
        
        if ( $this->user_can(3) ) {
            
            $out .= '<div class="row mt-1">';
            $out .= '<div class="col-12 p-0 mb-3">';
            if ( $f ) $out .= '<a href="' . get_edit_post_link( $id ) . '" target="_blank">';
            $out .= 'Расширенный редактор';
            if ( $f ) $out .= '</a>';
            $out .= '</div>';
            $out .= '</div>';

        }

        return apply_filters( 'mif_mr_link_edit_advanced', $out );
    }
    

    

    //
    // Получить время в человекопонятном формате
    //
    
    public function get_time()
    {
        // $time = ( function_exists( 'current_time' ) ) ? current_time( 'mysql' ) : date( 'r' );
        $time = current_time( 'mysql' );
        return apply_filters( 'mif_mr_core_get_time', $time );
    }



    //
    //  
    //
    
    public static function mb_substr( $s, $length )
    {
        $out = mb_substr( $s, 0, $length - 3 );
        if ( mb_strlen( $s ) >= $length ) $out .= '...';
        return apply_filters( 'mif_mr_functions_mb_substr', $out );
    }
    



    //
    //  
    //
    
    public static function get_ext( $file )
    {
        $a = explode( '.', $file );
        return array_pop( $a );
    }
    



    //
    //  
    //
    
    public static function get_att_id()
    {
        $att_id = NULL;
        
        global $wp_query;
        if ( isset( $wp_query->query_vars['id'] ) ) $att_id = $wp_query->query_vars['id'];

        return $att_id;
    }
    



}

?>