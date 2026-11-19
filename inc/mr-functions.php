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





    // function level_access( $post = NULL, $user_login = NULL ) 
    function level_access( $user_login = NULL ) 
    {
        //  http://codex.wordpress.org/Roles_and_Capabilities
        
        // 0 - ничего не умеет
        // 1 - просмотр общедоступной информации
        // 2 - просмотр всей информации ОПОП
        // 3 - изменение информации ОПОП
        // 4 - редактор
        // 5 - админ
    
        global $tree;

        if ( ! is_user_logged_in() ) return 0;

        if ( current_user_can( 'administrator' ) ) return 5;
        if ( current_user_can( 'editor' ) ) return 4;
        
        if ( $user_login === NULL ) $user_login = wp_get_current_user()->user_login;

        if ( isset( $tree['param']['admins']['data'] ) )
            foreach ( $tree['param']['admins']['data'] as $u ) if ( $u == $user_login ) return 3;
        
        if ( isset( $tree['param']['users']['data'] ) )
            foreach ( $tree['param']['users']['data'] as $u ) if ( $u == $user_login ) return 2;

        // p(current_user_can( 'subscriber' ));

        // if ( current_user_can( 'subscriber' ) ) return 1;
        
        return 1;
    
    }
    
    
    function user_can( $level_access, $post = NULL )
    {
        if ( $this->level_access() >= $level_access ) return true;
        return false;
    }
    
    
    
    function access_denied()
    {
        // return '<div class="alert alert-warning" role="alert">
        //             Доступ ограничен. Возможно, надо просто <a href="' . get_site_url() . '/wp-login.php?redirect_to=' . get_permalink() . '">войти на сайт</a>.
        //             </div>';

        $out = '';
        
        $out .= '<div class="mt-6 mb-6">';
        
        $out .= $this->get_callout( '<p class="mt-2 mb-2">Доступ ограничен. Возможно, надо просто <a href="' . 
                                    get_site_url() . '/wp-login.php?redirect_to=' . get_permalink() . '">войти на сайт</a></p>', 'warning' );
        
        $out .= '<p><br /><br /></p>';
        $out .= '<p><br /><br /></p>';
        $out .= '<p><br /><br /></p>';
        $out .= '</div>';


        return apply_filters( 'mif_mr_part_core_access_denied', $out );
    }
    


            
    function get_widget_user()
    {
        if ( ! is_user_logged_in() ) return;

        $user = wp_get_current_user(); 

        $out = '';

        $out .= '<div class="row mb-5">';
        $out .= '<div class="col bg-light border rounded no-gutters p-3">';
        
        $out .= '<div>' . $this->get_avatar( $user->user_login ) . '</div>';  
        $out .= '<div>' . $this->get_display_name( $user->user_login ) . '</div>';  
        $out .= '<div>Статус: ' . $this->get_user_can( $user->user_login ) . '</div>';  
        
        $out .= '</div>';
        $out .= '</div>';


        return apply_filters( 'mif_mr_get_avatar', $out );
    }

            



    function get_avatar( $user_login = NULL )
    {
        // if ( ! is_user_logged_in() ) return;

        // $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'slug', $user_login );
        $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'login', $user_login );

        if ( function_exists( 'bp_is_active' ) && bp_is_active() ) {

            $a1 = '<a href="' . network_home_url() . 'members/' . $user->user_nicename . '" title="' . $user->display_name . '">';
            $a2 = '</a>';
            
        } else {
            
            $a1 = '<span title="' . $user->display_name . '">';
            $a2 = '</span>';

        }

        $out = '';
        $out .= $a1 . get_avatar( $user->ID, 50 ) . $a2;  
        // $out .= '<div>' . $a1 . get_avatar( $user->ID, 50 ) . $a2 . '</div>';  
        
        // if ( $is_display_name ) $out .= '<div>' . $this->get_display_name() . '</div>';  
        // if ( $is_user_can ) $out .= '<div>Статус: ' . $this->get_user_can() . '</div>';  

        return apply_filters( 'mif_mr_get_avatar', $out );
    }
    

    
    
    function get_display_name( $user_login = NULL )
    {
        // if ( ! is_user_logged_in() ) return;

        // $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'slug', $user_login );
        $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'login', $user_login );

        $out = '';
        $out .= $user->display_name;  

        return apply_filters( 'mif_mr_get_display_name', $out );
    }

    
    
    function get_user_can( $user_login = NULL )
    {
        // if ( ! is_user_logged_in() ) return;

        // $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'slug', $user_login );
        $user = ( $user_login === NULL ) ? wp_get_current_user() : get_user_by( 'login', $user_login );

        $user_can = apply_filters( 'mif_mr_user_can', array(  
                                                                // 0 => '', // ничего не умеет
                                                                1 => 'посетитель', // просмотр общедоступной информации
                                                                2 => 'читатель', // просмотр всей информации ОПОП
                                                                3 => 'редактор ОПОП', // изменение информации ОПОП
                                                                4 => 'редактор сайта', // редактор
                                                                5 => 'администратор', // админ
                                                            ) );

        $out = '';
        $out .= $user_can[ $this->level_access() ];  

        return apply_filters( 'mif_mr_get_user_can', $out );
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
    



    //
    //  
    //
    
    public static function get_p( $s = '' )
    {   
        $s = str_replace( '<', '&lt;', $s );
        $s = str_replace( '>', '&gt;', $s );
        return '<p>' . str_replace( "\n", '</p><p>', $s ) . '</p>';
    }
    



    //
    // Показать ссылку на шаблон (д.р.)
    //
    
    public function get_download_link( $type = 'text' )
    {
        global $mr;
        if ( ! $mr->user_can(2) ) return;

        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            if ( $type == 'x-tpl' ) $a = array( 'course-x-tpl', 'Шаблон' );
            // if ( $type == 'program' ) $a = array( 'course-x-tpl', 'Программа' );
            if ( $type == 'program' ) $a = array( 'course-d-program', 'Прог-ма' );
            if ( $type == 'text' ) $a = array( 'text-raw', 'Текст' );


            $out .= '<div class="mb-3">'; 
            $out .= '<a href="?download=' . $a[0]. '"><span class="mr-btn mr-gray rounded"><i class="fa fa-download" aria-hidden="true"></i></span>' . $a[1] . '</a>';
            $out .= '</div>'; 
        
        }

        return apply_filters( 'mif_mr_get_template', $out, $type );
        
    }

    
}

?>