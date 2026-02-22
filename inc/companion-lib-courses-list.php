<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_courses_list extends mif_mr_lib_courses {
    
    
    function __construct()
    {
        parent::__construct();

        $this->courses_per_page = apply_filters( 'lib_courses_per_page', 10 );
    }
    

    
    //
    // Показать библиотеку дисциплин
    //

    public function get_lib_courses( $opop_id = NULL )
    {
        // global $tree;
        global $wp_query;
        
        if ( ! empty( $wp_query->query_vars['id'] ) ) return;
        
        // if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // ####!!!!!
        
        $this->create( $opop_id, 'lib-courses' );
        
        // $arr = array();
        // if ( isset( $tree['content']['lib-courses']['data'] ) ) $arr = $tree['content']['lib-courses']['data'];
    
        // $index = array();
        // foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
        // foreach ( $index as $key => $item ) sort( $index[$key] ); 
        // ksort( $index );
        
        $f = true;
        
        $out = '';
        
        $out .= '<div class="content-ajax">';
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';

        $out .= $this->get_lib_head( array( 'title' => 'Библиотека дисциплин' ) );

        $out .= $this->get_panel_search();
        
        // foreach ( $index as $i ) {
        //     foreach ( $i as $ii ) {
        //         $item = $arr[$ii];
        //         $out .= $this->get_lib_body( array( 
        //                                             'comp_id' => $item['comp_id'],    
        //                                             'name' => $item['name'],    
        //                                             'from_id' => $item['from_id'],    
        //                                             'type' => 'lib-courses',    
        //                                         ) );
        //     }
        // }
        
        // $out .= $this->get_list_courses( array( 'opop_id' => $opop_id ) );
        
        
        // $out .= '<div class="loading text-center text-secondary p-6 m-6" style="display: none"><i class="fas fa-spinner fa-3x fa-spin"></i></div>';
        $out .= '<div class="list-box">';
        $out .= $this->get_list_courses();
        $out .= '</div>';

        if ( $f ) $out .= $this->get_lib_create( array(
                                                    'action' => 'lib-courses',
                                                    'button' => 'Создать дисциплину',
                                                    'title' => 'Название дисциплины',
                                                    'hint_a' => 'Например: Математика, Безопасность жизнедеятельности, Педагогическая практика',
                                                    'date' => 'Данные',
                                                    'hint_b' => '<a href="' . '123' . '">Помощь</a>',
                                                    'a' => 'Импорт шаблонов',
                                                    'url' => mif_mr_opop_core::get_opop_url() . 'tools-courses/',
                                                ) );
    

        // $out .= $this->get_page_numbers();

        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_lib_courses', $out, $opop_id );
    }    

    

    
    //
    // Показать cписок 
    //

    public function get_list_courses( $att = array() )
    {
        global $tree;
        // if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // p($att);

        ####!!!!!
        
        $arr = array();
        if ( isset( $tree['content']['lib-courses']['data'] ) ) $arr = $tree['content']['lib-courses']['data'];

        // p($arr);
        
        // Критерии
        
        if ( isset( $att['criteria'] ) ) {

            switch ( $att['criteria'] ) {
            
                case 'local': 
                
                    foreach ( $arr as $k => $i ) if ( $i['from_id'] != mif_mr_opop_core::get_opop_id() ) unset( $arr[$k] ); 
                    break;
                
                case 'lib': 
                    
                    foreach ( $arr as $k => $i ) if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) unset( $arr[$k] ); 
                    break;
                    
                case 'curriculum': 
                    
                    foreach ( $arr as $k => $i ) if ( empty( $tree['content']['courses']['index'][$i['name']] ) ) unset( $arr[$k] );
                    break;

                case 'not-curriculum': 
                    
                    foreach ( $arr as $k => $i ) if ( ! empty( $tree['content']['courses']['index'][$i['name']] ) ) unset( $arr[$k] );
                    break;

                // default:
                // break;
            
            }
            
        }


        // Поиск

        if ( ! empty( $att['s'] ) ) {

            foreach ( $arr as $k => $i ) {
                    
                $a = mb_strtoupper( $i['name'] );
                $b = mb_strtoupper( $att['s'] );
                
                if ( ! mb_strstr( $a, $b ) ) unset( $arr[$k] );
                 
            }

        }

        $count = count($arr);

        $numbers = ceil( $count / $this->courses_per_page );
        $current = ( isset( $att['num'] ) ) ? $att['num'] : 0;
        
        $index = array();
        foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
        foreach ( $index as $key => $item ) sort( $index[$key] ); 
        ksort( $index );

        $arr2 = array();
        foreach ( $index as $i ) foreach ( $i as $ii ) $arr2[] = $arr[$ii];

        $arr3 = array_chunk( $arr2, $this->courses_per_page );
        $arr4 = ( $current !== -1 ) ? $arr3[$current] : $arr2;

        $out = '';

        if ( $count !== 0 ) $out .= '<div class="fw-semibold mb-3">Всего: <span class="p-1 pl-3 pr-3 mr-gray rounded">' . $count . '</span></div>';
        
        // foreach ( $arr3[$current] as $item ) {
        foreach ( $arr4 as $item ) {

            $is_curriculum = ( ! empty( $tree['content']['courses']['index'][$item['name']] ) ) ? true : false;

            $out .= $this->get_lib_body( array( 
                                                'comp_id' => $item['comp_id'],    
                                                'name' => $item['name'],    
                                                'from_id' => $item['from_id'],    
                                                'type' => 'lib-courses',  
                                                'is_curriculum' => $is_curriculum,
                                            ) );

        }

        if ( $count !== 0 ) $out .= $this->get_page_numbers( $numbers, $current );
        
        if ( empty( $out ) ) {

            $out .= '<div class="bg-light p-5 mb-5 text-center">';
            $out .= '<p class="text-secondary mt-4 mb-0"><i class="fas fa-3x fa-ellipsis-h"></i></p>';
            $out .= '<p class="mb-5 fw-semibold">' . __( 'Ничего не найдено', 'mif-mr' ) . '</p>';
            $out .= '</div>';

        }

        return apply_filters( 'mif_mr_get_list_courses', $out, $att );
    }    








    

    // 
    // Получить панель поиска
    //

    function get_panel_search()
    {
        $out = '';

        $arr = apply_filters( 'mif_mr_lib_courses_panel_search', array(  
                                                                    array( 'Все', 'all' ),
                                                                    array( 'Локальные', 'local' ),
                                                                    array( 'Из библиотеки', 'lib' ),
                                                                    array( 'Входит в план', 'curriculum' ),
                                                                    array( 'Не входит в план', 'not-curriculum' ),
                                                                ) );

        $out .= '<div class="row mb-3 mt-5">';
        $out .= '<div class="col col-md-2"></div>';
        $out .= '<div class="col col-12 col-md-8">';
        $out .= '<div class="input-group">';
        $out .= '<div class="input-group-text"><i class="fa-solid fa-magnifying-glass fa-sm"></i></div>';
        $out .= '<input type="text" class="form-control" name="courses_search" placeholder="Искать дисциплину">';
        $out .= '</div>';
        $out .= '</div>';
        $out .= '<div class="col col-md-2"></div>';
        $out .= '</div>';

        $out .= '<div class="row mb-6">';
        $out .= '<div class="col text-center">';
        
        $checked = '-checked';

        foreach ( $arr as $a ) {
            $out .= '<a class="criteria' . $checked . ' m-2 pb-1" href="#" data-criteria="' . $a[1]. '">' . $a[0]. '</a>';
            $checked = '';
        }
        
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_get_panel_search', $out );
    }
  
    


    // 
    // Получить номера страниц
    //

    function get_page_numbers( $numbers, $current = 0 )
    {
        if ( $numbers <= 1 ) return;
    
        $out = '';

        // $arr = apply_filters( 'mif_mr_lib_courses_panel_search', array(  
        //                                                             array( 'Все', 'all' ),
        //                                                             array( 'Локальные', 'local' ),
        //                                                             array( 'Из библиотеки', 'lib' ),
        //                                                             array( 'Входит в план', 'curriculum' ),
        //                                                             array( 'Не входит в план', 'not-curriculum' ),
        //                                                         ) );

        $out .= '<div class="row mb-3 mt-4">';
        $out .= '<div class="col text-center">';

        for ( $i = 0;  $i < $numbers;  $i++ ) { 

            // $out .= '<span class="m-1 p-1 pr-3 pl-3 rounded bg-secondary text-light">';
            // $out .= $i + 1;
            // $out .= '</span>';
            $class = ( $current == $i ) ? 'bg-primary text-light' : 'mr-gray';
            $out .= '<a href="#" class="numbers m-1 p-1 pr-3 pl-3 rounded fw-semibold ' . $class . '" data-num="' . $i . '">';
            $out .= $i + 1;
            $out .= '</a>';

        }

        $class = ( $current == -1 ) ? 'bg-primary text-light' : 'mr-gray';
        $out .= '<a href="#" class="numbers m-1 p-1 pr-3 pl-3 rounded fw-semibold ' . $class . '" data-num="-1">';
        $out .= 'Показать все';
        $out .= '</a>';



        // $out .= '<div class="col col-12 col-md-8">';
        // $out .= '<div class="input-group">';
        // $out .= '<div class="input-group-text"><i class="fa-solid fa-magnifying-glass fa-sm"></i></div>';
        // $out .= '<input type="text" class="form-control" name="courses_search" placeholder="Искать дисциплину">';
        // $out .= '</div>';
        // $out .= '</div>';
        // $out .= '<div class="col col-md-2"></div>';
        // $out .= '</div>';

        // $out .= '<div class="row mb-5">';
        // $out .= '<div class="col text-center">';
        
        // $checked = '-checked';

        // foreach ( $arr as $a ) {
        //     $out .= '<a class="criteria' . $checked . ' m-2 pb-1" href="#" data-criteria="' . $a[1]. '">' . $a[0]. '</a>';
        //     $checked = '';
        // }
        
        // $out .= '@@@';
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_get_page_numbers', $out );
    }


    private $courses_per_page;

}


?>