<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_courses_screen extends mif_mr_lib_courses_list {
    
    
    function __construct()
    {
        parent::__construct();
        
        $this->save_all();

    }
    
    
    // 
    // Показывает 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-course.php' ) ) {
           
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-course.php', false );

        }
        
    }
    
    
    
    //
    // Показать 
    //
    
    public function show_courses( $opop_id = NULL )
    {
        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            $out .= $this->get_course( (int) $wp_query->query_vars['id'] );

        } else {

            $out .= $this->get_lib_courses();
        
        }

        return apply_filters( 'mif_mr_show_courses', $out );
    }    
 
    

    
    //
    // Показать дисциплину
    //
    
    public function get_course( $course_id = NULL, $opop_id = NULL )
    { 
        global $wp_query;

        if ( isset( $_REQUEST['sub'] ) ) $this->save_part( sanitize_key( $_REQUEST['sub'] ), $course_id, $opop_id, true );
        
        global $tree;
        
        // p( $tree['content']['lib-courses']['data'][$wp_query->query_vars['id']] ); 
        
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        if ( empty( $course_id ) ) $course_id = (int) $wp_query->query_vars['id'];
        
        // p($arr);
        
        $out = '';
        $f = true;
        
        $out .= '<div class="content-ajax">';
        
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) {
            
            $arr = $tree['content']['lib-courses']['data'][$course_id];
            // p($arr);
            
            $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $arr['name'] . '</h4>';
            
            if ( isset( $_REQUEST['edit'] ) ) {

                $out .= $this->get_edit( $course_id );

            } else {

                $out .= $this->get_show_all();
                
                $out .= '<div class="comp container no-gutters">';
                
                $save = ( isset( $_REQUEST['do'] ) ) ? sanitize_key( $_REQUEST['part'] ) : NULL;

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Содержание',
                                                    'part' => 'content',
                                                    'sub_id' => 'content',
                                                    'data' => $arr['data']['content'],
                                                    'coll' => $this->coll_on_off( 'content', true ),
                                                ));
                
                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Оценочные средства',
                                                    'part' => 'evaluations',
                                                    'sub_id' => 'evaluations',
                                                    'data' => $arr['data']['evaluations'],
                                                    // 'coll' => $this->coll_on_off( 'evaluations', false ),
                                                    'coll' => $this->coll_on_off( 'evaluations', empty( $arr['data']['evaluations'] ) ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Индикаторы',
                                                    'part' => 'indicator',
                                                    'sub_id' => 'content',
                                                    'data' => $arr['data']['content'],
                                                    // 'coll' => $this->coll_on_off( 'indicator', false ),
                                                    'coll' => $this->coll_on_off( 'indicator', empty( $arr['data']['content'] ) ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Литература',
                                                    'part' => 'biblio',
                                                    'sub_id' => 'biblio',
                                                    'data' => $arr['data']['biblio'],
                                                    // 'coll' => $this->coll_on_off( 'biblio', false ),
                                                    'coll' => $this->coll_on_off( 'biblio', empty( $arr['data']['biblio'] ) ),
                                                ));
                
                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Информационные технологии',
                                                    'part' => 'it',
                                                    'sub_id' => 'it',
                                                    'data' => $arr['data']['it'],
                                                    // 'coll' => $this->coll_on_off( 'it', false ),
                                                    'coll' => $this->coll_on_off( 'it', empty( $arr['data']['it'] ) ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Материально-техническое обеспечение',
                                                    'part' => 'mto',
                                                    'sub_id' => 'mto',
                                                    'data' => $arr['data']['mto'],
                                                    // 'coll' => $this->coll_on_off( 'mto', false ),
                                                    'coll' => $this->coll_on_off( 'mto', empty( $arr['data']['mto'] ) ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Разработчики',
                                                    'part' => 'authors',
                                                    'sub_id' => 'authors',
                                                    'data' => $arr['data']['authors'],
                                                    // 'coll' => $this->coll_on_off( 'authors', false ),
                                                    'coll' => $this->coll_on_off( 'authors', empty( $arr['data']['authors'] ) ),
                                                ));

                $out .= '</div>';
                
            }
            
        }
        

    //     // Hidden
        
        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $course_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="action" value="lib-courses">';
        if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';

        return apply_filters( 'mif_mr_lib_courses_get_course', $out, $course_id, $opop_id );
    }    
    
    
    
    private function coll_on_off( $part, $f )
    {
        if ( isset( $_REQUEST['coll'][$part] ) ) $f = ( $_REQUEST['coll'][$part] == 'on' ) ? true : false;
        return $f;
    }
    
    
    
    //
    // часть
    //
    
    public function get_course_part( $d )
    {
        global $tree;
        
        if ( empty( $d['data'] ) ) $d['data'] = $tree['content']['lib-courses']['data'][$d['course_id']]['data'][$d['sub_id']];
        
        $out = '';   
        
        $f = true;
        
        $out .= '<span class="content-ajax">';
  
        $coll = false;
        if ( isset( $d['coll'] ) ) $coll = $d['coll'];

        $out .= $this->get_sub_head( array(
                                            'name' => $d['name'],    
                                            'note' => ( empty( $d['data'] ) ) ? ' (отсутствуют)' : '',    
                                            'part' => $d['part'],
                                            'sub_id' => $d['sub_id'],
                                            'f' => $f, 
                                            'coll' => $coll,    
                                            ) );

        if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'edit' ) {
            
            // Режим edit

            if ( $f ) $out .= $this->get_sub_edit( $d['sub_id'], $d['course_id'], $d['part'] );
            
        } else {
                
            // Режим отображения

            switch ( $d['part'] ) {
                
                case 'content':
                case 'indicator':

                    $out .= $this->get_item_body_content( $d );
                    
                break;
                    
                case 'evaluations':

                    $out .= $this->get_item_body_evaluations( $d );
                    
                break;
                    
                default:
                
                    $out .= $this->get_item_body( $d );
                
                break;
            
            }

        }
        
        $out .= '&nbsp;';

        $out .= '</span>';
        
        return apply_filters( ' mif_mr_lib_courses_get_course_sub', $out, $d );
    }





    //
    // body
    //

    public function get_item_body_evaluations( $d )
    {
        if ( empty( $d['data']) ) return $this->data_none( $d );

        $t = apply_filters( 'mif-mr-body-evaluations-text', array( 
                                'sem' => 'Семестр',
                            ) );          
        
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        $style2 = ( isset( $d['coll'] ) && $d['coll'] == true ) ? ' style="display: none;"' : '';
        
        $out = '';
        
        $out .= '<span class="coll-ppp"' . $style2 . '">. . .</span>';

        foreach ( $d['data'] as $item ) {
            
        // Семестр
            
            if ( isset( $d['data'][1] ) ) {

                $out .= '<div class="row coll"' . $style . '">';
                $out .= '<div class="col">';
                
                // $out .= '<p class="mr-gray p-1 pl-3 mt-5"><strong>' . $t['sem'] . ' ' . $item['sem'] + 1 . '</strong></p>';
                $out .= '<p class="bg-light p-1 pl-3 mt-5"><strong>' . $t['sem'] . ' ' . $item['sem'] + 1 . '</strong></p>';
                
                $out .= '</div>';
                $out .= '</div>';
                
            }
                
            // Оценочные средства
            
            foreach ( $item['data'] as $item2 ) {
                
                $out .= '<div class="row coll"' . $style . '">';
                
                $out .= '<div class="col">';
                // $out .= '<p class="pl-3">' . $item2['name'] . '</p>';
                $out .= '<p class="pl-3">' . trim( $item2['name'], '. ' ) . '</p>';
                $out .= '</div>';
                
                $out .= '<div class="col-1">';
                $out .= $item2['att']['rating'];
                $out .= '</div>';
                
                $out .= '<div class="col-4 col-lg-3">';
                $out .= $item2['att']['cmp'];
                $out .= '</div>';
                
                $out .= '</div>';

            }
            
        }

        return apply_filters( 'mif_mr_companion_lib_courses_get_item_body_evaluations', $out, $d );
    }



    //
    // body
    //

    public function get_item_body_content( $d )
    {
        if ( empty( $d['data']) ) return $this->data_none( $d );

        $t = apply_filters( 'mif-mr-body-content-text', array( 
                                'target' => 'Цель освоения дисциплины',
                                'part' => 'Раздел',
                                'hours' => 'Трудоемкость',
                                'z' => 'знать', 
                                'u' => 'уметь', 
                                'v' => 'владеть', 
                                'cmp' => ' Компетенции', 
                            ) );          
        
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        $style2 = ( isset( $d['coll'] ) && $d['coll'] == true ) ? ' style="display: none;"' : '';
        
        $is_indicator = ( $d['part'] == 'indicator' ) ? true : false;
        
        $out = '';

        $out .= '<span class="coll-ppp"' . $style2 . '">. . .</span>';

        // Цель освоения дисциплины
        
        if ( isset( $d['data']['target'] ) ) {

            if ( ! $is_indicator ) {
                
                $out .= '<div class="row coll"' . $style . '>';
                $out .= '<div class="col">';
                $out .= '<p class="fw-bolder">' . $t['target'] . '</p>';
                $out .= '<p>' . $d['data']['target'] . '</p>';
                $out .= '</div>';
                $out .= '</div>';
                
            } 
        
        }
        
        // Разделы

        if ( isset( $d['data']['parts'] ) ) {

            foreach ( $d['data']['parts'] as $item ) {
                
                // Описание
                
                $out .= '<div class="row coll"' . $style . '>';
                $out .= '<div class="col">';
                
                // $out .= '<p class="mr-gray p-1 pl-3 mt-5"><strong>' . $t['part'] . ' ' . $item['sub_id'] + 1 . '.</strong> ' . $item['name'] . '</p>';
                $out .= '<p class="bg-light p-1 pl-3 mt-5"><strong>' . $t['part'] . ' ' . $item['sub_id'] + 1 . '.</strong> ' . $item['name'] . '</p>';
                
                if ( ! $is_indicator ) {
                    
                    $out .= '<p class="pl-3">' . $item['content'] . '</p>';
                    
                    // Трудоемкость
                    
                    $out .= '<div class="pl-3 mt-5">';
                    $out .= '<span class="p-1 pl-4 pr-4 rounded mr-green">' . $t['hours'] . ': (';
                    $out .= '<span title="Лек." class="hint">' . $item['hours']['lec'] . '</span>, ';
                    $out .= '<span title="Лаб." class="hint">' . $item['hours']['lab'] . '</span>, ';
                    $out .= '<span title="Прак." class="hint">' . $item['hours']['prac'] . '</span>, ';
                    $out .= '<span title="СРС." class="hint">' . $item['hours']['srs'] . '</span>)';
                    $out .= '</span>';
                    $out .= '</div>';
                    
                }
                
                $out .= '</div>';
                $out .= '</div>';
                

                // Результаты
                
                if ( $is_indicator ) {
                    
                    $out .= '<div class="row coll"' . $style . '>';
                    $out .= '<div class="col">';
                    
                    if ( ! empty( $item['outcomes']['z'] ) ) {
                        
                        $out .= '<p class="mb-1 mt-0 pl-3"><em>' . $t['z'] . ':</em></p>';
                        foreach ( (array) $item['outcomes']['z'] as $item2 ) $out .= '<p class="mb-1 pl-3">— ' . $item2 . '</p>';
                    
                    }

                    if ( ! empty( $item['outcomes']['u'] ) ) {

                        $out .= '<p class="mb-1 mt-3 pl-3"><em>' . $t['u'] . ':</em></p>';
                        foreach ( (array) $item['outcomes']['u'] as $item2 ) $out .= '<p class="mb-1 pl-3">— ' . $item2 . '</p>';
                    
                    } 
                    
                    if ( ! empty( $item['outcomes']['v'] ) ) {
                        
                        $out .= '<p class="mb-1 mt-3 pl-3"><em>' . $t['v'] . ':</em></p>';
                        foreach ( (array) $item['outcomes']['v'] as $item2 ) $out .= '<p class="mb-1 pl-3">— ' . $item2 . '</p>';
                   
                    }
                
                    
                    // Компетенции 
                    
                    $out .= '<div class="pl-3 mt-5">';
                    $out .= '<span class="p-1 pl-4 pr-4 rounded mr-green">' . $t['cmp'] . ': ';
                    $out .= $item['cmp'];
                    $out .= '</span>';
                    $out .= '</div>';
                    
                    $out .= '</div>';
                    $out .= '</div>';

                }

            }
            // p( $item );

        }


        return apply_filters( 'mif_mr_companion_lib_courses_get_item_body_content', $out, $d );
    }



    //
    // body
    //

    public function get_item_body( $d )
    {
        if ( empty( $d['data']) ) return $this->data_none( $d );

        $t = apply_filters( 'mif-mr-get-item-body', array(
            'biblio' => array( 'basic' => 'Основная литература', 'additional' => 'Дополнительная литература' ),
            'it' => array( 'inet' => 'Интернет-источники', 'app' => 'Программное обеспечение' ),
            'mto' => array( 'mto' => NULL ),
            'authors' => array( 'authors' => NULL ),
        ) );

        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        $style2 = ( isset( $d['coll'] ) && $d['coll'] == true ) ? ' style="display: none;"' : '';
        
        $ol = 'ol';   
        $li = 'li'; 
        
        if ( in_array( $d['part'], array( 'authors' ) ) ) {
            
            $ol = 'div';   
            $li = 'p'; 
            
        }
        
        $out = '';

        $out .= '<span class="coll-ppp"' . $style2 . '">. . .</span>';
        
        foreach ( $t[$d['part']] as $key => $item ) {
            
            $out .= '<div class="row coll"' . $style . '">';
            $out .= '<div class="col">';
            if ( ! empty( $item ) ) $out .= '<p class="fw-bolder mt-4">' . $item . '</p>';
            
            $out .= '<' . $ol . '>';
            foreach ( $d['data'][$key] as $item2 ) $out .= '<' . $li . '>' . $item2 . '</' . $li . '>';
            $out .= '</' . $ol . '>';
            
            $out .= '</div>';
            $out .= '</div>';
            
        }
        
        return apply_filters( 'mif_mr_companion_lib_courses_get_item_body', $out, $d );
    }
    
    
    
    
    //
    // Показать "нет данных" 
    //
    
    protected function data_none( $d )
    {
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';

        $out = '';
        $out .= '<div class="row coll"' . $style . '">';
        $out .= '<div class="col">';
        $out .= '<p class="mt-4"><em>нет данных</em></p>';
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_companion_lib_data_none', $out, $d );
        
    }
    
    
    
    
    // //
    // // Показать cписок 
    // //

    // public function get_lib_courses( $opop_id = NULL )
    // {
    //     global $tree;
    //     global $wp_query;
        
    //     if ( ! empty( $wp_query->query_vars['id'] ) ) return;
        
    //     if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
    //     ####!!!!!
        
    //     $this->create( $opop_id, 'lib-courses' );
        
    //     $arr = array();
    //     if ( isset( $tree['content']['lib-courses']['data'] ) ) $arr = $tree['content']['lib-courses']['data'];
    
    //     $index = array();
    //     foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
    //     foreach ( $index as $key => $item ) sort( $index[$key] ); 
    //     ksort( $index );
        
    //     $f = true;
        
    //     $out = '';
        
    //     $out .= '<div class="content-ajax">';
        
    //     $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';

    //     $out .= $this->get_lib_head( array( 'title' => 'Библиотека дисциплин' ) );
        
    //     foreach ( $index as $i ) {
            
    //         foreach ( $i as $ii ) {
                
    //             $item = $arr[$ii];
            
    //             $out .= $this->get_lib_body( array( 
    //                                                 'comp_id' => $item['comp_id'],    
    //                                                 'name' => $item['name'],    
    //                                                 'from_id' => $item['from_id'],    
    //                                                 'type' => 'lib-courses',    
    //                                             ) );
                
    //         }
        
    //     }
        
    //     if ( $f ) $out .= $this->get_lib_create( array(
    //                                                 'action' => 'lib-courses',
    //                                                 'button' => 'Создать дисциплину',
    //                                                 'title' => 'Название дисциплины',
    //                                                 'hint_a' => 'Например: Математика, Безопасность жизнедеятельности, Педагогическая практика',
    //                                                 'date' => 'Данные',
    //                                                 'hint_b' => '<a href="' . '123' . '">Помощь</a>',
    //                                             ) );
    
    //     $out .= '</div>';
    //     $out .= '</div>';
        
    //     return apply_filters( 'mif_mr_show_lib_courses', $out, $opop_id );
    // }    


   
}


?>