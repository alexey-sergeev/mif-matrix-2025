<?php

//
// Экранные методы ОПОП
// 
//


defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/opop-core.php';
include_once dirname( __FILE__ ) . '/opop-tree-raw.php';
include_once dirname( __FILE__ ) . '/opop-templates.php';

class mif_mr_opop extends mif_mr_opop_tree_raw {
    

    function __construct()
    {

        parent::__construct();
        
    }
    
    


    // 
    // Показывает ОПОП
    // 

    public function the_opop()
    {
        if ( $template = locate_template( 'mr-opop.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-opop.php', false );

        }
    }




    //
    // Главное меню
    //

    public function get_menu_item( $text, $url )
    {
        // global $mr;

        $out = '';
        if ( ! empty( $text ) ) $out .= '<div><a href=' . $this->get_opop_url() . $url . '>' . $text . '</a></div>';
        else $out .= '&nbsp;';
        
        return apply_filters( 'mif_mr_opop_get_menu_item', $out );
    }



    
    //
    // Часть
    //

    public function get_part()
    {
        // global $mr;
        global $wp_query;
        
        global $mif_mr_part;
        // $mif_mr_part = new mif_mr_part_core();
        $mif_mr_part = new mif_mr_part_companion();

        $out = '';
        // p($wp_query);
        $part = NULL; 
        if ( isset( $wp_query->query_vars['part'] ) ) $part = $wp_query->query_vars['part'];
        
        switch ( $part ) {
            
            case 'param':
                global $mif_mr_param;
                $mif_mr_param = new mif_mr_param();
                $mif_mr_param->the_show();
                // p('param');
            break;
            
            case 'courses':
                global $mif_mr_courses;
                $mif_mr_courses = new mif_mr_courses();
                $mif_mr_courses->the_show();
            break;
            
            case 'matrix':
                global $mif_mr_matrix;
                $mif_mr_matrix = new mif_mr_matrix();
                $mif_mr_matrix->the_show();
            break;
            
            case 'curriculum':
                global $mif_mr_curriculum;
                $mif_mr_curriculum = new mif_mr_curriculum();
                $mif_mr_curriculum->the_show();
            break;
            
            case 'lib-competencies':

                global $wp_query;
                
                if ( isset( $wp_query->query_vars['id'] ) ) {

                    global $mif_mr_comp;
                    // $mif_mr_comp = new mif_mr_comp();
                    $mif_mr_comp = new mif_mr_competencies_screen();
                    $mif_mr_comp->the_show();
                    
                } else {
                    
                    // global $mif_mr_lib_comp;
                    // $mif_mr_lib_comp = new mif_mr_lib_comp();
                    global $mif_mr_comp;
                    // $mif_mr_comp = new mif_mr_comp();
                    $mif_mr_comp = new mif_mr_competencies_screen();
                    
                    global $mif_mr_set_comp;
                    $mif_mr_set_comp = new mif_mr_set_comp();
                    $mif_mr_set_comp->the_show();
                    
                }
                
                break;
                
            case 'lib-courses':
                
                global $wp_query;
                    
                if ( isset( $wp_query->query_vars['id'] ) ) {
                        
                    global $mif_mr_lib_courses;
                    $mif_mr_lib_courses = new mif_mr_lib_courses_screen();
                    
                    $mif_mr_lib_courses->the_show();
                    
                } else {
                    
                    global $mif_mr_lib_courses;
                    $mif_mr_lib_courses = new mif_mr_lib_courses_screen();
                    
                    global $mif_mr_set_courses;
                    $mif_mr_set_courses = new mif_mr_set_courses();
                    $mif_mr_set_courses->the_show();

                }

            break;
            
            case 'tools':

                global $mif_mr_tools;
                $mif_mr_tools = new mif_mr_tools_core();
                $mif_mr_tools->the_show();

            break;
            
            case 'tools-curriculum':

                global $mif_mr_tools_curriculum;
                $mif_mr_tools_curriculum = new mif_mr_tools_curriculum();
                $mif_mr_tools_curriculum->the_show();

            break;
            
            case 'stat':
                global $tree;
                p($tree);
            break;
            
            default:
                p('home');
                break;
        
        }

        return apply_filters( 'mif_mr_opop_get_part', $out );
    }



    
    //
    // 
    //

    public function show_messages()
    {
        global $messages;

        $out = '';

        foreach ( $messages as $item ) {

            $class = 'primary';
            if ( isset( $item[1] ) ) $class = $item[1];

            $out .= mif_mr_functions::get_callout( $item[0], $class );


            // $out .= '<div class="alert alert-' . $class . '" role="alert">';
            // $out .= $item[0];
            // $out .= '</div>';
            
        }

        $messages = array();
        
        return apply_filters( 'mif_mr_opop_show_messages', $out );
    }
    
    
    
