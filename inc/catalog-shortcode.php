<?php

//
// Экранные методы каталога
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_catalog_shortcode extends mif_mr_catalog_core {
    
    function __construct()
    {
        parent::__construct();
        
        add_shortcode( 'opops_list', array( $this, 'show_opops_list' ) );
        
    }
    
    


    
    //
    // Список ОПОП
    //
    
    public function show_opops_list( $atts )
    {
        global $mr;
        
        if ( $mr->level_access() == 0 ) {
            
            return '<div class="alert alert-warning" role="alert">
                    Доступ ограничен. Возможно, надо просто <a href="' . get_site_url() . '/wp-login.php?redirect_to=' . get_permalink() . '">войти на сайт</a>.
                    </div>';
        }

        $must_level_access = 4;

        // if ( mif_user_can( 0 ) ) return;
          
        $out = '';
       
       
        if ( $mr->user_can( $must_level_access ) ) {

            $out .= '<div class="row">';
            $out .= '<div class="col-12 col-sm-6 mb-5"><a href="?download=opops-list-xlsx"><span class="mr-btn mr-gray"><i class="fa fa-download" aria-hidden="true"></i></span>Скачать</a></div>';
            $out .= '<div class="col-12 col-sm-6 mb-5 text-sm-end"><a href="' . admin_url() . 'post-new.php?post_type=opop" class="btn btn-secondary mt-1">Добавить программу</a></div>';
            $out .= '</div>';

        };

        // $out .= '<div></div>';
        // p(admin_url());



        $out .= '<table class="sortable">';
        
        
        $arr = $this->get_opops_list_head();
        
        $out .= '<thead class="mr-gray">';
        
        foreach ( (array) $arr as $a ) {
        
            // $out .= '<th';
            // if ( in_array( $a, array( '№', '' ) ) ) $out .= ' class="no-sort"';
            // $out .= '>';

            if ( ! $mr->user_can( $must_level_access ) && empty( $a ) ) continue;

            $out .= ( ! in_array( $a, array( '№', 'Логотип', '' ) ) ) ? '<th>' : '<th class="no-sort">';
            $out .= $a;
            $out .= '</th>';
        
        }

        $out .= '</thead>';
        
        
        $arr = $this->get_opops_list_data();
        $n = 1;
        
        foreach ( (array) $arr as $a ) {
            
            $out .= '<tr>';
            
            $out .= '<td>' . $n++ . '</td>';
            
            $out .= '<td>';
            $out .= '<a href="' . $a['URL'] . '" target="_blank" >';
            $out .= $a['title'];
            $out .= '</a>';
            $out .= '</td>';
            
            $flag = false;

            foreach ( array( 'department', 'degree', 'period', 'form' ) as $item ) {

                $out .= '<td class="mr-' . $a['categories'][$item]['class'] . '">';
                $out .= implode( '<br />', $a['categories'][$item]['category'] );
                $out .= '</td>';
                
            }
            
            $style = ( ! empty( $a['color'] ) ) ? ' style="background-color: ' . $a['color'] . ';"' : '';

            
            $out .= ( ! empty( $a['categories']['logo']['category'] ) ) ? '<td class="logo cover text-center align-middle"' . $style . '>' : '<td>';
            // $out .= ( empty( $out ) ) ? '<td class="logo cover text-center"' . $style . '>' : '<td>';
            

            // p($a['categories']['logo']['category']);

            foreach ( (array) $a['categories']['logo']['category'] as $logo ) {
                
                // p($logo);
                // $out .= '<img src="';
                // $out .= $logo;
                // $out .= '">';
                $out .= '<img src="' . plugins_url( 'images/logo/', __DIR__ ) . $logo . '.png">';

            }

            $out .= '</td>';



            if ( $mr->user_can( $must_level_access ) ) {

                $out .= '<td class="mr-' . $a['URL_edit_color'] . '">';
                $out .= '<a href="' . $a['URL_edit'] . '" target="_blank">';
                // $out .= '<i class="fa fa-pencil-square fa-1x"></i>';
                $out .= '<i class="fa fa-pencil-square-o fa-1x"></i>';
                // $out .= '<i class="fa fa-pencil fa-1x"></i>';
                $out .= '</a>';
                $out .= '</td>';

            }
            
            $out .= '</tr>';
            
        }
        
        $out .= '</table>';

        return apply_filters( 'mif_mr_shortcode_opops_list', $out, $arr );
    }
    
    
    
    
    
    //
    // Данные ОПОП для xlsx
    //
    
    public function get_opops_list_xlsx()
    {
        global $mr;
        
        // if ( ! $mr->user_can( 0 ) ) return;
        if ( ! is_user_logged_in() ) exit;
        // if ( ! is_user_logged_in() ) return array();

        $arr = array();

        // $arr[] = ' ';
        // $arr[] = ' ';
        // $arr[] = ' ';
        // $arr[] = ' ';
        // $arr[] = ' ';
        // $arr[] = ' ';
        $arr[] = array();
        $arr[] = array( __( 'Список образовательных программ', 'mif-mr' ) );
        $arr[] = array();
        $arr[] = array( __( 'URL:', 'mif-mr' ), get_permalink() );
        $arr[] = array( __( 'Дата:', 'mif-mr' ), $mr->get_time() );
        $arr[] = array();
        $arr[] = array();


        $arr[] = $this->get_opops_list_head();
        // $head = $this->get_opops_list_head();

        // $arr[] = array(

        //     $head[1],
        //     $head[2],
        //     $head[3],
        //     $head[4],
        //     $head[5],
            
        // );

        $data = $this->get_opops_list_data();
        $n = 1;

        foreach ( (array) $data as $d ) {
            // p($d);
            $arr[] = array(

                (string) $n++,
                // '$n++',
                $d['title'],
                implode( ', ', $d['categories']['department']['category'] ),
                implode( ', ', $d['categories']['degree']['category'] ),
                implode( ', ', $d['categories']['period']['category'] ),
                implode( ', ', $d['categories']['form']['category'] ),
                implode( ', ', $d['categories']['logo']['category'] ),
                // implode( ', ', $d['logo'] ),

            );

        }
// p($arr);
        return apply_filters( 'mif_mr_shortcode_opops_list_xlsx', $arr );
    }
    
    

    
    //
    // Шапка ОПОП
    //
    
    public function get_opops_list_head()
    {
        $arr = array( 
            '№',
            'Образовательная программа',
            'Институт',
            'Уровень образования',
            'Срок обучения',
            'Форма обучения',
            'Логотип',
            '',
        );

        return apply_filters( 'mif_mr_shortcode_opops_list_head', $arr );
    }
    
    

    
    //
    // Данные ОПОП
    //
    
    public function get_opops_list_data()
    {
        $arr = array();
        
        $args = $this->get_catalog_args();
        $args['posts_per_page'] = -1;
        $opops = get_posts( $args );

        foreach ( (array) $opops as $opop ) {

            $arr[] = array(
                'title' => $opop->post_title,
                'URL' => get_permalink( $opop->ID ),
                'categories' => array(
                    'department' => array( 'category' => $this->get_category_opop( $opop->ID, 'department', 'name' ) ),
                    'degree' => array( 'category' => $this->get_category_opop( $opop->ID, 'degree', 'name' ) ),
                    'period' => array( 'category' => $this->get_category_opop( $opop->ID, 'period', 'name' ) ),
                    'form' => array( 'category' => $this->get_category_opop( $opop->ID, 'form', 'name' ) ),
                    'logo' => array( 'category' => $this->get_category_opop( $opop->ID, 'logo', 'slug' ) ),
                ),
                'color' => $this->get_cover_color( $opop->ID ),
                'URL_edit' => get_edit_post_link( $opop->ID ),
                // '' => ,
            );

        }

        // p($arr);
        
        foreach ( (array) $arr as $key => $a ) {

            $l = 'white';

            foreach ( array( 'department', 'degree', 'period', 'form' ) as $item ) {

                $n = count( $arr[$key]['categories'][$item]['category'] );
                $arr[$key]['categories'][$item]['count'] = $n;
                
                $c = ( $n == 1) ? 'white' : ( ( $n == 0) ? 'red' : 'yellow' );
                $arr[$key]['categories'][$item]['class'] = $c;
                
                if ( $c == 'red' ) $l = 'red';
                if ( $c == 'yellow' && $l != 'red' ) $l = 'yellow';
                
            }
            
            $arr[$key]['URL_edit_color'] = $l;
            
            // p( $arr[$key]['categories']['category'] );

        }

        // p($arr);

        return apply_filters( 'mif_mr_shortcode_opops_list_data', $arr );
    }




    //
    // Получить массив категорий
    //
    
    public function get_category_opop( $id, $which, $key = 'description' )
    {
        
        $term_id = get_term_by( 'slug', $which, 'opop_category' )->term_id;
        
        if ( empty( $term_id ) ) return;
        
        $categories = get_the_terms( $id, 'opop_category' );
        
        $arr = array();
        
        foreach ( (array) $categories as $item ) 
        if ( ! empty( $item ) && $item->parent === $term_id ) $arr[] = $item->$key;

        return apply_filters( 'mif_mr_screen_get_the_category_opop', $arr );
    }
        

    
}

?>