<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_courses extends mif_mr_tools_core {
    
    function __construct()
    {
        parent::__construct();

        add_filter( 'lib-upload-save-title', array( $this, 'set_save_title'), 10, 2 );
        add_filter( 'scheme-data-courses', array( $this, 'scheme_data_courses'), 10 );

        $this->scheme = apply_filters( 'scheme-data-courses', array() );
        // $this->index_part =  apply_filters( 'index-courses-part', array( 
        //                                                     'content',
        //                                                     'evaluations',
        //                                                     'biblio',
        //                                                     'it',
        //                                                     'mto',
        //                                                     'guidelines',
        //                                                     'authors',
        //                                                 ) );
    }
        

    

    // 
    // Показывает  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools-courses.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools-courses.php', false );

        }
    }
    




    // 
    //   
    // 

    public function get_tools_courses()
    {
        $att_id = mif_mr_functions::get_att_id();

        $out = '';

        // p($_FILES);
        // p($_REQUEST);
        
        // Разбор формы
        
        $m = new mif_mr_upload();
        // $res = $m->save( array( 'ext' => array( 'png' ) ) ); 
        $res = $m->save( array( 'ext' => array( 'xls', 'xlsx' ) ) ); 
            
        foreach ( (array) $res as $i ) {
            
            $out .= mif_mr_functions::get_callout( 
                $i['name'] . ' — <span class="fw-semibold">' . $i['messages'] . '</span>', 
                $i['status'] ); 

            if ( isset( $i['id'] ) ) $att_id = $i['id'];

        }        



        // Показать форму

        $out .= $m->form_upload( array( 
                            'text' => 'Загрузите файл шаблона учебной дисциплины в формате Excel', 
                            // 'title_placeholder' => 'Название плана', 
                            'url' => 'tools-courses',
                            'multiple' => true 
                        ) );
        

        // Показать список файлов courses
        
        $out .= $this->show_list_file_courses();
        
        $out .= $this->get_meta( 'courses' );


        // Показать courses

        if ( ! empty( $att_id ) ) $out .= $this->show_file_courses( $att_id );
        
        return apply_filters( 'mif_mr_get_tools_courses', $out );

    }
    

    
    
    // 
    // Вывести  
    // 

    public function show_list_file_courses()
    {
        $out = '';

        $arr = $this->get_file( array( 'ext' => array( 'xls', 'xlsx' ) ) );
        // $arr = $this->get_file();

        // p($arr);

        $out .= '<div class="container mt-5">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">№</div>';
        $out .= '<div class="col p-2 pt-4 pb-4 fw-semibold">Название дисциплины</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">Файл</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '</div>';
        
        $n = 0;
        $out .= '<div class="striped">';
        
        foreach ( $arr as $item ) {
            
            $out .= '<div class="row">';
            $out .= '<div class="col col-1 p-2">' . ++$n . '</div>';
            $out .= '<div class="col p-2"><a href="' .  mif_mr_opop_core::get_opop_url() . 'tools-courses/' . $item->ID . '">' . $item->post_title . '</a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="' . $item->guid . '"><i class="fa-regular fa-file-code fa-lg"></i></a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="#" class="remove" data-attid="' . $item->ID . '"><i class="fa-regular fa-trash-can fa-lg"></i></a></div>';
            $out .= '</div>';
            
        }
            
        $out .= '</div>';

            $style = ( $n === 0 ) ? '' : ' style="display: none;"'; 
            $out .= '<div class="row no-plans"' . $style . '>';
            $out .= '<div class="col p-4 text-center bg-light border rounded fw-semibold">Нету дисциплин</div>';
            $out .= '</div>';

        $out .= '</div>';

        return $out;
    }


    

    
    // 
    // Вывести  
    // 

    public function show_file_courses( $att_id )
    {
        $att = get_post( $att_id );
        
        if ( empty( $att ) ) return;
        if ( $att->post_type != 'attachment' ) return;
        if ( ! in_array( mif_mr_functions::get_ext( $att->guid ), array( 'xls', 'xlsx' ) ) ) return;
        
        $arr = $this->set_courses_form_xls( $att_id );

        p($arr);

        // p($this->scheme);

        $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
        $c = $m->get( $this->scheme['name'][0] );

        
        
        //  ## @@@ ### !!!!!!!!!!
        
        // $arr = $m->get_arr();
        
        // foreach ( $arr as $k => $i )
        // foreach ( $i as $k2 => $i2 ) {

        //     if ( empty( $i2 ) ) continue;
        //     if ( preg_match( '/^\[/', $i2 ) ) {
                
        //         $i2 = trim( $i2, '[]' );
        //         p(  '$arr["' . $i2 . '"][] = "' . $k2 . $k . '";' );

        //     }
        //     // p($k);
        //     // p($k2);
    
    
        // }





        // p($c);

        $out = '';
        
        $out .= '<p>.';
        $out .= '<p>.';
        $out .= '<p>.';

        $out .= '<div class="container show-file" data-attid="' . $att_id . '">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-3 text-center">' . $arr['name'] . '</div>';
        $out .= '</div>';

        $out .= '<div class="row">';
        $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из файла</div>';
        $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из Matrix</div>';
        $out .= '</div>';
        
        // Цели

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Цели</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'target' );
        $out .= '</div>';

        // Разделы (содержание)

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (содержание)</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'part', 'content' );
        $out .= '</div>';

        // Разделы (компетенции)

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (компетенции)</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'part', 'cmp' );
        $out .= '</div>';

        // Разделы (трудоемкость)

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (трудоемкость)</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'part', 'hours' );
        $out .= '</div>';

        // Разделы (знать, уметь, владеть)

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (знать, уметь, владеть)</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'part', 'outcomes' );
        $out .= '</div>';
  
        // Оценочные средства

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Оценочные средства</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'evaluations' );
        $out .= '</div>';
  
        // Основная литература

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Основная литература</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'biblio', 'basic' );
        $out .= '</div>';
  
        // Дополнительная литература

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Дополнительная литература</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'biblio', 'additional' );
        $out .= '</div>';
  
        // Ресурсы Интернета

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Ресурсы Интернета</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'it', 'inet' );
        $out .= '</div>';
  
        // Программное обеспечение

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Программное обеспечение</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'it', 'app' );
        $out .= '</div>';
  
        // Материально-техническое обеспечение

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Материально-техническое обеспечение</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'mto', 'mto' );
        $out .= '</div>';
  
        // Разработчики

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разработчики</div>';
        $out .= '</div>';
        
        $out .= '<div class="row">';
        $out .= $this->get_course_part( $arr, 'authors', 'authors' );
        $out .= '</div>';





        $out .= '</div>';
        
        // $out .= '<input type="hidden" name="attid" value="' . $att_id . '" />'; 
        
        
        return $out;
        }
        
        
        
        
        private function get_course_part( $arr, $key, $key2 = NULL )
        {
            $out = '';    

            $out .= '<div class="col col-6 mt-3 mb-3">';
            
            switch ( $key ) {
                        
                case 'target':
        
                    $out .= '<div class="mb-3">' . $arr['target'] . '</div>';

                break;
                
                case 'part':

                    foreach ( $arr['part'] as $i ) {

                        $out .= '<div class="mb-3 fw-bold">= ' . $i['name'] . '</div>';
                        // $out .= '<div class="mb-3">= ' . $i['name'] . '</div>';
                        if ( $key2 == 'content' ) $out .= '<div class="mb-3">' . $i['content'] . '</div>';
                        if ( $key2 == 'hours' ) $out .= '<div class="mb-3">' . $i['hours'] . '</div>';
                        if ( $key2 == 'cmp' ) $out .= '<div class="mb-3">' . $i['cmp'] . '</div>';
                        if ( $key2 == 'outcomes' ) {
                            
                            $ii = array_diff( $i['outcomes']['z'], array( '' ) );
                            $out .= '<div class="mb-3"><i>знать:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                            $ii = array_diff( $i['outcomes']['u'], array( '' ) );
                            $out .= '<div class="mb-3"><i>уметь:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                            $ii = array_diff( $i['outcomes']['v'], array( '' ) );
                            $out .= '<div class="mb-3"><i>владеть:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                        
                        }

                    }
                
                break;
                
                case 'evaluations':
                    
                    foreach ( $arr['evaluations'] as $k => $d ) {
                    
                        if ( $k != 0 ) $out .= '<br />';

                        foreach ( $d['data'] as $i ) {

                            $out .= '<div>' . $i['name'] . ' (' . $i['att']['rating'] . ') (' . $i['att']['cmp'] . ')' . '</div>';

                        }

                    }
                    
                    
                break;
                
                case 'biblio':

                    if ( $key2 == 'basic' ) $out .= '<div class="mb-3">' . implode( '<br /><br />', $arr['biblio']['basic'] ) . '</div>';
                    if ( $key2 == 'additional' ) $out .= '<div class="mb-3">' . implode( '<br /><br />', $arr['biblio']['additional'] ) . '</div>';
                    
                break;
                
                case 'it':

                    if ( $key2 == 'inet' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['it']['inet'] ) . '</div>';
                    if ( $key2 == 'app' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['it']['app'] ) . '</div>';
                    
                break;
                
                case 'mto':
                    
                    if ( $key2 == 'mto' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['mto']['mto'] ) . '</div>';
                    
                break;
                
                case 'authors':
                    
                    if ( $key2 == 'authors' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['authors']['authors'] ) . '</div>';
                
                break;
                        
                // default:
                // break;
            
            }

            $out .= '</div>';

            return $out;
        }
        
        
    






    
    
    // 
    //   
    // 
    
    public function set_courses_form_xls( $att_id )
    {

        $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
        // $c = $m->get( $this->scheme['name'][0] );
        $arr = array();

        // Название, Цель

        $arr['name'] = $m->get( $this->scheme['name'][0] );
        $arr['target'] = $m->get( $this->scheme['target'][0] );

        // Разделы

        for ( $i = 0; $i < 10; $i++ ) { 

            if ( empty( $m->get( $this->scheme['parts_name'][$i] ) ) ) continue;

            $arr['part'][$i]['name'] = $m->get( $this->scheme['parts_name'][$i] );
            $arr['part'][$i] ['cmp']= $m->get( $this->scheme['parts_cmp'][$i] );
            $arr['part'][$i] ['hours']= $m->get( $this->scheme['parts_hours'][$i] );
            $arr['part'][$i]['content'] = $m->get( $this->scheme['parts_content'][$i] );
            
            for ( $j = 0; $j < 10; $j++ ) { 
                
                if ( isset( $this->scheme['parts_outcomes_z_'.$j][$i] ) ) $arr['part'][$i]['outcomes']['z'][] = $m->get( $this->scheme['parts_outcomes_z_'.$j][$i] );
                if ( isset( $this->scheme['parts_outcomes_u_'.$j][$i] ) ) $arr['part'][$i]['outcomes']['u'][] = $m->get( $this->scheme['parts_outcomes_u_'.$j][$i] );
                if ( isset( $this->scheme['parts_outcomes_v_'.$j][$i] ) ) $arr['part'][$i]['outcomes']['v'][] = $m->get( $this->scheme['parts_outcomes_v_'.$j][$i] );

            }
            
        }

        // Оценочные средства
            
        for ( $i = 0; $i < 10; $i++ ) {

            for ( $j = 0; $j < 10; $j++ ) { 

                if ( empty( $this->scheme['evaluations_name_'.$i][$j] ) ) continue;
                if ( empty( $m->get( $this->scheme['evaluations_name_'.$i][$j] ) ) ) continue;
                
                $arr['evaluations'][$j]['sem'] = $j;
                if ( isset( $this->scheme['evaluations_name_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['name'] = $m->get( $this->scheme['evaluations_name_'.$i][$j] );
                if ( isset( $this->scheme['evaluations_rating_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['rating'] = $m->get( $this->scheme['evaluations_rating_'.$i][$j] );
                if ( isset( $this->scheme['evaluations_cmp_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['cmp'] = $m->get( $this->scheme['evaluations_cmp_'.$i][$j] );
                
    
            }

        } 

        // Литература

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['biblio_basic'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['biblio_basic'][$i] ) ) ) continue;
            
            $arr['biblio']['basic'][] = $m->get( $this->scheme['biblio_basic'][$i] );

        } 

        for ( $i = 0; $i < 10; $i++ ) {
            
            if ( empty( $this->scheme['biblio_additional'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['biblio_additional'][$i] ) ) ) continue;
            
            $arr['biblio']['additional'][] = $m->get( $this->scheme['biblio_additional'][$i] );
        
        } 
        
        // Информационные технологии
        
        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['it_inet'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['it_inet'][$i] ) ) ) continue;
            
            $arr['it']['inet'][] = $m->get( $this->scheme['it_inet'][$i] );

        } 
        
        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['it_app'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['it_app'][$i] ) ) ) continue;
            
            $arr['it']['app'][] = $m->get( $this->scheme['it_app'][$i] );

        } 
    
        // Материально-техническое обеспечение

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['mto'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['mto'][$i] ) ) ) continue;
            
            $arr['mto']['mto'][] = $m->get( $this->scheme['mto'][$i] );

        } 
    
        // Разработчики

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['authors'][$i] ) ) continue;
            if ( empty( $m->get( $this->scheme['authors'][$i] ) ) ) continue;
            
            $arr['authors']['authors'][] = $m->get( $this->scheme['authors'][$i] );

        } 
    

        //
        // guidelines ???? !!!!
        //








        // p($this->scheme);


        return $arr;
    }


        
    
    // //
    // // Анализ
    // // 
    
    // public function analysis( $att = array() )
    // {
    //     // p($_REQUEST);
    //     // p($att);
    //     $out = '';

    //     $m = new mif_mr_part_companion();
    //     $data[0] = $this->get_date_from_plx( $att['key'], $att['att_id'] );
    //     $data[1] = $m->get_companion_content( $att['key'], $att['opop_id'] );
    
    //     $data = $this->analysis_process( $data, $att['method'] );

    //     $one = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[0] . '</div>';
    //     $two = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[1] . '</div>';
        
    //     $out .= '<div class="container mt-5 fullsize-fix">';

    //     $out .= '<div class="row">';
    //     $out .= '<div class="col d-none d-sm-block mt-5 pr-0 text-end">';
    //     $out .= '<a href="#" class="text-secondary" id="fullsize">';
    //     $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
    //     $out .= '</a>';
    //     $out .= '</div>';
    //     $out .= '</div>';

    //     $out .= '<div class="row">';

    //     $out .= '<div class="col col-6 p-0 pr-2 pt-4 pb-4"><div class="text-center fw-semibold">из файла</div>' . $one. '</div>';
    //     $out .= '<div class="col col-6 p-0 pl-2 pt-4 pb-4"><div class="text-center fw-semibold">из Matrix</div>' . $two. '</div>';

    //     $out .= '</div>';
   
    //     $out .= '<div class="row">';

    //     $out .= '<div class="col p-3 bg-light">';
    //     $out .= '<a href="#" class="mr-3 save" data-yes="yes">Сохранить</a>';
    //     $out .= '<a href="#" class="mr-3 cancel-analysis">Отменить</a>';
    //     $out .= '</div>';

    //     $out .= '<div class="col p-3 bg-light text-end">';
    //     $out .= '<a href="#" class="mr-3 help"><i class="fa-solid fa-circle-question fa-xl"></i></a>';
    //     $out .= '</div>';

    //     $out .= '</div>';
  
    //     $out .= '<div class="row">';

    //     $out .= '<div class="col bg-light help-box" style="display: none;">';
    //     $out .= mif_mr_functions::get_callout( 
    //                 'белые — эти строки доступны одинаково как в первом, так и во втором списке<br />
    //                  желтые — есть дисциплина, но параметры другие<br />
    //                  красные — это строки, которые написаны один раз, отсутствуют в другом списке.
    //                 ', 'info' );;
    //     $out .= '</div>';

    //     $out .= '</div>';
  
    //     $out .= '</div>';

    //     return $out;
    // }




    // private function analysis_process( $data = array(), $method = '' )
    // {

    //     foreach ( array( 0, 1 ) as $k ) $arr[$k] = array_map( 'strim', explode( "\n", $data[$k] ) );     
        
    //     foreach ( array( 0, 1 ) as $k ) 
    //     foreach ( $arr[$k] as $item ) {
        
    //         if ( empty( $method ) ) {
                
    //             $p = new parser();
    //             $d = $p->parse_name( $item );
                
    //         } elseif ( $method == 'dots' ) {
                
    //             $p = new attributes( $item );
    //             $a = array_keys( $p->get_arr() );
    //             $d['name'] = $a[0];

    //         }

    //         // p('$d');
    //         // p($d);

    //         $a = trim( preg_replace( '/' . $d['name'] . '/', '', $item ) ); 
    //         $arr2[$k][] = array( $d['name'], $a );       
            
    //         $arr3[$k][] = 2;

    //     }

    //     foreach ( $arr2[0] as $k => $i )
    //     foreach ( $arr2[1] as $k2 => $i2 ) {
        
    //         if ( $i[0] === $i2[0] ) {

    //             if ( $arr3[0][$k] != 0 ) $arr3[0][$k] = 1;
    //             if ( $arr3[1][$k2] != 0 ) $arr3[1][$k2] = 1;

    //         }
            
    //         if ( $i[0] === $i2[0] && $i[1] === $i2[1] ) {
                
    //             $arr3[0][$k] = 0;
    //             $arr3[1][$k2] = 0;
            
    //         }

    //     }
        
    //     $f = true;
    //     foreach ( array( 0, 1 ) as $k ) if ( empty( $data[$k]) ) $f = false;
        
    //     foreach ( array( 0, 1 ) as $k ) foreach ( $arr[$k] as $key => $item ) {
            
    //         $c = '';
            
    //         if ( $f && $arr3[$k][$key] === 1 ) $c = ' mr-yellow'; 
    //         if ( $f && $arr3[$k][$key] === 2 ) $c = ' mr-red'; 
            
    //         $arr[$k][$key] = '<div class="pl-3 pr-3 m-1' . $c . '">' . $item . '</div>';
        
    //     }

    //     foreach ( array( 0, 1 ) as $k ) $data[$k] = implode( "\n", $arr[$k]);

    //     return $data;
    // }






    function set_save_title( $title, $tmp_name )
    {
        $m = new mif_mr_xlsx( $tmp_name );
        $name = $m->get( $this->scheme['name'][0] );

        if ( ! empty( $name ) ) $title = $name;

        return $title;
    }





    function scheme_data_courses( $arr )
    {

        //
        //  ## @@@ ### !!!!!!!!!!
        //

        // $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
        // $arr = $m->get_arr();

        // foreach ( $arr as $k => $i )
        // foreach ( $i as $k2 => $i2 ) {

        //     if ( empty( $i2 ) ) continue;
        //     if ( preg_match( '/^\[/', $i2 ) ) {
                
        //         $i2 = trim( $i2, '[]' );
        //         p(  '$arr["' . $i2 . '"][] = "' . $k2 . $k . '";' );

        //     }
        //     // p($k);
        //     // p($k2);
    
    
        // }

        $arr["version"][] = "A0";
        $arr["name"][] = "C4";
        $arr["target"][] = "C7";
        $arr["parts_name"][] = "C9";
        $arr["parts_name"][] = "F9";
        $arr["parts_name"][] = "I9";
        $arr["parts_name"][] = "L9";
        $arr["parts_name"][] = "O9";
        $arr["parts_name"][] = "R9";
        $arr["parts_name"][] = "U9";
        $arr["parts_name"][] = "X9";
        $arr["parts_name"][] = "AA9";
        $arr["parts_name"][] = "AD9";
        $arr["parts_cmp"][] = "C10";
        $arr["parts_cmp"][] = "F10";
        $arr["parts_cmp"][] = "I10";
        $arr["parts_cmp"][] = "L10";
        $arr["parts_cmp"][] = "O10";
        $arr["parts_cmp"][] = "R10";
        $arr["parts_cmp"][] = "U10";
        $arr["parts_cmp"][] = "X10";
        $arr["parts_cmp"][] = "AA10";
        $arr["parts_cmp"][] = "AD10";
        $arr["parts_hours"][] = "C11";
        $arr["parts_hours"][] = "F11";
        $arr["parts_hours"][] = "I11";
        $arr["parts_hours"][] = "L11";
        $arr["parts_hours"][] = "O11";
        $arr["parts_hours"][] = "R11";
        $arr["parts_hours"][] = "U11";
        $arr["parts_hours"][] = "X11";
        $arr["parts_hours"][] = "AA11";
        $arr["parts_hours"][] = "AD11";
        $arr["parts_content"][] = "C12";
        $arr["parts_content"][] = "F12";
        $arr["parts_content"][] = "I12";
        $arr["parts_content"][] = "L12";
        $arr["parts_content"][] = "O12";
        $arr["parts_content"][] = "R12";
        $arr["parts_content"][] = "U12";
        $arr["parts_content"][] = "X12";
        $arr["parts_content"][] = "AA12";
        $arr["parts_content"][] = "AD12";
        $arr["parts_outcomes_z_0"][] = "C13";
        $arr["parts_outcomes_z_0"][] = "F13";
        $arr["parts_outcomes_z_0"][] = "I13";
        $arr["parts_outcomes_z_0"][] = "L13";
        $arr["parts_outcomes_z_0"][] = "O13";
        $arr["parts_outcomes_z_0"][] = "R13";
        $arr["parts_outcomes_z_0"][] = "U13";
        $arr["parts_outcomes_z_0"][] = "X13";
        $arr["parts_outcomes_z_0"][] = "AA13";
        $arr["parts_outcomes_z_0"][] = "AD13";
        $arr["parts_outcomes_u_0"][] = "C14";
        $arr["parts_outcomes_u_0"][] = "F14";
        $arr["parts_outcomes_u_0"][] = "I14";
        $arr["parts_outcomes_u_0"][] = "L14";
        $arr["parts_outcomes_u_0"][] = "O14";
        $arr["parts_outcomes_u_0"][] = "R14";
        $arr["parts_outcomes_u_0"][] = "U14";
        $arr["parts_outcomes_u_0"][] = "X14";
        $arr["parts_outcomes_u_0"][] = "AA14";
        $arr["parts_outcomes_u_0"][] = "AD14";
        $arr["parts_outcomes_v_0"][] = "C15";
        $arr["parts_outcomes_v_0"][] = "F15";
        $arr["parts_outcomes_v_0"][] = "I15";
        $arr["parts_outcomes_v_0"][] = "L15";
        $arr["parts_outcomes_v_0"][] = "O15";
        $arr["parts_outcomes_v_0"][] = "R15";
        $arr["parts_outcomes_v_0"][] = "U15";
        $arr["parts_outcomes_v_0"][] = "X15";
        $arr["parts_outcomes_v_0"][] = "AA15";
        $arr["parts_outcomes_v_0"][] = "AD15";
        $arr["parts_outcomes_z_1"][] = "C16";
        $arr["parts_outcomes_z_1"][] = "F16";
        $arr["parts_outcomes_z_1"][] = "I16";
        $arr["parts_outcomes_z_1"][] = "L16";
        $arr["parts_outcomes_z_1"][] = "O16";
        $arr["parts_outcomes_z_1"][] = "R16";
        $arr["parts_outcomes_z_1"][] = "U16";
        $arr["parts_outcomes_z_1"][] = "X16";
        $arr["parts_outcomes_z_1"][] = "AA16";
        $arr["parts_outcomes_z_1"][] = "AD16";
        $arr["parts_outcomes_u_1"][] = "C17";
        $arr["parts_outcomes_u_1"][] = "F17";
        $arr["parts_outcomes_u_1"][] = "I17";
        $arr["parts_outcomes_u_1"][] = "L17";
        $arr["parts_outcomes_u_1"][] = "O17";
        $arr["parts_outcomes_u_1"][] = "R17";
        $arr["parts_outcomes_u_1"][] = "U17";
        $arr["parts_outcomes_u_1"][] = "X17";
        $arr["parts_outcomes_u_1"][] = "AA17";
        $arr["parts_outcomes_u_1"][] = "AD17";
        $arr["parts_outcomes_v_1"][] = "C18";
        $arr["parts_outcomes_v_1"][] = "F18";
        $arr["parts_outcomes_v_1"][] = "I18";
        $arr["parts_outcomes_v_1"][] = "L18";
        $arr["parts_outcomes_v_1"][] = "O18";
        $arr["parts_outcomes_v_1"][] = "R18";
        $arr["parts_outcomes_v_1"][] = "U18";
        $arr["parts_outcomes_v_1"][] = "X18";
        $arr["parts_outcomes_v_1"][] = "AA18";
        $arr["parts_outcomes_v_1"][] = "AD18";
        $arr["evaluations_name_0"][] = "C22";
        $arr["evaluations_rating_0"][] = "D22";
        $arr["evaluations_cmp_0"][] = "E22";
        $arr["evaluations_name_0"][] = "F22";
        $arr["evaluations_rating_0"][] = "G22";
        $arr["evaluations_cmp_0"][] = "H22";
        $arr["evaluations_name_0"][] = "I22";
        $arr["evaluations_rating_0"][] = "J22";
        $arr["evaluations_cmp_0"][] = "K22";
        $arr["evaluations_name_0"][] = "L22";
        $arr["evaluations_rating_0"][] = "M22";
        $arr["evaluations_cmp_0"][] = "N22";
        $arr["evaluations_name_0"][] = "O22";
        $arr["evaluations_rating_0"][] = "P22";
        $arr["evaluations_cmp_0"][] = "Q22";
        $arr["evaluations_name_0"][] = "R22";
        $arr["evaluations_rating_0"][] = "S22";
        $arr["evaluations_cmp_0"][] = "T22";
        $arr["evaluations_name_0"][] = "U22";
        $arr["evaluations_rating_0"][] = "V22";
        $arr["evaluations_cmp_0"][] = "W22";
        $arr["evaluations_name_0"][] = "X22";
        $arr["evaluations_rating_0"][] = "Y22";
        $arr["evaluations_cmp_0"][] = "Z22";
        $arr["evaluations_name_0"][] = "AA22";
        $arr["evaluations_rating_0"][] = "AB22";
        $arr["evaluations_cmp_0"][] = "AC22";
        $arr["evaluations_name_0"][] = "AD22";
        $arr["evaluations_rating_0"][] = "AE22";
        $arr["evaluations_cmp_0"][] = "AF22";
        $arr["evaluations_name_1"][] = "C23";
        $arr["evaluations_rating_1"][] = "D23";
        $arr["evaluations_cmp_1"][] = "E23";
        $arr["evaluations_name_1"][] = "F23";
        $arr["evaluations_rating_1"][] = "G23";
        $arr["evaluations_cmp_1"][] = "H23";
        $arr["evaluations_name_1"][] = "I23";
        $arr["evaluations_rating_1"][] = "J23";
        $arr["evaluations_cmp_1"][] = "K23";
        $arr["evaluations_name_1"][] = "L23";
        $arr["evaluations_rating_1"][] = "M23";
        $arr["evaluations_cmp_1"][] = "N23";
        $arr["evaluations_name_1"][] = "O23";
        $arr["evaluations_rating_1"][] = "P23";
        $arr["evaluations_cmp_1"][] = "Q23";
        $arr["evaluations_name_1"][] = "R23";
        $arr["evaluations_rating_1"][] = "S23";
        $arr["evaluations_cmp_1"][] = "T23";
        $arr["evaluations_name_1"][] = "U23";
        $arr["evaluations_rating_1"][] = "V23";
        $arr["evaluations_cmp_1"][] = "W23";
        $arr["evaluations_name_1"][] = "X23";
        $arr["evaluations_rating_1"][] = "Y23";
        $arr["evaluations_cmp_1"][] = "Z23";
        $arr["evaluations_name_1"][] = "AA23";
        $arr["evaluations_rating_1"][] = "AB23";
        $arr["evaluations_cmp_1"][] = "AC23";
        $arr["evaluations_name_1"][] = "AD23";
        $arr["evaluations_rating_1"][] = "AE23";
        $arr["evaluations_cmp_1"][] = "AF23";
        $arr["evaluations_name_2"][] = "C24";
        $arr["evaluations_rating_2"][] = "D24";
        $arr["evaluations_cmp_2"][] = "E24";
        $arr["evaluations_name_2"][] = "F24";
        $arr["evaluations_rating_2"][] = "G24";
        $arr["evaluations_cmp_2"][] = "H24";
        $arr["evaluations_name_2"][] = "I24";
        $arr["evaluations_rating_2"][] = "J24";
        $arr["evaluations_cmp_2"][] = "K24";
        $arr["evaluations_name_2"][] = "L24";
        $arr["evaluations_rating_2"][] = "M24";
        $arr["evaluations_cmp_2"][] = "N24";
        $arr["evaluations_name_2"][] = "O24";
        $arr["evaluations_rating_2"][] = "P24";
        $arr["evaluations_cmp_2"][] = "Q24";
        $arr["evaluations_name_2"][] = "R24";
        $arr["evaluations_rating_2"][] = "S24";
        $arr["evaluations_cmp_2"][] = "T24";
        $arr["evaluations_name_2"][] = "U24";
        $arr["evaluations_rating_2"][] = "V24";
        $arr["evaluations_cmp_2"][] = "W24";
        $arr["evaluations_name_2"][] = "X24";
        $arr["evaluations_rating_2"][] = "Y24";
        $arr["evaluations_cmp_2"][] = "Z24";
        $arr["evaluations_name_2"][] = "AA24";
        $arr["evaluations_rating_2"][] = "AB24";
        $arr["evaluations_cmp_2"][] = "AC24";
        $arr["evaluations_name_2"][] = "AD24";
        $arr["evaluations_rating_2"][] = "AE24";
        $arr["evaluations_cmp_2"][] = "AF24";
        $arr["evaluations_name_3"][] = "C25";
        $arr["evaluations_rating_3"][] = "D25";
        $arr["evaluations_cmp_3"][] = "E25";
        $arr["evaluations_name_3"][] = "F25";
        $arr["evaluations_rating_3"][] = "G25";
        $arr["evaluations_cmp_3"][] = "H25";
        $arr["evaluations_name_3"][] = "I25";
        $arr["evaluations_rating_3"][] = "J25";
        $arr["evaluations_cmp_3"][] = "K25";
        $arr["evaluations_name_3"][] = "L25";
        $arr["evaluations_rating_3"][] = "M25";
        $arr["evaluations_cmp_3"][] = "N25";
        $arr["evaluations_name_3"][] = "O25";
        $arr["evaluations_rating_3"][] = "P25";
        $arr["evaluations_cmp_3"][] = "Q25";
        $arr["evaluations_name_3"][] = "R25";
        $arr["evaluations_rating_3"][] = "S25";
        $arr["evaluations_cmp_3"][] = "T25";
        $arr["evaluations_name_3"][] = "U25";
        $arr["evaluations_rating_3"][] = "V25";
        $arr["evaluations_cmp_3"][] = "W25";
        $arr["evaluations_name_3"][] = "X25";
        $arr["evaluations_rating_3"][] = "Y25";
        $arr["evaluations_cmp_3"][] = "Z25";
        $arr["evaluations_name_3"][] = "AA25";
        $arr["evaluations_rating_3"][] = "AB25";
        $arr["evaluations_cmp_3"][] = "AC25";
        $arr["evaluations_name_3"][] = "AD25";
        $arr["evaluations_rating_3"][] = "AE25";
        $arr["evaluations_cmp_3"][] = "AF25";
        $arr["evaluations_name_4"][] = "C26";
        $arr["evaluations_rating_4"][] = "D26";
        $arr["evaluations_cmp_4"][] = "E26";
        $arr["evaluations_name_4"][] = "F26";
        $arr["evaluations_rating_4"][] = "G26";
        $arr["evaluations_cmp_4"][] = "H26";
        $arr["evaluations_name_4"][] = "I26";
        $arr["evaluations_rating_4"][] = "J26";
        $arr["evaluations_cmp_4"][] = "K26";
        $arr["evaluations_name_4"][] = "L26";
        $arr["evaluations_rating_4"][] = "M26";
        $arr["evaluations_cmp_4"][] = "N26";
        $arr["evaluations_name_4"][] = "O26";
        $arr["evaluations_rating_4"][] = "P26";
        $arr["evaluations_cmp_4"][] = "Q26";
        $arr["evaluations_name_4"][] = "R26";
        $arr["evaluations_rating_4"][] = "S26";
        $arr["evaluations_cmp_4"][] = "T26";
        $arr["evaluations_name_4"][] = "U26";
        $arr["evaluations_rating_4"][] = "V26";
        $arr["evaluations_cmp_4"][] = "W26";
        $arr["evaluations_name_4"][] = "X26";
        $arr["evaluations_rating_4"][] = "Y26";
        $arr["evaluations_cmp_4"][] = "Z26";
        $arr["evaluations_name_4"][] = "AA26";
        $arr["evaluations_rating_4"][] = "AB26";
        $arr["evaluations_cmp_4"][] = "AC26";
        $arr["evaluations_name_4"][] = "AD26";
        $arr["evaluations_rating_4"][] = "AE26";
        $arr["evaluations_cmp_4"][] = "AF26";
        $arr["evaluations_name_5"][] = "C27";
        $arr["evaluations_rating_5"][] = "D27";
        $arr["evaluations_cmp_5"][] = "E27";
        $arr["evaluations_name_5"][] = "F27";
        $arr["evaluations_rating_5"][] = "G27";
        $arr["evaluations_cmp_5"][] = "H27";
        $arr["evaluations_name_5"][] = "I27";
        $arr["evaluations_rating_5"][] = "J27";
        $arr["evaluations_cmp_5"][] = "K27";
        $arr["evaluations_name_5"][] = "L27";
        $arr["evaluations_rating_5"][] = "M27";
        $arr["evaluations_cmp_5"][] = "N27";
        $arr["evaluations_name_5"][] = "O27";
        $arr["evaluations_rating_5"][] = "P27";
        $arr["evaluations_cmp_5"][] = "Q27";
        $arr["evaluations_name_5"][] = "R27";
        $arr["evaluations_rating_5"][] = "S27";
        $arr["evaluations_cmp_5"][] = "T27";
        $arr["evaluations_name_5"][] = "U27";
        $arr["evaluations_rating_5"][] = "V27";
        $arr["evaluations_cmp_5"][] = "W27";
        $arr["evaluations_name_5"][] = "X27";
        $arr["evaluations_rating_5"][] = "Y27";
        $arr["evaluations_cmp_5"][] = "Z27";
        $arr["evaluations_name_5"][] = "AA27";
        $arr["evaluations_rating_5"][] = "AB27";
        $arr["evaluations_cmp_5"][] = "AC27";
        $arr["evaluations_name_5"][] = "AD27";
        $arr["evaluations_rating_5"][] = "AE27";
        $arr["evaluations_cmp_5"][] = "AF27";
        $arr["evaluations_name_6"][] = "C28";
        $arr["evaluations_rating_6"][] = "D28";
        $arr["evaluations_cmp_6"][] = "E28";
        $arr["evaluations_name_6"][] = "F28";
        $arr["evaluations_rating_6"][] = "G28";
        $arr["evaluations_cmp_6"][] = "H28";
        $arr["evaluations_name_6"][] = "I28";
        $arr["evaluations_rating_6"][] = "J28";
        $arr["evaluations_cmp_6"][] = "K28";
        $arr["evaluations_name_6"][] = "L28";
        $arr["evaluations_rating_6"][] = "M28";
        $arr["evaluations_cmp_6"][] = "N28";
        $arr["evaluations_name_6"][] = "O28";
        $arr["evaluations_rating_6"][] = "P28";
        $arr["evaluations_cmp_6"][] = "Q28";
        $arr["evaluations_name_6"][] = "R28";
        $arr["evaluations_rating_6"][] = "S28";
        $arr["evaluations_cmp_6"][] = "T28";
        $arr["evaluations_name_6"][] = "U28";
        $arr["evaluations_rating_6"][] = "V28";
        $arr["evaluations_cmp_6"][] = "W28";
        $arr["evaluations_name_6"][] = "X28";
        $arr["evaluations_rating_6"][] = "Y28";
        $arr["evaluations_cmp_6"][] = "Z28";
        $arr["evaluations_name_6"][] = "AA28";
        $arr["evaluations_rating_6"][] = "AB28";
        $arr["evaluations_cmp_6"][] = "AC28";
        $arr["evaluations_name_6"][] = "AD28";
        $arr["evaluations_rating_6"][] = "AE28";
        $arr["evaluations_cmp_6"][] = "AF28";
        $arr["authors"][] = "C31";
        $arr["authors"][] = "C32";
        $arr["authors"][] = "C33";
        $arr["authors"][] = "C34";
        $arr["authors"][] = "C35";
        $arr["biblio_basic"][] = "C41";
        $arr["biblio_basic"][] = "C42";
        $arr["biblio_basic"][] = "C43";
        $arr["biblio_basic"][] = "C44";
        $arr["biblio_basic"][] = "C45";
        $arr["biblio_additional"][] = "C47";
        $arr["biblio_additional"][] = "C48";
        $arr["biblio_additional"][] = "C49";
        $arr["biblio_additional"][] = "C50";
        $arr["biblio_additional"][] = "C51";
        $arr["biblio_additional"][] = "C52";
        $arr["biblio_additional"][] = "C53";
        $arr["biblio_additional"][] = "C54";
        $arr["biblio_additional"][] = "C55";
        $arr["biblio_additional"][] = "C56";
        $arr["it_inet"][] = "C60";
        $arr["it_inet"][] = "C61";
        $arr["it_inet"][] = "C62";
        $arr["it_inet"][] = "C63";
        $arr["it_inet"][] = "C64";
        $arr["it_app"][] = "C66";
        $arr["it_app"][] = "C67";
        $arr["it_app"][] = "C68";
        $arr["it_app"][] = "C69";
        $arr["it_app"][] = "C70";
        $arr["mto"][] = "C73";
        $arr["mto"][] = "C74";
        $arr["mto"][] = "C75";
        $arr["mto"][] = "C76";
        $arr["mto"][] = "C77";

        return $arr;
    }


    protected $scheme = array();
    // protected $index_part = array();

}

?>