    //
    // 
    //
    
    public function show_explanation( $key = NULL )
    {
        global $explanation;

        if ( ! isset( $_REQUEST['edit'] ) ) return;
        
        $out = '';
        
        if ( isset( $key ) && isset( $explanation[$key] ) ) {
            
            $out .= '<div class="col pt-2 pb-2 mt-3">';
            $out .= $explanation[$key];
            $out .= '</div>';
            
        }
        
        return apply_filters( 'mif_mr_opop_explanation', $out );
    }
    
    
    
    //
    // 
    //
    
    public function show_panel( $type = 'courses' )
    {
        if ( isset( $_REQUEST['edit'] ) ) return;

        $out = '';

        $out .= '<div class="col-auto p-0 mb-3">';
        $out .= '<form>';
        
        $out .= '<ul class="nav nav-tabs pb-0 nav-tabs-0">';
        $out .= '<li class="nav-item"><label class="nav-link mb-0 active"><input type="radio" class="d-none" name="key" value="modules" checked="" />Модули</label></li>';
        $out .= '<li class="nav-item"><label class="nav-link mb-0"><input type="radio" class="d-none" name="key" value="courses" />Дисциплины</label></li>';
        $out .= '</ul>';
            
        $out .= '<input type="hidden" name="action" value="' . $type . '" />';
        $out .= '<input type="hidden" name="opop" value="' . $this->get_opop_id() . '" />';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';     
        
        $out .= '</form>';
        $out .= '</div>';
        
        $out .= '<div class="col d-none d-sm-block mt-5 pr-0 text-end">';
        $out .= '<a href="#" class="text-secondary" id="fullsize">';
        $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
        $out .= '</a>';
        $out .= '</div>';
    
        return apply_filters( 'mif_mr_opop_panel', $out, $type );
    }


    //
    // 
    //
    
    public function show_back( $type = 'courses' )
    {
        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            $out .= '<div class="pb-4"><a href="' . get_permalink( $this->get_opop_id() ) . $type . '/">← Вернуться к странице раздела</a></div>';
        
        } 
        
