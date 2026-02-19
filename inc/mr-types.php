<?php

//
// Типы данных плагина Matrix
// 
//



defined( 'ABSPATH' ) || exit;




class mif_mr_types extends mif_mr_functions { 

    
    function __construct()
    {
        parent::__construct();
        
        $this->post_types_init();
      
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
        


        register_post_type( 'attributes', array(
            'label'  => null,
            'labels' => array(
                'name'               => __( 'Атрибуты ОПОП', 'mif-mr' ), // основное название для типа записи
                'singular_name'      => __( 'Атрибуты ОПОП', 'mif-mr' ), // название для одной записи этого типа
                'add_new'            => __( 'Создать атрибуты', 'mif-mr' ), // для добавления новой записи
                'add_new_item'       => __( 'Создание атрибуты', 'mif-mr' ), // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => __( 'Редактирование атрибуты', 'mif-mr' ), // для редактирования типа записи
                'new_item'           => __( 'Новый атрибуты', 'mif-mr' ), // текст новой записи
                'view_item'          => __( 'Посмотреть атрибуты', 'mif-mr' ), // для просмотра записи этого типа.
                'search_items'       => __( 'Найти', 'mif-mr' ), // для поиска по этим типам записи
                'not_found'          => __( 'Атрибуты не найдены', 'mif-mr' ), // если в результате поиска ничего не было найдено
                'not_found_in_trash' => __( 'Не найдено в корзине', 'mif-mr' ), // если не было найдено в корзине
                'parent_item_colon'  => '', // для родителей (у древовидных типов)
                'menu_name'          => __( 'Атрибуты ОПОП', 'mif-mr' ), // название меню
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
            'rewrite'             => array( 'slug' => 'attributes' ),
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