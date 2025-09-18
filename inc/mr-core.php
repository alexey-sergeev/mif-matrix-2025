<?php

//
// Ядро инит-файл плагина Matrix
// 
//


defined( 'ABSPATH' ) || exit;




class mif_mr_core { 


    
    function __construct()
    {

        $this->post_types_init();

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
    // Иницализация типов записей
    // 

    private function post_types_init()
    {
        // 
        // Таксономия и тип записей - "ОПОП"
        // 

    
        register_taxonomy( 'opop_category', array( 'opop' ), array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __( 'Рубрики', 'mif-mr' ),
                'singular_name' => __( 'ОПОП', 'mif-mr' ),
                'search_items' =>  __( 'Найти', 'mif-mr' ),
                'all_items' => __( 'Все', 'mif-mr' ),
                'parent_item' => __( 'Родительская категория', 'mif-mr' ),
                'parent_item_colon' => __( 'Родительская категория:', 'mif-mr' ),
                'edit_item' => __( 'Редактировать категорию', 'mif-mr' ),
                'update_item' => __( 'Обновить категорию', 'mif-mr' ),
                'add_new_item' => __( 'Добавить новую категорию', 'mif-mr' ),
                'new_item_name' => __( 'Новое имя категории', 'mif-mr' ),
                'menu_name' => __( 'Рубрики', 'mif-mr' ),
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'opops' ),
        ) );


        
        register_post_type( 'opop', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'ОПОП', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'ОПОП', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать ОПОП', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание ОПОП', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование ОПОП', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новая ОПОП', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть ОПОП', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти ОПОП', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'ОПОП не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => __( 'ОПОП', 'mif-mr' ), // название меню
            ),
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => null,
            'exclude_from_search' => null,
            'show_ui'             => null,
            'show_in_menu'        => true, // показывать ли в меню адмнки
            'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
            'show_in_nav_menus'   => null,
            'show_in_rest'        => null, // добавить в REST API. C WP 4.7
            'rest_base'           => null, // $post_type. C WP 4.7
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-forms', 
            'capability_type'   => 'post',
            //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
            'map_meta_cap'      => true, // Ставим true чтобы включить дефолтный обработчик специальных прав
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
            'taxonomies'          => array(),
            'has_archive'         => true,
            'rewrite'             => array( 'slug' => 'opop' ),
            'query_var'           => true,

        ) );

        
    //     $process_snapshots = new mif_process_snapshots();
    //     $process_snapshots->post_types_init();

    //     $process_results = new mif_qm_process_results();
    //     $process_results->post_types_init();

    //     $members_core = new mif_qm_members_core();
    //     $members_core->post_types_init();

    //     $invites_core = new mif_qm_invites_core();
    //     $invites_core->post_types_init();

    }




}

?>