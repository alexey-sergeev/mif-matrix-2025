<?php

//
// Инит-файл плагина Matrix
// 
//



defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/mr-functions.php';
include_once dirname( __FILE__ ) . '/catalog-screen.php';
include_once dirname( __FILE__ ) . '/opop-screen.php';

include_once dirname( __FILE__ ) . '/part-param.php';
include_once dirname( __FILE__ ) . '/part-templates.php';
// include_once dirname( __FILE__ ) . '/shortcode-opops-list.php';

// include_once dirname( __FILE__ ) . '/qm-workroom.php';
// include_once dirname( __FILE__ ) . '/qm-results.php';
// include_once dirname( __FILE__ ) . '/qm-profile.php';

// include_once dirname( __FILE__ ) . '/qm-global.php';
// include_once dirname( __FILE__ ) . '/qm-parser.php';

include_once dirname( __FILE__ ) . '/lib-download.php';
include_once dirname( __FILE__ ) . '/lib-xlsx-core.php';
include_once dirname( __FILE__ ) . '/lib-docx-core.php';

// include_once dirname( __FILE__ ) . '/quiz-core.php';
// include_once dirname( __FILE__ ) . '/quiz-screen.php';

// include_once dirname( __FILE__ ) . '/members-core.php';
// include_once dirname( __FILE__ ) . '/members-screen.php';

// include_once dirname( __FILE__ ) . '/invites-core.php';
// include_once dirname( __FILE__ ) . '/invites-screen.php';

// include_once dirname( __FILE__ ) . '/xml-core.php';
// include_once dirname( __FILE__ ) . '/process-process.php';

// include_once dirname( __FILE__ ) . '/process-snapshots.php';
// include_once dirname( __FILE__ ) . '/process-results.php';




class mif_mr_init extends mif_mr_functions { 
// class mif_mr_init { 
    
    
    // Названия домашней старницы, профиля и др.

    protected $post_name_catalog = 'home';
    // protected $post_name_help = 'help';



    function __construct()
    {
        parent::__construct();

        $this->rewrite_rule();
        $this->post_types_init();

        // add_action('init', array( $this, 'rewrite_rule' ) );
        add_filter( 'the_content', array( $this, 'content' ) );
        
        add_action( 'wp_ajax_catalog', array( $this, 'ajax_catalog_submit' ) );
        add_action( 'wp_ajax_nopriv_catalog', array( $this, 'ajax_catalog_submit' ) );
        
        // global $mif_mr_catalog_shortcode;
        // $mif_mr_catalog_shortcode = new mif_mr_catalog_shortcode();
        new mif_mr_catalog_shortcode();
        
    }
    
    
    
    //
    // Вывод теста на страницах
    //
    
    
    public function content( $content = '' )
    {
        if ( is_admin() ) return '';
        if ( wp_is_json_request() ) return '';

        global $post;
        
        // p($post);
        
        if ( $post->post_name == $this->post_name_catalog ) {
            
            global $mif_mr_catalog;
            $mif_mr_catalog = new mif_mr_catalog();
            
            $mif_mr_catalog->the_catalog();

            return '';
            
        } 
        
        if ( $post->post_type == 'opop' ) {
            
            // p('@');
            global $mif_mr_opop;
            $mif_mr_opop = new mif_mr_opop();
            
            $mif_mr_opop->the_opop();

            return '';

        } 

        return $content;
    }




    // 
    // Точка входа для AJAX-запросов (каталог главной страницы)
    // 

    public function ajax_catalog_submit()
    {
        // f($_REQUEST);
        check_ajax_referer( 'mif-mr' );

        if ( isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'stat' ) {

            // echo $this->get_catalog_stat();

        } else {

            $mif_mr_catalog = new mif_mr_catalog();
            echo  $mif_mr_catalog->get_catalog();

        }

        wp_die();
    }





    // 
    // Добавляет новое правило перезаписи 
    // 

    private function rewrite_rule()
    {
        add_rewrite_tag('%part%', '([^&]+)');
        add_rewrite_tag('%course%', '([^&]+)');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]','top');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]&course=$matches[3]','top');
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


    }


}

?>