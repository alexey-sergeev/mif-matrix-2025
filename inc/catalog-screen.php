<?php

//
// Экранные методы каталога
// 
//


defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/catalog-core.php';
include_once dirname( __FILE__ ) . '/catalog-shortcode.php';
include_once dirname( __FILE__ ) . '/catalog-templates.php';

class mif_mr_catalog extends mif_mr_catalog_core {
    

    function __construct()
    {

        parent::__construct();
        
    }
    
    


    // 
    // Показывает каталог
    // 

    public function the_catalog()
    {
        if ( $template = locate_template( 'mr-catalog.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-catalog.php', false );

        }
    }


    
    //
    // Каталог ОПОП
    //
    
    public function get_catalog()
    {
        $out = '';
        
        $page = ( isset( $_REQUEST['page'] ) ) ? (int) $_REQUEST['page'] : 1;
        
        // Выбрать записи из базы данных
        
        $args = $this->get_catalog_args( $page );
        $opops = get_posts( $args );
  
        if ( $page == 1 && count( $opops ) == 0 ) {

            // Нет тестов по таким криетиям поиска

            $out .= '<div class="col-12 p-3 alert"><div class="bg-light p-5 mb-5 text-center">';
            $out .= '<p class="text-secondary mt-4"><i class="fas fa-3x fa-ellipsis-h"></i></p>';
            // $out .= '<h4 class="mb-5">' . __( 'Ничего не найдено', 'mif-mr' ) . '</h4>';
            $out .= '<p class="mb-5">' . __( 'Нужных вам образовательных программ нет, но есть много других.', 'mif-mr' ) . '<br />';
            $out .= __( 'Измените критерии поиска, чтобы что-то найти.', 'mif-mr' ) . '</p>';
            $out .= '</div></div>';
            
        } else {

            // Вывести элементы каталога

            $last_opop_id = NULL;
            
            foreach ( (array) $opops as $opop ) {
                
                $out .= $this->get_catalog_item( $opop );
                $last_opop_id = $opop->ID;
                
            }

        }
        
        $out .= '<div class="col-12 text-center next-page p-6">';
        
        // Вывести скрытые поля с данными
        
        $out .= '<input type="hidden" name="action" value="catalog" />';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';     
        
        $next = $page + 1;
        $out .= '<input type="hidden" name="page" value="' . $next . '" />';
        
        
        foreach ( array('department', 'degree', 'period', 'form' ) as $item ) {
            
            $value = ( isset( $_REQUEST['categories'][$item] ) ) ? sanitize_key( $_REQUEST['categories'][$item] ) : "all";
            $out .= '<input type="hidden" name="categories[' . $item . ']" value="' . $value . '" />';
            
        }
        
        // // Выбрать последнюю запись с указанными критериями
        
        // $args['order'] = 'ASC';
        // $args['posts_per_page'] = 1;
        // unset( $args['paged'] );
        // p($args);
        // $opops = get_posts( $args );
        
        // Выбрать последнюю запись с указанными критериями
        
        $args['order'] = 'DESC';
        $args['posts_per_page'] = 1;
        unset( $args['paged'] );
        $opops = get_posts( $args );
        
        // Кнопка "Показать еще" - если есть, что показывать

        if ( isset( $opops[0] ) && $opops[0]->ID != $last_opop_id ) $out .= '<button class="btn btn-lg btn-primary">' . __( 'Показать ещё', 'mif-mr' ) . '</button>';
        
        $out .= '<div class="loading" style="display: none"><i class="fas fa-spinner fa-3x fa-spin"></i></div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_screen_get_catalog', $out, $page );
    }
    
    
    
    //
    // Элемент каталога ОПОП
    //
    
    public function get_catalog_item( $opop )
    {
        if ( empty( $opop ) ) return '';
        
        $link = get_permalink( $opop->ID );
        $title = $opop->post_title;
        $excerpt = $opop->post_excerpt;
        

        $department = $this->get_category_list( $opop->ID, 'department' );
        $degree = $this->get_category_list( $opop->ID, 'degree', 'name' );
        $period = $this->get_category_list( $opop->ID, 'period' );
        $form = $this->get_category_list( $opop->ID, 'form' );
        
        $list = array_merge( $degree, $period, $form );
        
        $logo = $this->get_category_list( $opop->ID, 'logo', 'slug' );
        if ( empty( $logo ) ) $logo[] = 'default'; 
        
        $arr_logo_img = array();
        
        $color = $this->get_cover_color( $opop->ID );
        $style = ( ! empty( $color ) ) ? ' style="background-color: ' . $color . ';"' : '';

        foreach ( $logo as $item )                 
            $arr_logo_img[] = '<img src="' . plugins_url( 'images/logo/', __DIR__ ) . $item . '.png">';
  
        $out = '';

        $out .= '<div class="p-3 col-12 col-md-6 col-xl-4"><div class="card bg-light h-100">';
        $out .= '<a href="' . $link . '"><div class="cover text-center"' . $style . '>' . implode( "", $arr_logo_img ) . '</div></a>';
        $out .= '<div class="card-block p-3">';
        $out .= '<h4 class="mb-4 card-title"><a href="' . $link . '">' . $title . '</a></h4>';
        $out .= '<p class="mb-4"><strong><small>' . implode( ', ', $department ) . '</small></strong></p>';
        $out .= '<p class="mb-4"><small>' . implode( ', ', $list ) . '</small></p>';
        $out .= '</div>';
        $out .= '</div></div>';
        
        return apply_filters( 'mif_qm_screen_get_catalog_item', $out, $opop );
    }
    
    
    
    //
    // Категории ОПОП
    //
    
    public function get_category()
    {
        $out = '';

        $out .= $this->get_category_item( 'department' );
        $out .= $this->get_category_item( 'degree' );
        $out .= $this->get_category_item( 'period' );
        $out .= $this->get_category_item( 'form' );
        
        return apply_filters( 'mif_mr_screen_get_category', $out );
    }
    
    
    
    //
    // Элемент каталога ОПОП
    //
    
    public function get_category_item( $key )
    {
        $out = '';
        
        $arr = $this->get_category_arr();
        
        $out .= '<div class="row mt-4 ' . $key . '">';
        $out .= '<div class="col-2 col-xl-1 d-none d-sm-block"><img src="' . plugins_url( 'images/icons/', __DIR__ ) . $key . '.png" class="icons "></div>';
        $out .= '<div class="col">';
        $out .= '<button type="button" class="btn btn-primary mr-1 mt-1" data-key="' . $key . '" data-value="all">';
        $out .=  __( 'Всё', 'mif-mr' );
        $out .= '</button>';
        
        foreach ( (array) $arr[$key] as $item ) {

            $out .= '<button type="button" class="btn btn-secondary mr-1 mt-1" data-key="' . $key . '" data-value="' . $item['slug'] . '">';
            $out .= $item['name'];
            $out .= '</button>';
            
        }
        
        $out .= '</div>';
        $out .= '</div>';

        return apply_filters( 'mif_mr_screen_get_category_item', $out );
    }
    
  

}

?>