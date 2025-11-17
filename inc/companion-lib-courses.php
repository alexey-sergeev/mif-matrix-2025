<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_courses extends mif_mr_companion_core {
    
    
    function __construct()
    {
        parent::__construct();
        
        $this->index_part =  apply_filters( 'mif-mr-index_part', array( 
                                                                    'content',
                                                                    'evaluations',
                                                                    'biblio',
                                                                    'it',
                                                                    'mto',
                                                                    'guidelines',
                                                                    'authors',
                                                                ) );

    }
    
    
    // 
    // Показывает 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-lib-courses.php' ) ) {
           
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-lib-courses.php', false );

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
    
    public function get_course( $course_id, $opop_id = NULL )
    {
        
        if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'save' ) {

            if ( isset( $_REQUEST['sub'] ) ) $this->save( sanitize_key( $_REQUEST['sub'] ), $course_id, $opop_id, true );

        }
        
        
        global $tree;
        
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // p($arr);
        
        $out = '';
        $f = true;
        
        $out .= '<div class="content-ajax">';
        
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) {
            
            $arr = $tree['content']['lib-courses']['data'][$course_id];
            // p($arr);
            
            $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $arr['name'] . '</h4>';
            // $out .= '&nbsp;';
            
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
                                                    'coll' => $this->coll_on_off( 'evaluations', false ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Индикаторы',
                                                    'part' => 'indicator',
                                                    'sub_id' => 'content',
                                                    'data' => $arr['data']['content'],
                                                    'coll' => $this->coll_on_off( 'indicator', false ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Литература',
                                                    'part' => 'biblio',
                                                    'sub_id' => 'biblio',
                                                    'data' => $arr['data']['biblio'],
                                                    'coll' => $this->coll_on_off( 'biblio', false ),
                                                ));
                
                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Информационные технологии',
                                                    'part' => 'it',
                                                    'sub_id' => 'it',
                                                    'data' => $arr['data']['it'],
                                                    'coll' => $this->coll_on_off( 'it', false ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Материально-техническое обеспечение',
                                                    'part' => 'mto',
                                                    'sub_id' => 'mto',
                                                    'data' => $arr['data']['mto'],
                                                    'coll' => $this->coll_on_off( 'mto', false ),
                                                ));

                $out .= $this->get_course_part( array(
                                                    'course_id' => $course_id,
                                                    'name' => 'Разработчики',
                                                    'part' => 'authors',
                                                    'sub_id' => 'authors',
                                                    'data' => $arr['data']['authors'],
                                                    'coll' => $this->coll_on_off( 'authors', false ),
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
        // p($d);
        // p($_REQUEST);
        global $tree;
        
        if ( empty( $d['data'] ) ) $d['data'] = $tree['content']['lib-courses']['data'][$d['course_id']]['data'][$d['sub_id']];
        
        $out = '';   
        
        $f = true;
        
        $out .= '<span class="content-ajax">';
  
        $coll = false;
        if ( isset( $d['coll'] ) ) $coll = $d['coll'];

        $out .= $this->get_sub_head( array(
                                            'name' => $d['name'],    
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
        // p($d); 
        if ( empty( $d['data']) ) return '';

        $t = apply_filters( 'mif-mr-body-evaluations-text', array( 
                                'sem' => 'Семестр',

                            ) );          
        $out = '';
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        
        foreach ( $d['data'] as $item ) {
            
        // Семестр
            
            if ( isset( $d['data'][1] ) ) {

                $out .= '<div class="row coll"' . $style . '">';
                $out .= '<div class="col">';
                
                $out .= '<p class="mr-gray p-1 pl-3 mt-5"><strong>' . $t['sem'] . ' ' . $item['sem'] + 1 . '</strong></p>';
                
                $out .= '</div>';
                $out .= '</div>';
                
            }
            
        // Оценочные средства
        
        foreach ( $item['data'] as $item2 ) {
            
            $out .= '<div class="row coll"' . $style . '">';
            
            $out .= '<div class="col">';
            $out .= '<p class="pl-3">' . $item2['name'] . '</p>';
            $out .= '</div>';
            
            $out .= '<div class="col-1">';
            $out .= $item2['att']['rating'];
            $out .= '</div>';
            
            $out .= '<div class="col-3 col-lg-2">';
            $out .= $item2['att']['cmp'];
            $out .= '</div>';
            
            $out .= '</div>';

        }
            
        //     // p( $item );

        }

        return apply_filters( 'mif_mr_companion_lib_courses_get_item_body_evaluations', $out, $d );
    }



    //
    // body
    //

    public function get_item_body_content( $d )
    {
        // p($d);
        
        if ( empty( $d['data']) ) return '';

        $t = apply_filters( 'mif-mr-body-content-text', array( 
                                'target' => 'Цель освоения дисциплины',
                                'part' => 'Раздел',
                                'hours' => 'Трудоемкость',
                                'z' => 'знать', 
                                'u' => 'уметь', 
                                'v' => 'владеть', 
                                'cmp' => ' Компетенции', 
                            ) );          
        $out = '';
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        $is_indicator = ( $d['part'] == 'indicator' ) ? true : false;

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
                
                $out .= '<p class="mr-gray p-1 pl-3 mt-5"><strong>' . $t['part'] . ' ' . $item['sub_id'] + 1 . '.</strong> ' . $item['name'] . '</p>';
                
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
                        foreach ( (array) $item['outcomes']['v'] as $item2 ) $out .= '<p class="mb-3 pl-3">— ' . $item2 . '</p>';
                   
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
        // p($d);
        if ( empty( $d['data']) ) return '';

        $t = apply_filters( 'mif-mr-get-item-body', array(
            'biblio' => array( 'basic' => 'Основная литература', 'additional' => 'Дополнительная литература' ),
            'it' => array( 'inet' => 'Интернет-источники', 'app' => 'Программное обеспечение' ),
            'mto' => array( 'mto' => NULL ),
            'authors' => array( 'authors' => NULL ),
        ) );

        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        
        $ol = 'ol';   
        $li = 'li'; 
        
        if ( in_array( $d['part'], array( 'authors' ) ) ) {
            
            $ol = 'div';   
            $li = 'p'; 

        }

        $out = '';

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
    // Показать cписок 
    //

    public function get_lib_courses( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        ####!!!!!
        
        $f = true;
        
        $this->create( $opop_id, 'lib-courses' );
        
        $out = '';
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        
        $out .= '<div class="row">';
        
        $out .= '<div class="col">';
        $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека дисциплин</h4>';
        $out .= '</div>';
        
        $out .= '</div>';
        global $tree;

        $arr = array();
        if ( isset( $tree['content']['lib-courses']['data'] ) ) $arr = $tree['content']['lib-courses']['data'];
    
        // p($arr);

        foreach ( $arr as $item ) {
        
            $out .= '<div class="row mt-3 mb-3">';
            
            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            $out .= ( $item['parent'] == mif_mr_opop_core::get_opop_id() ||  $item['parent'] == 0 ) ?
                    '' :
                    '<a href="' .  get_permalink( $item['parent'] ) . 'lib-courses/' . $item['comp_id'] . '" title="' . 
                    $this->mb_substr( get_the_title( $item['parent'] ), 20 ) . '">' . $item['parent'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
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
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'lib-courses', $opop_id );
    
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr_courses', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $course_id )
    {
        $arr = array();
        
        $post = get_post( $course_id );
        
        $arr2 = explode( "\n", $post->post_content );
        $arr2 = array_map( 'strim', $arr2 );
        
        $data = array();
        $n = 0;
        
        foreach ( $arr2 as $item ) {
            
            if ( preg_match( '/^==/', $item ) ) $n++; 
            if ( ! isset( $data[$n] ) ) $data[$n] = '';
            $data[$n] .= $item . "\n";
            
        }
        
        // p($data);
             
        $arr3 = array();

        foreach ( $data as $item ) {
            
            if ( preg_match( '/^==\s*(?<key>\w+)/', $item, $m ) ) {

                switch ( $m['key'] ) {
                            
                    case 'content':

                        $c = new content( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                    
                    break;
                    
                    case 'evaluations':
                        
                        $c = new evaluations( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                    
                    case 'biblio':
                        
                        $c = new biblio( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                        
                    case 'it':

                        $c = new it( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                        break;
                        
                    case 'mto':

                        $c = new mto( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                    
                    case 'authors':
                            
                        $c = new authors( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );

                    break;
                            
                    // default:
                    // break;
                
                }
    
            }; 
            
        }    
       
        $arr4 = array();
        foreach ( $this->index_part as $item ) $arr4[$item] = ( isset( $arr3[$item] ) ) ? $arr3[$item] : NULL;

        $arr['comp_id'] = $course_id;
        $arr['parent'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['data'] = $arr4;
        
        return apply_filters( 'mif_mr_get_courses_arr', $arr, $course_id );
    }        
    
    
    
    
    //
    // Возвращает текст дисциплины из дерева
    //

    public function get_sub_arr( $course_id )
    {
        global $tree;    
        
        $data = array();
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) $data = $tree['content']['lib-courses']['data'][$course_id];
        
        foreach ( $data['data'] as $key => $item ) {
                
            $s = '';
            
            switch ( $key ) {
                
                case 'content':
                    
                    $s .= $item['target'] . "\n\n";
                    
                    foreach ( $item['parts'] as $item2 ) {
                        
                        $s .= '= ' . $item2['name'];
                        $s .= ' (' . $item2['cmp'] . ')';
                        $h = $item2['hours'];
                        $s .= ' (' . $h['lec'] . ', ' . $h['lab'] . ', ' . $h['prac'] . ', ' . $h['srs'] . ')';
                        $s .= "\n\n";


                        $s .= $item2['content'] . "\n\n";
                        
                        $arr2 = array();
                        for ( $i=0;  $i < 30;  $i++ ) $arr2[] = '-';
                        foreach ( (array) $item2['outcomes']['z'] as $key3 => $item3 ) $arr2[$key3 * 3] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['u'] as $key3 => $item3 ) $arr2[$key3 * 3 + 1] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['v'] as $key3 => $item3 ) $arr2[$key3 * 3 + 2] = '- ' . $item3;
                        while ( $arr2[ array_key_last( $arr2 ) ] == '-' ) unset( $arr2[ array_key_last( $arr2 ) ] ); 
                        
                        $s .= implode( "\n", $arr2 );
                        $s .= "\n\n";

                    }
                    
                break;
                
                case 'evaluations':
                
                    foreach ( $item as $item2 ) {
                        
                        foreach ( $item2['data'] as $item3 ) {
                            
                            $s .= $item3['name'];
                            if ( ! empty( $item3['att'] ) ) $s .= ' (' . implode( ") (", $item3['att'] ) . ')';
                            $s .= "\n";
                            
                        }

                        $s .= "\n";
    
                    }


                break;
                
                default:
                
                    foreach ( $item as $item2 ) {
                        
                        $s .= implode( "\n", $item2 );
                        $s .= "\n\n";

                    }
                
                break;
                
                
            }

            $arr[$key] = $s;
            
        }

        // p($arr);
        
        // $index = array( 
        //     'content',
        //     'evaluations',
        //     'biblio',
        //     'it',
        //     'mto',
        //     'guidelines',
        //     'authors',
        // );
        
        $arr2 = array();
        
        foreach ( $this->index_part as $item ) {
            
            if ( isset( $arr[$item] ) ) {
                
                $arr2[$item] = $arr[$item];
                unset( $arr[$item] );
                
            }
            
        }
        
        $arr2 = array_merge( $arr2, $arr );
        // p($arr2);
        
        return apply_filters( 'mif_mr_companion_course_get_sub_arr', $arr2, $course_id );
    }
    

    private $index_part = array();
    
}


?>