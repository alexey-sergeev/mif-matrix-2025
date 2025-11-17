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
        
        // $this->sub_default = apply_filters( 'mif-mr-sub_default', 'default' );
        
        
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
    //    $this->get_sub_arr( $course_id );
        // $out = '';

        // $out .= $course_id;
        
        // $arr = array();
        // if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) $arr = $tree['content']['lib-courses']['data'][$course_id];
        
        // p($arr);
        
        $out = '';
        $f = true;
        
        // $out .= '<div class="comp container no-gutters">';
        $out .= '<div class="content-ajax">';
        
        // if ( $f ) $out .= '<div><a href="' . get_edit_post_link( $course_id ) . '">Расширенный редактор</a></div>';
        
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) {
            
            $arr = $tree['content']['lib-courses']['data'][$course_id];
            // p($arr);
            
            
            $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $arr['name'] . '</h4>';
            $out .= '&nbsp;';
            // $out .= 'id: ' . $course_id;
            $out .= $this->get_show_all();
            
            // p($arr['data']);
            $out .= '<div class="comp container no-gutters">';
            
            // $coll = 
            // p($_REQUEST);
            // $save = ( isset( $_REQUEST['do'] ) ) ? sanitize_key( $_REQUEST['sub'] ) : NULL;
            $save = ( isset( $_REQUEST['do'] ) ) ? sanitize_key( $_REQUEST['part'] ) : NULL;

            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Содержание',
                                                'part' => 'content',
                                                'sub_id' => 'content',
                                                'data' => $arr['data']['content'],
                                                // 'coll' => true,
                                                // 'coll' => ( ( $save == NULL ) || ( $save == 'content' && $_REQUEST['name'] == 'Содержание' ) ) ? true : false,
                                                // 'coll' => ( $save == NULL || $save == 'content' ) ? true : false,
                                                'coll' => $this->coll_on_off( 'content', true ),
                                            ));
            
            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Оценочные средства',
                                                'part' => 'evaluations',
                                                'sub_id' => 'evaluations',
                                                'data' => $arr['data']['evaluations'],
                                                // 'coll' => true,
                                                // 'coll' => ( $save == 'evaluations' ) ? true : false,
                                                'coll' => $this->coll_on_off( 'evaluations', false ),
                                            ));

            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Индикаторы',
                                                'part' => 'indicator',
                                                'sub_id' => 'content',
                                                'data' => $arr['data']['content'],
                                                // 'indicator' => true,
                                                // 'coll' => ( $save == 'content' && $_REQUEST['name'] == 'Индикаторы' ) ? true : false,
                                                // 'coll' => ( $save == 'indicator' ) ? true : false,
                                                'coll' => $this->coll_on_off( 'indicator', false ),
                                            ));

            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Литература',
                                                'part' => 'biblio',
                                                'sub_id' => 'biblio',
                                                'data' => $arr['data']['biblio'],
                                                // 'title_sub' => array( 'Основная литература', 'Дополнительная литература' ),
                                                // 'index_sub' => array( 'basic', 'additional' ),
                                                'coll' => $this->coll_on_off( 'biblio', false ),
                                            ));
            
            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Информационные технологии',
                                                'part' => 'it',
                                                'sub_id' => 'it',
                                                'data' => $arr['data']['it'],
                                                // 'title_sub' => array( 'Интернет-источники', 'Программное обеспечение' ),
                                                // 'index_sub' => array( 'inet', 'app' ),
                                                'coll' => $this->coll_on_off( 'it', false ),
                                            ));

            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Материально-техническое обеспечение',
                                                'part' => 'mto',
                                                'sub_id' => 'mto',
                                                'data' => $arr['data']['mto'],
                                                // 'index_sub' => array( 'mto' ),
                                                'coll' => $this->coll_on_off( 'mto', false ),
                                            ));

            $out .= $this->get_course_part( array(
                                                'course_id' => $course_id,
                                                'name' => 'Разработчики',
                                                'part' => 'authors',
                                                'sub_id' => 'authors',
                                                'data' => $arr['data']['authors'],
                                                // 'index_sub' => array( 'authors' ),
                                                // 'ol' => 'div',
                                                // 'li' => 'p',
                                                'coll' => $this->coll_on_off( 'authors', false ),
                                            ));





    //         foreach ( $item['data'] as $item2 ) {
                    
    //             // if ( $f ) $out .= '<span>';
    //             $out .= '<span>';
                
    //             $out .= $this->show_comp_sub( $item2['sub_id'], $comp_id, $opop_id );
                
    //             $out .= '</span>';
    //             // if ( $f ) $out .= '</span>';
                
    //         }
        
            
            $out .= '</div>';
            
        }
        
    //     if ( $f ) $out .= '<div class="row mt-3">';
    //     if ( $f ) $out .= '<div class="col">';
    //     if ( $f ) $out .= '<small><a href="#" class="msg-remove">Удалить</a></small>';
    //     // if ( $f ) $out .= '<div class="alert" style="display: none;">Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a></div>';
    //     // if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . 
    //     //                     mif_mr_functions::get_callout( 'Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a>', 'warning' ) . '</div>';
        
    //     $msg = '<div>Вы уверены?</div>';

    //     $msg .= '<div><label class="form-label mt-4"><input type="checkbox" name="yes" value="on" class="form-check-input"> Да</label></div>';
    //     $msg .= '<button type="button" class="btn btn-primary mr-3 remove">Удалить <i class="fas fa-spinner fa-spin d-none"></i></button>';
    //     $msg .= '<button type="button" class="btn btn-light border mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
      
      
    //     if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . mif_mr_functions::get_callout( $msg, 'warning' ) . '</div>';
        
        
        
    //     if ( $f ) $out .= '</div>';
    //     if ( $f ) $out .= '</div>';

    //     // Hidden
        
        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $course_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="action" value="lib-courses">';
        if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        // $out .= '</div>';







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
        // p($d);
        
        // p($tree['content']['lib-courses']['data'][$d['course_id']]['data'][$d['part']]);
        $out = '';   
        
        $f = true;
        
        //     if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // $out .= '<span>';
        $out .= '<span class="content-ajax">';
  
        // $out .= '&nbsp;';
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
            // p($d);
            
            // Режим edit

            if ( $f ) $out .= $this->get_sub_edit( $d['sub_id'], $d['course_id'], $d['part'] );
            
        } else {
                
            // Режим отображения
            // p($d['part']);
            // p($_REQUEST);
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
        // $out .= '</span>';
        
        return apply_filters( ' mif_mr_lib_courses_get_course_sub', $out, $d );
    }





    //
    // body
    //

    public function get_item_body_evaluations( $d )
    {
        // p($d); 
        $t = apply_filters( 'mif-mr-body-evaluations-text', array( 
                                'sem' => 'Семестр',

                            ) );          
        $out = '';
        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        // $style = ' style="display: none;"';
        // if ( isset( $d['coll'] ) && $d['coll'] == false ) $style = '';
        
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

        // $style = ' style="display: none;"';
        // if ( isset( $d['coll'] ) && $d['coll'] == false ) $style = '';

        // Цель освоения дисциплины
        
        if ( ! $is_indicator ) {
            
            $out .= '<div class="row coll"' . $style . '>';
            $out .= '<div class="col">';
            $out .= '<p class="fw-bolder">' . $t['target'] . '</p>';
            $out .= '<p>' . $d['data']['target'] . '</p>';
            $out .= '</div>';
            $out .= '</div>';
            
        } 
        
        // Разделы
        
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
                
                if ( ! empty( $item['outcomes']['z'] ) ) $out .= '<p class="mb-1 mt-0 pl-3"><em>' . $t['z'] . ':</em></p>';
                foreach ( (array) $item['outcomes']['z'] as $item2 ) $out .= '<p class="mb-1 pl-3">— ' . $item2 . '</p>';
                
                if ( ! empty( $item['outcomes']['u'] ) ) $out .= '<p class="mb-1 mt-3 pl-3"><em>' . $t['u'] . ':</em></p>';
                foreach ( (array) $item['outcomes']['u'] as $item2 ) $out .= '<p class="mb-1 pl-3">— ' . $item2 . '</p>';
                
                if ( ! empty( $item['outcomes']['v'] ) ) $out .= '<p class="mb-1 mt-3 pl-3"><em>' . $t['v'] . ':</em></p>';
                foreach ( (array) $item['outcomes']['v'] as $item2 ) $out .= '<p class="mb-3 pl-3">— ' . $item2 . '</p>';
                
                // Компетенции 
                
                $out .= '<div class="pl-3 mt-5">';
                $out .= '<span class="p-1 pl-4 pr-4 rounded mr-green">' . $t['cmp'] . ': ';
                $out .= $item['cmp'];
                $out .= '</span>';
                $out .= '</div>';
    
                $out .= '</div>';
                $out .= '</div>';

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

        $t = apply_filters( 'mif-mr-get-item-body', array(
            'biblio' => array( 'basic' => 'Основная литература', 'additional' => 'Дополнительная литература' ),
            'it' => array( 'inet' => 'Интернет-источники', 'app' => 'Программное обеспечение' ),
            'mto' => array( 'mto' => NULL ),
            'authors' => array( 'authors' => NULL ),
        ) );

        $style = ( isset( $d['coll'] ) && $d['coll'] == false ) ? ' style="display: none;"' : '';
        // $style = ' style="display: none;"';
        // if ( isset( $d['coll'] ) && $d['coll'] == false ) $style = '';        
        
        $ol = 'ol';   
        $li = 'li'; 
        
        if ( in_array( $d['part'], array( 'authors' ) ) ) {
            
            $ol = 'div';   
            $li = 'p'; 

        }

        $out = '';

        foreach ( $t[$d['part']] as $key => $item ) {
          
            // $out .= '<div class="row coll mb-3 mt-3">';
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
        
        // p('@');

        
        // // p($list);
        // // p($arr);
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        // $out .= '<div class="comp container no-gutters">';
        
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
        
        //     // p($item);

            $out .= '<div class="row mt-3 mb-3">';
            
            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            $out .= ( $item['parent'] == mif_mr_opop_core::get_opop_id() ||  $item['parent'] == 0 ) ?
                    // $item['parent'] :
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
    
        // $out .= '</div>';
        
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
        // $arr_raw = array();
        
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
        // $arr3 = array( 
        //     'content' => NULL,
        //     'evaluations' => NULL,
        //     'biblio' => NULL,
        //     'it' => NULL,
        //     'mto' => NULL,
        //     'authors' => NULL,
        //     'guidelines' => NULL,
        // );
        
        foreach ( $data as $item ) {
            
            if ( preg_match( '/^==\s*(?<key>\w+)/', $item, $m ) ) {

                // p($item);
                // p($m['key']);
                
                switch ( $m['key'] ) {
                            
                    case 'content':

                        $c = new content( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);
                    
                    break;
                    
                    // case 'parts':
                        
                    //     $c = new parts( $item );
                    //     $arr3 = array_merge( $c->get_arr(), $arr3 );
                    //     // $arr3[] = $c->get_arr();
                    //     // p($c->get_arr());
                    //     // p($item);
                        
                    // break;
                            
                    case 'evaluations':
                        
                        $c = new evaluations( $item );
                        // $arr3[] = $c->get_arr();
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);
                        
                    break;
                    
                    case 'biblio':
                        
                        $c = new biblio( $item );
                        // $arr3[] = $c->get_arr();
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);
                        
                    break;
                        
                    case 'it':

                        $c = new it( $item );
                        // $arr3[] = $c->get_arr();
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);
                        
                        break;
                        
                    case 'mto':

                        $c = new mto( $item );
                        // $arr3[] = $c->get_arr();
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);
                        
                    break;
                    
                    case 'authors':
                            
                        $c = new authors( $item );
                        // $arr3[] = $c->get_arr();
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        // p($c->get_arr());
                        // p($item);

                    break;
                            
                    // default:
                    // break;
                
                }
    
            }; 
            
        }    
        
        $arr['comp_id'] = $course_id;
        $arr['parent'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['data'] = $arr3;
        
        return apply_filters( 'mif_mr_get_courses_arr', $arr, $course_id );
    }        
    
    
    
    
    //
    // Возвращает текст дисциплины из дерева
    //

    public function get_sub_arr( $course_id )
    {
        global $tree;    
        
        // $out = '';
        // p($tree['content']['lib-courses']['data'][$course_id]);
        $data = array();
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) $data = $tree['content']['lib-courses']['data'][$course_id];
        
        // $out = array();
        
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
                        // p($item2);
                        
                        $arr2 = array();
                        for ( $i=0;  $i < 30;  $i++ ) $arr2[] = '-';
                        foreach ( (array) $item2['outcomes']['z'] as $key3 => $item3 ) $arr2[$key3 * 3] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['u'] as $key3 => $item3 ) $arr2[$key3 * 3 + 1] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['v'] as $key3 => $item3 ) $arr2[$key3 * 3 + 2] = '- ' . $item3;
                        while ( $arr2[ array_key_last( $arr2 ) ] == '-' ) unset( $arr2[ array_key_last( $arr2 ) ] ); 
                        // p($arr2);
                        
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
                
                    // $s .= '== ' . $key . "\n\n";
                    
                    foreach ( $item as $item2 ) {
                        
                        $s .= implode( "\n", $item2 );
                        $s .= "\n\n";

                    }
                
                break;
                
                
            }

            $arr[$key] = $s;
            
            //     p($item);
        // //     $s = '';
        // //     $s .= '= ' . $item['name'] . "\n\n";
            
        // //     if ( empty( $item['data'] ) ) continue;
            
        // //     foreach ( $item['data'] as $item2 ) {
                    
        // //         $s .= $item2['name'] . '. ';
        // //         $s .= $item2['descr'] . "\n\n";
                
        // //         if ( empty( $item2['indicators'] ) ) continue;
                
        // //         foreach ( $item2['indicators'] as $item3 ) {
                        
        // //             $s .= implode( "\n", $item3 );
        // //             $s .= "\n\n";
                    
        // //         }
                
        // //         $s .= "\n";
                
        // //     }
            
        //     // $out[$item['sub_id']] = $s;

        }

        // p($arr);

        return apply_filters( 'mif_mr_companion_course_get_sub_arr', $arr, $course_id );
    }
    

    

    
    
    
    
    
    
    
    
    



    
    



    
    // //
    // // Форму создания 
    // //
    
    // private function show_lib_courses_create()
    // {
    //     $out = '';        
        
    //     $out .= '<div class="row mt-5">';
    //     $out .= '<div class="col">';
    //     // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
    //     $out .= '<button type="button" class="btn btn-primary new">Создать дисциплин</button>';
    //     $out .= '</div>';
    //     $out .= '</div>';
        
    //     $out .= '<div class="row new" style="display: none;">';
    //     $out .= '<div class="col mt-5">';
    //     // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        
    //     // $out .= '';
        
    //     $out .= '<div class="mb-3">';
    //     // $out .= '<label class="form-label">Название перечня компетенций</label>';
    //     $out .= '<label class="form-label">Название cписка компетенций:</label>';
    //     $out .= '<input name="title" class="form-control" autofocus>';
    //     $out .= '<div class="form-text">Например: ФГОС "Информатика и вычислительная техника", ОПОП "Математика", ...</div>';
    //     $out .= '</div>';
        
    //     $out .= '<div class="mb-3">';
    //     // $out .= '<label class="form-label">Данные <i class="fa-regular fa-circle-question" style="color: #aaa;"></i></label>';
    //     $out .= '<label class="form-label">Данные:</label>';
    //     // $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
    //     $out .= '<textarea name="data" class="form-control" rows="3"></textarea>';
    //     $out .= '<div class="form-text">Например: УК-1. Способен использовать философские знания, ... (<a href="' . '123' . '">помощь</a>)</div>';
    //     $out .= '<button type="button" class="btn btn-primary mt-4 mr-3 create">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
    //     $out .= '<button type="button" class="btn btn-light border mt-4 mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
        
    //     $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
    //     $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
    //     $out .= '</div>';
    //     $out .= '</div>';
       
    //     return apply_filters( 'mif_mr_show_list_compe_create', $out );
    // }
    
    
    
    // //
    // // Показать компетенции
    // //
    
    // public function show_comp( $comp_id = NULL, $opop_id = NULL )
    // {
    //     // Init $comp_id, $opop_id        
        
    //     if ( empty( $comp_id ) ) {
                    
    //         global $wp_query;
    //         if ( ! isset( $wp_query->query_vars['id'] ) ) return 'wp: error 1';
    //         $comp_id = $wp_query->query_vars['id'];

    //     }

    //     if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
           
        
    //     // Save

    //     if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) {
                    
    //         if ( isset( $_REQUEST['sub'] ) ) $this->save( (int) $_REQUEST['sub'], $comp_id, $opop_id );

    //     }


    //     // HTML

    //     global $tree;
        
    //     $out = '';
    //     $f = true;

    //     $out .= '<div class="content-ajax">';

    //     // $out .= '<div class="pb-4"><a href="' . get_permalink( $opop_id ) .'competencies/">← Вернуться к странице раздела</a></div>';
    //     if ( $f ) $out .= '<div><a href="' . get_edit_post_link( $comp_id ) . '">Расширенный редактор</a></div>';
        
    //     if ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) ) {
        
    //         $item = $tree['content']['lib-competencies']['data'][$comp_id];


    //         $out .= '<h4 class="mb-6 mt-5">' . $item['name'] . '</h4>';
    //         $out .= 'id: ' . $comp_id;
    //         $out .= $this->get_show_all();
    //         // $out .= '<div class="text-end">';
    //         // $out .= '<small><a href="#" id="roll-down-all">Показать всё</a> | <a href="#" id="roll-up-all">Свернуть</a></small>';
    //         // $out .= '</div>';

    //         $out .= '<div class="container no-gutters">';

    //         foreach ( $item['data'] as $item2 ) {
                        
    //             // if ( $f ) $out .= '<span>';
    //             $out .= '<span>';
                
    //             $out .= $this->show_comp_sub( $item2['sub_id'], $comp_id, $opop_id );
                
    //             $out .= '</span>';
    //             // if ( $f ) $out .= '</span>';
                
    //         }
        
    //         // New

    //         if ( $f ) $out .= '<div class="row mb-3 mt-6">';
    //         if ( $f ) $out .= '<div class="col mr-gray p-3 fw-bolder text-center">';
    //         if ( $f ) $out .= '<a href="#" class="new"><i class="fa-solid fa-plus fa-xl"></i></a>';
    //         if ( $f ) $out .= '</div>';
    //         if ( $f ) $out .= '</div>';
            
    //         $out .= '</div>';
            
    //     }
        
    //     if ( $f ) $out .= '<div class="row mt-3">';
    //     if ( $f ) $out .= '<div class="col">';
    //     if ( $f ) $out .= '<small><a href="#" class="msg-remove">Удалить</a></small>';
    //     // if ( $f ) $out .= '<div class="alert" style="display: none;">Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a></div>';
    //     // if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . 
    //     //                     mif_mr_functions::get_callout( 'Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a>', 'warning' ) . '</div>';
        
    //     $msg = '<div>Вы уверены?</div>';

    //     $msg .= '<div><label class="form-label mt-4"><input type="checkbox" name="yes" value="on" class="form-check-input"> Да</label></div>';
    //     $msg .= '<button type="button" class="btn btn-primary mr-3 remove">Удалить <i class="fas fa-spinner fa-spin d-none"></i></button>';
    //     $msg .= '<button type="button" class="btn btn-light border mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
      
      
    //     if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . mif_mr_functions::get_callout( $msg, 'warning' ) . '</div>';
        
        
        
    //     if ( $f ) $out .= '</div>';
    //     if ( $f ) $out .= '</div>';

    //     // Hidden
        
    //     if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
    //     if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
    //     if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
    //     $out .= '</div>';
        
    //     return apply_filters( 'mif_mr_show_competencies', $out );
    // }
    
    
    
    
    // //
    // // Показать всё
    // //
    
    // public static function get_show_all()
    // {
    //     $out = '';        
        
    //     $out .= '<div class="text-end">';
    //     $out .= '<small><a href="#" id="roll-down-all">Показать всё</a> | <a href="#" id="roll-up-all">Свернуть</a></small>';
    //     $out .= '</div>';

    //     return apply_filters( 'mif_mr_get_show_all', $out );
    // }



    // //
    // // Показать cписок компетенций - часть
    // //
    
    // public function show_comp_sub( $sub_id, $comp_id, $opop_id = NULL )
    // {
    //     global $tree;        
        
    //     $f = true;
        
    //     if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
    //     if ( ! ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) || $sub_id == '-1' ) ) return 'wp: error 2';
        
    //     $item = $tree['content']['lib-competencies']['data'][$comp_id]['data'][$sub_id];
    //     // $style = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) ? '' : 'style="display: none;"';
        
    //     // HTML
        
    //     $out = '';   
    //     $out .= '<span class="content-ajax">';
        
    //     $out .= $this->get_sub_head( array(
    //                                         'name' => $item['name'],        
    //                                         'sub_id' => $sub_id,
    //                                         'f' => $f            
    //                                         ) );

    //     // $out .= '<div class="row mb-3 mt-3">';
        
    //     // // Наименование категории

    //     // $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
    //     // $out .= $item2['name'];
    //     // $out .= '</div>';
        
    //     // // Кнопка edit

    //     // $out .= '<div class="col-1 mr-gray p-3 text-end">';
    //     // if ( $f ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
    //     // if ( $f ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $sub_id . '"><i class="fa-regular fa-pen-to-square"></i></a>';
    //     // $out .= '<a href="#" class="roll-up d-none"><i class="fa-solid fa-angle-up"></i></a>';
    //     // $out .= '<a href="#" class="roll-down"><i class="fa-solid fa-chevron-down"></i></a>';
    //     // $out .= '</div>';
        
    //     // $out .= '</div>';

    //     if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
                    
    //         // Режим edit

    //         if ( $f ) $out .= $this->get_sub_edit( $sub_id, $comp_id, $opop_id );
            
    //     } else {
                    
    //         // Режим отображения
            
    //         foreach ( $item['data'] as $item2 ) $out .= $this->get_item_body( $item2 );

    //     }
        
    //     $out .= '</span>';
   
    //     return apply_filters( ' mif_mr_show_competencies_sub', $out, $sub_id, $comp_id, $opop_id );
    // }
    
    
    





    
    // //
    // // Показать cписок компетенций - компетенция, head
    // //
    
    // public static function get_sub_head( $item )
    // {
    //     if ( ! isset( $item['sub_id'] ) ) $item['sub_id'] = 0;        
    //     if ( ! isset( $item['f'] ) ) $item['f'] = false;
        
    //     $out = '';
        
    //     $out .= '<div class="row mb-3 mt-3">';
        
    //     // Наименование категории

    //     $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
    //     $out .= $item['name'];
    //     $out .= '</div>';
        
    //     // Кнопка edit

    //     $out .= '<div class="col-1 mr-gray p-3 text-end">';
    //     if ( $item['f'] ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
    //     if ( $item['f'] ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $item['sub_id'] . '"><i class="fa-regular fa-pen-to-square"></i></a>';
    //     $out .= '<a href="#" class="roll-up d-none"><i class="fa-solid fa-angle-up"></i></a>';
    //     $out .= '<a href="#" class="roll-down"><i class="fa-solid fa-chevron-down"></i></a>';
    //     $out .= '</div>';
        
    //     $out .= '</div>';

    //     return apply_filters( ' mif_mr_get_item_head', $out, $item );
    // }





    
    // //
    // // Показать cписок компетенций - компетенция, body
    // //
    
    // public static function get_item_body( $item )
    // {
    //     $name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );        
        
    //     $out = '';

    //     $out .= '<div class="row">';
        
    //     $out .= '<div class="col col-2 col-md-1 fw-bolder">';
    //     $out .= $item['name'];
    //     $out .= '</div>';
        
    //     $out .= '<div class="col mb-3">';
    //     $out .= $item['descr'];
    //     $out .= '</div>';
        
    //     $out .= '</div>';

    //     // Индикаторы

    //     if ( isset( $item['indicators'] ) ) {
        
    //         foreach ( (array) $item['indicators'] as $key4 => $item4 ) {
                        
    //             $style = ' style="display: none;"';

    //             $out .= '<div class="row coll"' . $style . '>';
                
    //             $out .= '<div class="col col-2 col-md-1">';
    //             $out .= '</div>';
                
    //             $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
    //             $out .= ( isset( $name_indicators[$key4] ) ) ? $name_indicators[$key4] : 'индикатор ' . $key4;
    //             $out .= '</div>';
                
    //             $out .= '</div>';
                
    //             $out .= '<div class="row coll"' . $style . '">';
    //             $out .= '<div class="col col-2 col-md-1">';
    //             $out .= '</div>';
                
    //             $out .= '<div class="col">';
    //             foreach ( $item4 as $item5 ) $out .= '<p class="m-2">' . $item5 . '</p>';
    //             $out .= '</div>';
    //             $out .= '</div>';
                
    //         }
            
    //         $out .= '<div class="row coll"' . $style . '">';
    //         $out .= '<div class="col col-2 col-md-1">';
    //         $out .= '</div>';
            
    //         $out .= '<div class="col">';
    //         $out .= '&nbsp;';
    //         $out .= '</div>';
    //         $out .= '</div>';
            
        
    //     }
        
    //     return apply_filters( ' mif_mr_get_item_body', $out, $item );
    // }

    


 
    // //
    // // 
    // //
    
    // public static function set_comp_to_tree( $t = array() )
    // {
    //     $arr = array();
        
    //     foreach ( $t['content']['set-competencies']['data'] as $item ) {

    //         if ( is_numeric( $item[2] ) ) {

    //             if ( isset( $t['content']['lib-competencies']['data'][$item[2]] ) )
    //                 foreach ( $t['content']['lib-competencies']['data'][$item[2]]['data'] as $item2 ) 
    //                     foreach ( $item2['data'] as $item3 ) 
    //                         if ( $item3['name'] == $item[1] ) { 
    //                             $item3['old_name'] = $item3['name'];
    //                             $item3['comp_id'] = $item[2];
    //                             $item3['name'] = $item[0];
    //                             $arr[$item[0]] = $item3;
    //                         }
    //         } else {
                
    //             foreach ( $t['content']['lib-competencies']['data'] as $item2 ) 
    //                 foreach ( $item2['data'] as $item3 ) 
    //                     foreach ( $item3['data'] as $item4 ) 
    //                         if ( $item4['name'] == $item[1] ) {
    //                             $item4['old_name'] = $item4['name'];
    //                             $item4['comp_id'] = $item2['comp_id'];
    //                             $item4['name'] = $item[0];
    //                             $arr[$item[0]] = $item4;
    //                         } 

    //         }

    //     }

    //     // p($arr);

    //     return apply_filters( 'mif_mr_comp_set_comp_to_tree', $arr, $t );
    // }




    // private function get_begin_data( $post )
    // {
    //     $data = '';
    //     if ( ! preg_match( '/^=/', $post->post_content ) ) $data .= "= " . $this->sub_default . "\n";
    //     $data .= $post->post_content;
    //     return $data;
    // }


    
    // // private $name_indicators = array();
    // private $sub_default = '';
   
}


?>