        return apply_filters( 'mif_mr_opop_show_back', $out, $type );
    }










    // //
    // // Каталог ОПОП
    // //
    
    // public function get_catalog()
    // {
    //     $out = '';
        
    //     $page = ( isset( $_REQUEST['page'] ) ) ? (int) $_REQUEST['page'] : 1;
        
    //     // Выбрать записи из базы данных
        
    //     $args = $this->get_catalog_args( $page );
    //     $opops = get_posts( $args );
  
    //     if ( $page == 1 && count( $opops ) == 0 ) {

    //         // Нет тестов по таким криетиям поиска

    //         $out .= '<div class="col-12 p-3 alert"><div class="bg-light p-5 mb-5 text-center">';
    //         $out .= '<p class="text-secondary mt-4"><i class="fas fa-3x fa-ellipsis-h"></i></p>';
    //         // $out .= '<h4 class="mb-5">' . __( 'Ничего не найдено', 'mif-mr' ) . '</h4>';
    //         $out .= '<p class="mb-5">' . __( 'Нужных вам образовательных программ нет, но есть много других.', 'mif-mr' ) . '<br />';
    //         $out .= __( 'Измените критерии поиска, чтобы что-то найти.', 'mif-mr' ) . '</p>';
    //         $out .= '</div></div>';
            
    //     } else {

    //         // Вывести элементы каталога

    //         $last_opop_id = NULL;
            
    //         foreach ( (array) $opops as $opop ) {
                
    //             $out .= $this->get_catalog_item( $opop );
    //             $last_opop_id = $opop->ID;
                
    //         }

    //     }
        
    //     $out .= '<div class="col-12 text-center next-page p-6">';
        
    //     // Вывести скрытые поля с данными
        
    //     $out .= '<input type="hidden" name="action" value="catalog" />';
    //     $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';     
        
    //     $next = $page + 1;
    //     $out .= '<input type="hidden" name="page" value="' . $next . '" />';
        
        
    //     foreach ( array('department', 'degree', 'period', 'form' ) as $item ) {
            
    //         $value = ( isset( $_REQUEST['categories'][$item] ) ) ? sanitize_key( $_REQUEST['categories'][$item] ) : "all";
    //         $out .= '<input type="hidden" name="categories[' . $item . ']" value="' . $value . '" />';
            
    //     }
        
    //     // // Выбрать последнюю запись с указанными критериями
        
    //     // $args['order'] = 'ASC';
    //     // $args['posts_per_page'] = 1;
    //     // unset( $args['paged'] );
    //     // p($args);
    //     // $opops = get_posts( $args );
        
    //     // Выбрать последнюю запись с указанными критериями
        
    //     $args['order'] = 'DESC';
    //     $args['posts_per_page'] = 1;
    //     unset( $args['paged'] );
    //     $opops = get_posts( $args );
        
    //     // Кнопка "Показать еще" - если есть, что показывать

    //     if ( isset( $opops[0] ) && $opops[0]->ID != $last_opop_id ) $out .= '<button class="btn btn-lg btn-primary">' . __( 'Показать ещё', 'mif-mr' ) . '</button>';
        
    //     $out .= '<div class="loading" style="display: none"><i class="fas fa-spinner fa-3x fa-spin"></i></div>';
    //     $out .= '</div>';
        
    //     return apply_filters( 'mif_mr_screen_get_catalog', $out, $page );
    // }
    
    
    
    // //
    // // Элемент каталога ОПОП
    // //
    
    // public function get_catalog_item( $opop )
    // {
    //     if ( empty( $opop ) ) return '';
        
    //     $link = get_permalink( $opop->ID );
    //     $title = $opop->post_title;
    //     $excerpt = $opop->post_excerpt;
        

    //     $department = $this->get_category_list( $opop->ID, 'department' );
    //     $degree = $this->get_category_list( $opop->ID, 'degree', 'name' );
    //     $period = $this->get_category_list( $opop->ID, 'period' );
    //     $form = $this->get_category_list( $opop->ID, 'form' );
        
    //     $list = array_merge( $degree, $period, $form );
        
    //     $logo = $this->get_category_list( $opop->ID, 'logo', 'slug' );
    //     if ( empty( $logo ) ) $logo[] = 'default'; 
        
    //     $arr_logo_img = array();
        
    //     $color = $this->get_cover_color( $opop->ID );
    //     $style = ( ! empty( $color ) ) ? ' style="background-color: ' . $color . ';"' : '';

    //     foreach ( $logo as $item )                 
    //         $arr_logo_img[] = '<img src="' . plugins_url( 'images/logo/', __DIR__ ) . $item . '.png">';
  
    //     $out = '';

    //     $out .= '<div class="p-3 col-12 col-md-6 col-xl-4"><div class="card bg-light h-100">';
    //     $out .= '<a href="' . $link . '"><div class="cover text-center"' . $style . '>' . implode( "", $arr_logo_img ) . '</div></a>';
    //     $out .= '<div class="card-block p-3">';
    //     $out .= '<h4 class="mb-4 card-title"><a href="' . $link . '">' . $title . '</a></h4>';
    //     $out .= '<p class="mb-4"><strong><small>' . implode( ', ', $department ) . '</small></strong></p>';
    //     $out .= '<p class="mb-4"><small>' . implode( ', ', $list ) . '</small></p>';
    //     $out .= '</div>';
    //     $out .= '</div></div>';
        
    //     return apply_filters( 'mif_qm_screen_get_catalog_item', $out, $opop );
    // }
    
    
    
    // //
    // // Категории ОПОП
    // //
    
    // public function get_category()
    // {
    //     $out = '';

    //     $out .= $this->get_category_item( 'department' );
    //     $out .= $this->get_category_item( 'degree' );
    //     $out .= $this->get_category_item( 'period' );
    //     $out .= $this->get_category_item( 'form' );
        
    //     return apply_filters( 'mif_mr_screen_get_category', $out );
    // }
    
    
    
    // //
    // // Элемент каталога ОПОП
    // //
    
    // public function get_category_item( $key )
    // {
    //     $out = '';
        
    //     $arr = $this->get_category_arr();
        
    //     $out .= '<div class="row mt-4 ' . $key . '">';
    //     $out .= '<div class="col-2 col-xl-1 d-none d-sm-block"><img src="' . plugins_url( 'images/icons/', __DIR__ ) . $key . '.png" class="icons "></div>';
    //     $out .= '<div class="col">';
    //     $out .= '<button type="button" class="btn btn-primary mr-1 mt-1" data-key="' . $key . '" data-value="all">';
    //     $out .=  __( 'Всё', 'mif-mr' );
    //     $out .= '</button>';
        
    //     foreach ( (array) $arr[$key] as $item ) {

    //         $out .= '<button type="button" class="btn btn-secondary mr-1 mt-1" data-key="' . $key . '" data-value="' . $item['slug'] . '">';
    //         $out .= $item['name'];
    //         $out .= '</button>';
            
    //     }
        
    //     $out .= '</div>';
    //     $out .= '</div>';

    //     return apply_filters( 'mif_mr_screen_get_category_item', $out );
    // }
    
  

}

?>