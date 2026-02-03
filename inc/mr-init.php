<?php

//
// Инит-файл плагина Matrix
// 
//



defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/mr-functions.php';
include_once dirname( __FILE__ ) . '/catalog-screen.php';
include_once dirname( __FILE__ ) . '/opop-screen.php';

include_once dirname( __FILE__ ) . '/part-core.php';
include_once dirname( __FILE__ ) . '/part-companion.php';
include_once dirname( __FILE__ ) . '/part-table.php';
include_once dirname( __FILE__ ) . '/part-templates.php';

include_once dirname( __FILE__ ) . '/part-param.php';
include_once dirname( __FILE__ ) . '/part-courses.php';
include_once dirname( __FILE__ ) . '/part-matrix.php';
include_once dirname( __FILE__ ) . '/part-curriculum.php';

include_once dirname( __FILE__ ) . '/set-core.php';
include_once dirname( __FILE__ ) . '/set-competencies.php';
include_once dirname( __FILE__ ) . '/set-courses.php';
include_once dirname( __FILE__ ) . '/set-references.php';

include_once dirname( __FILE__ ) . '/companion-lib-core.php';
include_once dirname( __FILE__ ) . '/companion-lib-competencies.php';
include_once dirname( __FILE__ ) . '/companion-lib-competencies-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-courses.php';
include_once dirname( __FILE__ ) . '/companion-lib-courses-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-references.php';
include_once dirname( __FILE__ ) . '/companion-lib-references-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-templates.php';

include_once dirname( __FILE__ ) . '/tools-core.php';
include_once dirname( __FILE__ ) . '/tools-curriculum.php';
include_once dirname( __FILE__ ) . '/tools-templates.php';

include_once dirname( __FILE__ ) . '/lib-download.php';
include_once dirname( __FILE__ ) . '/lib-upload.php';
include_once dirname( __FILE__ ) . '/lib-xlsx-core.php';
include_once dirname( __FILE__ ) . '/lib-docx-core.php';


