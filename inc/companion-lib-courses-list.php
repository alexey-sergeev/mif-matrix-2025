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
                                                ) );
    
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



        $index = array();
        foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
        foreach ( $index as $key => $item ) sort( $index[$key] ); 
        ksort( $index );
        
        $out = '';

        foreach ( $index as $i ) {
            foreach ( $i as $ii ) {
                $item = $arr[$ii];
                $out .= $this->get_lib_body( array( 
                                                    'comp_id' => $item['comp_id'],    
                                                    'name' => $item['name'],    
                                                    'from_id' => $item['from_id'],    
                                                    'type' => 'lib-courses',    
                                                ) );
            }
        }
        
        return apply_filters( 'mif_mr_get_list_courses', $out, $att );
    }    








    

    // 
    // Получить панель поиска
    //

    function get_panel_search()
    {
        $out = '';

        // $out .= '<div class="row mb-3 mt-4">';
        // $out .= '<div class="col text-center">';
        // $out .= '<button type="button" class="btn btn-primary btn-sm mr-3 mb-3">Все</button>';
        // $out .= '<button type="button" class="btn btn-outline-primary btn-sm mr-3 mb-3">Полный список</button>';
        // $out .= '<button type="button" class="btn btn-outline-primary btn-sm mr-3 mb-3">Локальные</button>';
        // $out .= '<button type="button" class="btn btn-outline-primary btn-sm mr-3 mb-3">Из библиотеки</button>';
        // $out .= '<button type="button" class="btn btn-outline-primary btn-sm mr-3 mb-3">Входит в план</button>';
        // $out .= '<button type="button" class="btn btn-outline-primary btn-sm mb-3">Не входит в план</button>';
    
        // // $out .= '<span><a href="#">Полный список</a></span>';
        // // $out .= '<span><a href="#">Локальные</a></span>';
        // // $out .= '<span><a href="#">Из библиотеки</a></span>';
        // // $out .= '<span><a href="#">В учебный план</a></span>';
        // $out .= '</div>';
        // $out .= '</div>';
        
        $arr = apply_filters( 'mif_mr_lib_courses_panel_search', array(  
                                                                    array( 'Все', 'all' ),
                                                                    array( 'Локальные', 'local' ),
                                                                    array( 'Из библиотеки', 'lib' ),
                                                                    array( 'Входит в план', 'curriculum' ),
                                                                    array( 'Не входит в план', 'not-curriculum' ),
                                                                ) );

        $out .= '<div class="row mb-3 mt-4">';
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
        
        // $checked = ' checked';
        // foreach ( $arr as $a ) {

        //     $out .= '<label class="m-2"><input class="form-check-input mr-1" type="radio" name="r" value="' . $a[1]. '"' . $checked . '>' . $a[0]. '</label>';
        //     $checked = '';
        
        // }
        
        $checked = '-checked';
        foreach ( $arr as $a ) {
            $out .= '<a class="panel-search' . $checked . ' m-2 pb-1" href="#" data-criteria="' . $a[1]. '">' . $a[0]. '</a>';
            $checked = '';
        }
        

        $out .= '</div>';
        $out .= '</div>';
        

        return apply_filters( 'mif_mr_get_panel_search', $out );
    }
  
    




}


?>