include_once dirname( __FILE__ ) . '/classes/modules-class.php';
include_once dirname( __FILE__ ) . '/classes/matrix-class.php';
include_once dirname( __FILE__ ) . '/classes/curriculum-class.php';
// include_once dirname( __FILE__ ) . '/classes/assessments-class.php';
include_once dirname( __FILE__ ) . '/classes/evaluations-class.php';
include_once dirname( __FILE__ ) . '/classes/authors-class.php';
include_once dirname( __FILE__ ) . '/classes/biblio-class.php';
include_once dirname( __FILE__ ) . '/classes/content-class.php';
include_once dirname( __FILE__ ) . '/classes/it-class.php';
include_once dirname( __FILE__ ) . '/classes/mto-class.php';
include_once dirname( __FILE__ ) . '/classes/parts-class.php';
include_once dirname( __FILE__ ) . '/classes/plx-class.php';
// include_once dirname( __FILE__ ) . '/classes/.php';






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
        add_action( 'wp_head', array( $this, 'ajaxurl' ) );
        add_filter( 'the_content', array( $this, 'content' ) );
        
        add_action( 'wp_ajax_catalog', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_catalog', array( $this, 'ajax' ) );

        add_action( 'wp_ajax_courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_matrix', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_matrix', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_curriculum', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_curriculum', array( $this, 'ajax' ) );
        
        add_action( 'wp_ajax_lib-competencies', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_lib-courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_lib-references', array( $this, 'ajax' ) );
        
        add_action( 'wp_ajax_tools-curriculum', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_tools-curriculum', array( $this, 'ajax' ) );
        
        
        // add_action( 'wp_ajax_edit', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_cancel', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_save', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_remove', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_create', array( $this, 'ajax' ) );

        // add_action( 'wp_ajax_nopriv_edit', array( $this, 'ajax' ) );
      
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
    // Точка входа для AJAX-запросов 
    // 

    public function ajax()
    // public function ajax_catalog_submit()
    {   
        // echo H// f($_REQUEST);
        // p($_REQUEST);
        
        check_ajax_referer( 'mif-mr' );
        
        // p($_REQUEST);

        if ( isset( $_REQUEST['action'] ) ) {
            
            if ( $_REQUEST['action'] == 'catalog' ) {

                $m = new mif_mr_catalog();
                echo $m->get_catalog();

            } else {
                
                global $mif_mr_opop;
                $mif_mr_opop = new mif_mr_opop();
                
                // global $tree;
                // p( $tree['main'] );

                
                if ( $_REQUEST['action'] == 'courses' ) {
                    
                    $m = new mif_mr_courses();
                    echo $m->get_courses();
                
                } 
    
                if ( $_REQUEST['action'] == 'matrix' ) {
                    
                    $m = new mif_mr_matrix();
                    echo $m->get_matrix();
                
                } 
    
                if ( $_REQUEST['action'] == 'curriculum' ) {
                    
                    $m = new mif_mr_curriculum();
                    echo $m->get_curriculum();
                
                } 

                if ( $_REQUEST['action'] == 'tools-curriculum' ) {
                    
                    if ( $_REQUEST['do'] == 'remove' ) {
                        
                        $m = new mif_mr_tools_curriculum();
                        echo $m->remove( (int) $_REQUEST['attid'] );
                        
                    }     
                
                    if ( $_REQUEST['do'] == 'save' ) {
                        
                        $m = new mif_mr_tools_curriculum();
                        echo $m->save_comp( array(
                                                'opop_title' => sanitize_text_field( $_REQUEST['opop_title'] ),
                                                'explanation' => sanitize_text_field( $_REQUEST['explanation'] ),
                                                'opop_id' => (int) $_REQUEST['opop'],
                                                'att_id' => (int) $_REQUEST['attid'],
                                                'key' => sanitize_key( $_REQUEST['key'] ),
                                                'yes' => sanitize_key( $_REQUEST['yes'] ),
                                                'data' => sanitize_textarea_field( $_REQUEST['data'] ),
                                                ) );
                        
                    }     
                
                
                    if ( $_REQUEST['do'] == 'analysis' ) {
                        
                        $m = new mif_mr_tools_curriculum();
                        echo $m->analysis( array(
                                                'opop_title' => sanitize_text_field( $_REQUEST['opop_title'] ),
                                                'opop_id' => (int) $_REQUEST['opop'],
                                                'att_id' => (int) $_REQUEST['attid'],
                                                'key' => sanitize_key( $_REQUEST['key'] ),
                                                // 'yes' => sanitize_key( $_REQUEST['yes'] ),
                                                'data' => sanitize_textarea_field( $_REQUEST['data'] ),
                                                // 'data' => $_REQUEST['data'],
                                                ) );
                        
                    }     
                



                    // p($_REQUEST);
                    // $m = new mif_mr_curriculum();
                    // echo $m->get_curriculum();
                
                } 


                if ( $_REQUEST['action'] == 'lib-competencies' ) {
                    
                
                    if ( in_array( $_REQUEST['do'], array( 'edit', 'cancel' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_comp_sub( (int) $_REQUEST['sub'], (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'save' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_comp( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-competencies' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                       
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_lib_comp( (int) $_REQUEST['opop'] );
    
                    } 
                    
                } 


                if ( $_REQUEST['action'] == 'lib-courses' ) {
                    
                
                    if ( in_array( $_REQUEST['do'], array( 'edit', 'cancel' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_course_part( array(
                                                'course_id' => (int) $_REQUEST['comp'],
                                                'sub_id' => sanitize_key( $_REQUEST['sub'] ),
                                                'part' => sanitize_key( $_REQUEST['part'] ),
                                                'name' => sanitize_text_field( $_REQUEST['name'] ),
                                                'coll' => true,
                                                ) );
                    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'save' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_course( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-courses' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                        
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_lib_courses( (int) $_REQUEST['opop'] );
    
                    } 
                    
                } 
    
                
                
                if ( $_REQUEST['action'] == 'lib-references' ) {
                    
                
                    // if ( in_array( $_REQUEST['do'], array( 'edit', 'cancel' ) ) ) {
                    //     // p($_REQUEST);
                    //     $m = new mif_mr_lib_courses_screen();
                    //     echo $m->get_course_part( array(
                    //                             'course_id' => (int) $_REQUEST['comp'],
                    //                             'sub_id' => sanitize_key( $_REQUEST['sub'] ),
                    //                             'part' => sanitize_key( $_REQUEST['part'] ),
                    //                             'name' => sanitize_text_field( $_REQUEST['name'] ),
                    //                             'coll' => true,
                    //                             ) );
                    
                    // } 
                    
                    // if ( in_array( $_REQUEST['do'], array( 'save' ) ) ) {
                    //     // p($_REQUEST);
                    //     $m = new mif_mr_lib_courses_screen();
                    //     echo $m->get_course( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                        
                    // } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_references_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-references' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                        
                        $m = new mif_mr_lib_references_screen();
                        echo $m->get_lib_references( (int) $_REQUEST['opop'] );
    
                    } 
                    
                } 
    
                
                
                
    
            }
            

        }

        wp_die();
    }



    function ajaxurl()
    {   
        // $data = array(
        //     'url' => admin_url( 'admin-ajax.php' ),
        // );
        ?>
        <script>
            ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        </script>
        <?php
    }


    // 
    // Добавляет новое правило перезаписи 
    // 

    private function rewrite_rule()
    {
        add_rewrite_tag('%part%', '([^&]+)');
        // add_rewrite_tag('%course%', '([^&]+)');
        add_rewrite_tag('%id%', '([^&]+)');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]','top');
        // add_rewrite_rule('^opop/([^/]*)/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]&course=$matches[3]','top');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]&id=$matches[3]','top');
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
            
            


        register_post_type( 'courses', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Список дисциплин', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Список дисциплин', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать cписок дисциплин', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание cписка дисциплин', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование cписок дисциплин', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый cписок дисциплин', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть cписок дисциплин', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Список дисциплин не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => __( 'Список дисциплин', 'mif-mr' ), // название меню
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
            'rewrite'             => array( 'slug' => 'courses' ),
            'query_var'           => true,

        ) );


        register_post_type( 'matrix', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Матрица компетенций', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Матрица компетенций', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать матрицу компетенций', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание матрицу компетенций', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование матрицы компетенций', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новая матрица компетенций', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть матрицу компетенций', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Матрица компетенций не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => __( 'Матрица компетенций', 'mif-mr' ), // название меню
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
            'rewrite'             => array( 'slug' => 'matrix' ),
            'query_var'           => true,

        ) );




        register_post_type( 'curriculum', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Учебный план', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Учебный план', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать учебный план', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание учебный план', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование учебный план', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый учебный план', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть учебный план', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Учебный план не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => __( 'Учебный план', 'mif-mr' ), // название меню
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
            'rewrite'             => array( 'slug' => 'curriculum' ),
            'query_var'           => true,

        ) );    
        


        
        register_post_type( 'lib-competencies', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Библиотека компетенций', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Список компетенций', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать список компетенций', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание список компетенций', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование список компетенций', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый список компетенций', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть список компетенций', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Список компетенций не найден', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
            'rewrite'             => array( 'slug' => 'lib-competencies' ),
            'query_var'           => true,

        ) );

        
        
        

        
        register_post_type( 'lib-courses', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Библиотека дисциплин', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Дисциплина', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать дисциплину', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание дисциплину', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование дисциплину', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый дисциплины', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть дисциплину', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Дисциплины не найден', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
            'rewrite'             => array( 'slug' => 'lib-coorses' ),
            'query_var'           => true,
            
            ) );
                    
        

        
        register_post_type( 'lib-references', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Библиотека справочников', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Справочник', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать справочник', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание справочник', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование справочник', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый справочник', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть справочник', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Справочник не найден', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
            'rewrite'             => array( 'slug' => 'lib-references' ),
            'query_var'           => true,
            
            ) );
            
            
            
            register_post_type( 'set-competencies', array(
                'label'  => null,
                'labels' => array(
                    'name'               => __( 'Настройки компетенций', 'mif-mr' ), // основное название для типа записи
                    'singular_name'      => __( 'Настройки компетенций', 'mif-mr' ), // название для одной записи этого типа
                    'add_new'            => __( 'Создать настройки компетенций', 'mif-mr' ), // для добавления новой записи
                    'add_new_item'       => __( 'Создание настройки компетенций', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item'          => __( 'Редактирование настройки компетенций', 'mif-mr' ), // для редактирования типа записи
                    'new_item'           => __( 'Новый настройки компетенций', 'mif-mr' ), // текст новой записи
                    'view_item'          => __( 'Посмотреть настройки компетенций', 'mif-mr' ), // для просмотра записи этого типа.
                    'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                    'not_found'          => __( 'Настройки компетенций не найден', 'mif-mr' ), // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                    'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
                'rewrite'             => array( 'slug' => 'set-competencies' ),
                'query_var'           => true,
    
            ) );
            
            
            
            register_post_type( 'set-courses', array(
                'label'  => null,
                'labels' => array(
                    'name'               => __( 'Настройки дисциплин', 'mif-mr' ), // основное название для типа записи
                    'singular_name'      => __( 'Настройки дисциплин', 'mif-mr' ), // название для одной записи этого типа
                    'add_new'            => __( 'Создать настройки дисциплин', 'mif-mr' ), // для добавления новой записи
                    'add_new_item'       => __( 'Создание настройки дисциплин', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item'          => __( 'Редактирование настройки дисциплин', 'mif-mr' ), // для редактирования типа записи
                    'new_item'           => __( 'Новый настройки дисциплин', 'mif-mr' ), // текст новой записи
                    'view_item'          => __( 'Посмотреть настройки дисциплин', 'mif-mr' ), // для просмотра записи этого типа.
                    'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                    'not_found'          => __( 'Настройки дисциплин не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                    'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
                'rewrite'             => array( 'slug' => 'set-courses' ),
                'query_var'           => true,
    
            ) );





            register_post_type( 'set-references', array(
                'label'  => null,
                'labels' => array(
                    'name'               => __( 'Настройки справочников', 'mif-mr' ), // основное название для типа записи
                    'singular_name'      => __( 'Настройки справочников', 'mif-mr' ), // название для одной записи этого типа
                    'add_new'            => __( 'Создать настройки справочников', 'mif-mr' ), // для добавления новой записи
                    'add_new_item'       => __( 'Создание настройки справочников', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item'          => __( 'Редактирование настройки справочников', 'mif-mr' ), // для редактирования типа записи
                    'new_item'           => __( 'Новый настройки справочника', 'mif-mr' ), // текст новой записи
                    'view_item'          => __( 'Посмотреть настройки справочника', 'mif-mr' ), // для просмотра записи этого типа.
                    'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                    'not_found'          => __( 'Настройки справочник не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                    'parent_item_colon'  => '', // для родителей (у древовидных типов)
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
                'rewrite'             => array( 'slug' => 'set-references' ),
                'query_var'           => true,
    
            ) );
            
            // register_post_type( 'courses', array(
        //     'label'  => null,
        //     'labels' => array(
        //         'name'               => __( 'Дисциплина', 'mif-mr' ), // основное название для типа записи
        //         'singular_name'      => __( 'Дисциплина', 'mif-mr' ), // название для одной записи этого типа
        //         'add_new'            => __( 'Создать дисциплину', 'mif-mr' ), // для добавления новой записи
        //         'add_new_item'       => __( 'Создание дисциплину', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
        //         'edit_item'          => __( 'Редактирование дисциплину', 'mif-mr' ), // для редактирования типа записи
        //         'new_item'           => __( 'Новая дисциплина', 'mif-mr' ), // текст новой записи
        //         'view_item'          => __( 'Посмотреть дисциплину', 'mif-mr' ), // для просмотра записи этого типа.
        //         'search_items'       => __( 'Найти дисциплину', 'mif-mr' ), // для поиска по этим типам записи
        //         'not_found'          => __( 'Дисциплина не найдена', 'mif-mr' ), // если в результате поиска ничего не было найдено
        //         'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
        //         'parent_item_colon'  => '', // для родителей (у древовидных типов)
        //         'menu_name'          => __( 'Дисциплина', 'mif-mr' ), // название меню
        //     ),
        //     'description'         => '',
        //     'public'              => true,
        //     'publicly_queryable'  => null,
        //     'exclude_from_search' => null,
        //     'show_ui'             => null,
        //     'show_in_menu'        => true, // показывать ли в меню адмнки
        //     'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
        //     'show_in_nav_menus'   => null,
        //     'show_in_rest'        => null, // добавить в REST API. C WP 4.7
        //     'rest_base'           => null, // $post_type. C WP 4.7
        //     'menu_position'       => 20,
        //     'menu_icon'           => 'dashicons-forms', 
        //     'capability_type'   => 'post',
        //     //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
        //     'map_meta_cap'      => true, // Ставим true чтобы включить дефолтный обработчик специальных прав
        //     'hierarchical'        => false,
        //     'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        //     'taxonomies'          => array(),
        //     'has_archive'         => true,
        //     'rewrite'             => array( 'slug' => 'courses' ),
        //     'query_var'           => true,

        // ) );


    }


}

?>