<?php

//
// Экспорт данных учебных дисциплин (статистика, анализ)
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_info extends mif_mr_tools_core {
    
    function __construct()
    {
        parent::__construct();

        add_filter( 'lib-upload-save-title', array( $this, 'set_save_title'), 10, 2 );
        // add_filter( 'scheme-data-courses', array( $this, 'scheme_data_courses'), 10 );

        $this->scheme = apply_filters( 'scheme-data-courses', array() );
        
        $this->index = apply_filters( 'index-tools-courses', array(
                array( 'title' => 'Цели', 'key' => 'target', 'key2' => NULL, 'return' => ' mr-yellow' ),
                array( 'title' => 'Разделы (содержание)', 'key' => 'parts', 'key2' => 'content', 'return' => ' mr-yellow' ),
                array( 'title' => 'Разделы (компетенции)', 'key' => 'parts', 'key2' => 'cmp', 'return' => ' mr-yellow' ),
                array( 'title' => 'Разделы (трудоемкость)', 'key' => 'parts', 'key2' => 'hours', 'return' => ' mr-yellow' ),
                array( 'title' => 'Разделы (знать, уметь, владеть)', 'key' => 'parts', 'key2' => 'outcomes', 'return' => ' mr-yellow' ),
                array( 'title' => 'Оценочные средства', 'key' => 'evaluations', 'key2' => NULL, 'return' => ' mr-yellow' ),
                array( 'title' => 'Основная литература', 'key' => 'biblio', 'key2' => 'basic', 'return' => ' mr-yellow' ),
                array( 'title' => 'Дополнительная литература', 'key' => 'biblio', 'key2' => 'additional', 'return' => ' mr-yellow' ),
                array( 'title' => 'Ресурсы Интернета', 'key' => 'it', 'key2' => 'inet', 'return' => ' mr-yellow' ),
                array( 'title' => 'Программное обеспечение', 'key' => 'it', 'key2' => 'app', 'return' => ' mr-yellow' ),
                array( 'title' => 'Материально-техническое обеспечение', 'key' => 'mto', 'key2' => 'mto', 'return' => ' mr-yellow' ),
                array( 'title' => 'Разработчики', 'key' => 'authors', 'key2' => 'authors', 'return' => ' mr-yellow' ),
            ) );

        $this->description = apply_filters( 'description-tools-courses', array(
                'curriculum-yes' => 'Входит в учебный план',
                'curriculum-no'=> 'Не включена в учебный план', 
                'local-no' => 'Локальный контента нет', 
                'local-yes' => 'Есть локальный контент',
                'local-maybe' => 'Есть локальный контент, но он отличается',
                'lib-no' => 'В библиотеке нет контента',
                'lib-yes' => 'Есть в библиотеке контент',
                'lib-maybe' => 'Есть в библиотеке контент, но он отличается', 
            ) );

    }
        



    // 
    // Вывести статистику  
    // 
    
    protected function get_info_courses( $att_id )
    {
        global $tree;
    
        $arr = array();

        $m = new mif_mr_xlsx_tpl( get_attached_file( $att_id ) );
        $name = $m->get_name_course();
        // $name = $m->get( $this->scheme['name'][0] );

        $arr['title'] = $name;    
        $arr['is_course'] = ( ! empty( $name ) ) ? true : false; // !!!!
        $arr['is_curriculum'] = ( ! empty( $tree['content']['courses']['index'][$name]) ) ? true : false;
     
        $f_local = false;
        $f_lib = false;

        foreach ( $tree['content']['lib-courses']['data'] as $i ) {
            if ( $i['name'] != $name ) continue;
            if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) $f_local = true; else $f_lib = true;
        } 
        
        $arr['is_content_local'] = $f_local;    
        $arr['is_content_lib'] = $f_lib;    
        

        $arr['id_local'] = NULL;    
        $arr['id_libs'] = array();  
        
        $arr['percent_local'] = 0;
        $arr['percent_libs'] = array(); 
        $arr['percent_lib_max'] = 0;    
        
        foreach ( $tree['content']['lib-courses']['data'] as $i ) {
            
            if ( $i['name'] == $name ) {
            
                $p = $this->get_stats_courses( 
                        // $this->get_courses_form_xls( $att_id ), 
                        $m->xlsx_to_arr(), 
                        $tree['content']['lib-courses']['data'][$i['comp_id']]['data'] );

                if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) {

                    $arr['id_local'] = $i['comp_id']; 
                    $arr['percent_local'] = $p; 
                    
                } else {
                    
                    $arr['id_libs'][] = $i['comp_id'];
                    $arr['percent_libs'][] = $p; 

                    if ( $p > $arr['percent_lib_max'] ) $arr['percent_lib_max'] = $p;
            
                }

            }

        } 
        
        return $arr;
    }
    




    // 
    // Вывести панель
    // 

    public function show_file_courses( $att_id, $courses_id = NULL )
    {
        // Course 1
        
        $att = get_post( $att_id );
        
        if ( empty( $att ) ) return;
        if ( $att->post_type != 'attachment' ) return;
        if ( ! in_array( mif_mr_functions::get_ext( $att->guid ), array( 'xls', 'xlsx' ) ) ) return;
        
        // $arr = $this->get_courses_form_xls( $att_id );
        $m = new mif_mr_xlsx_tpl( get_attached_file( $att_id ) );
        $arr = $m->xlsx_to_arr();
        
        // Course 2

        global $tree;
        $arr2 = array();
        if ( isset( $tree['content']['lib-courses']['data'][$courses_id]['data'] ) ) $arr2 = $tree['content']['lib-courses']['data'][$courses_id]['data'];

        $from_id = ( $tree['content']['lib-courses']['data'][$courses_id]['from_id'] ) ? $tree['content']['lib-courses']['data'][$courses_id]['from_id'] : NULL;


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


        $out = '';
        
        $out .= '<div class="container show-file" data-attid="' . $att_id . '">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col d-none d-sm-block mt-3 mb-3 pr-0 text-end">';
        $out .= '<a href="#" class="text-secondary" id="fullsize">';
        $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
        $out .= '</a>';
        $out .= '</div>';
        $out .= '</div>';
        
        if ( ! empty( $from_id ) ) {

            $out .= '<div class="row">';
            $out .= '<div class="col fw-semibold p-3">';
            $out .= 'Дисциплина ' . mif_mr_opop_core::get_span_id( $courses_id );
            $out .= ( $from_id == mif_mr_opop_core::get_opop_id() ) ? ' из текущей ОПОП' : 
                ' из ОПОП: <a href="' .  get_permalink( $from_id ) . 'lib-courses/' . $courses_id . '">'
                 . mif_mr_functions::mb_substr( get_the_title( $from_id ), 40 ) . '</a>';
    
            $out .= '</div>';
            $out .= '</div>';

        }    

        $out .= '<div class="row">';
        $out .= '<div class="col fw-semibold bg-light p-3 text-center">' . $arr['name'] . '</div>';
        $out .= '</div>';

        $out .= '<div class="row">';
        $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из файла</div>';
        $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из Matrix</div>';
        $out .= '</div>';
        

        foreach ( $this->index as $i ) {

            $out .= '<div class="row">';
            $out .= '<div class="col fw-semibold bg-light p-2 text-center">' . $i['title'] . '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, $i['key'], $i['key2'], ' mr-yellow' ) . '">';
            $out .= $this->get_course_part( $arr, $i['key'], $i['key2'] );
            $out .= $this->get_course_part( $arr2, $i['key'], $i['key2'] );
            $out .= '</div>';

        }

        $out .= '</div>';
    
        return $out;
    }
    




    //
    //
    //
    
    private function get_diff_courses( $arr, $arr2, $key, $key2, $return = NULL )
    {
        $yes = true;  
        $no = false;  
        
        if ( $return !== NULL ) {
            
            $yes = $return;  
            $no = '';  

        }
    
        $s = $this->get_course_part( $arr, $key, $key2 );
        $s2 = $this->get_course_part( $arr2, $key, $key2 );

        if ( $s == $s2 ) $res = $no; else $res = $yes;

        return $res;
    }




    //
    //
    //
    
    private function get_stats_courses( $arr, $arr2 )
    {
        $p = 0;
        $n = 0;

        foreach ( $this->index as $i ) {

            if ( ! $this->get_diff_courses( $arr, $arr2, $i['key'], $i['key2'] ) ) $p++; 
            $n++;            
        
        }

        return round( $p / $n * 100, 0 );
    }




    //
    //
    //      
    
    private function get_course_part( $arr, $key, $key2 = NULL )
    {
        
        // Пропускаем
    
        switch ( $key ) {
                    
            case 'target':
            case 'parts':
    
                if ( empty( $arr['content'][$key] ) ) return;
            
            break;
                
            case 'evaluations':
    
                if ( empty( $arr[$key] ) ) return;

            break;
            
            case 'biblio':
            case 'it':
            case 'mto':
            case 'authors':

                if ( empty( $arr[$key][$key2]) ) return;
            
            break;
                    
            // default:
            // break;
        
        }


        // Собираем

        $out = '';    
        $out .= '<div class="col col-6 mt-3 mb-3">';

        switch ( $key ) {
                    
            case 'target':

                $out .= '<div class="mb-3">' . $arr['content']['target'] . '</div>';

            break;
            
            case 'parts':

                foreach ( $arr['content']['parts'] as $i ) {
                    
                    if ( preg_match( '/.*\)$/', $i['name'] ) ) $i['name'] .= '.'; // !!!!
                
                    $out .= '<div class="mb-3 fw-bold">= ' . $i['name'] . '</div>';
                    if ( $key2 == 'content' ) $out .= '<div class="mb-3">' . $i['content'] . '</div>';
                    if ( $key2 == 'cmp' ) $out .= '<div class="mb-3">' . $i['cmp'] . '</div>';
                    
                    if ( $key2 == 'hours' ) {

                        $out .= '<div class="mb-3">' . $i['hours_raw'] . '</div>';

                    }
                    
                    if ( $key2 == 'outcomes' ) {
                        
                        $ii = array_diff( (array) $i['outcomes']['z'], array( '' ) );
                        $out .= '<div class="mb-3"><i>знать:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                        $ii = array_diff( (array) $i['outcomes']['u'], array( '' ) );
                        $out .= '<div class="mb-3"><i>уметь:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                        $ii = array_diff( (array) $i['outcomes']['v'], array( '' ) );
                        $out .= '<div class="mb-3"><i>владеть:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                    
                    }

                }
            
            break;
            
            case 'evaluations':
                
                foreach ( $arr['evaluations'] as $k => $d ) {
                
                    if ( $k != 0 ) $out .= '<br />';

                    foreach ( $d['data'] as $i ) {

                        $out .= '<div>' . $i['name'];
                        if ( preg_match( '/.*\)$/', $i['name'] ) ) $out .= '.'; // !!!!
                        $out .= ' (' . $i['att']['rating'] . ') (' . $i['att']['cmp'] . ')' . '</div>';

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
    
    
    
    
    // // 
    // //   
    // // 
    
    // public function get_courses_form_xls( $att_id )
    // {

    //     $m = new mif_mr_xlsx_tpl( get_attached_file( $att_id ) );
    //     $arr = array();

    //     // Название, Цель

    //     $arr['name'] = $m->get( $this->scheme['name'][0] );
    //     $arr['content']['target'] = $m->get( $this->scheme['target'][0] );

    //     // Разделы

    //     for ( $i = 0; $i < 10; $i++ ) { 

    //         if ( empty( $m->get( $this->scheme['parts_name'][$i] ) ) ) continue;

    //         $arr['content']['parts'][$i]['name'] = $m->get( $this->scheme['parts_name'][$i] );
    //         $arr['content']['parts'][$i]['content'] = $m->get( $this->scheme['parts_content'][$i] );
    //         $arr['content']['parts'][$i] ['cmp'] = $m->get( $this->scheme['parts_cmp'][$i] );
    //         $arr['content']['parts'][$i] ['hours_raw'] = $m->get( $this->scheme['parts_hours'][$i] );
    //         $arr['content']['parts'][$i] ['hours'] = content::get_hours( $m->get( $this->scheme['parts_hours'][$i] ) );
            
    //         for ( $j = 0; $j < 10; $j++ ) { 
                
    //             if ( isset( $this->scheme['parts_outcomes_z_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['z'][] = $m->get( $this->scheme['parts_outcomes_z_'.$j][$i] );
    //             if ( isset( $this->scheme['parts_outcomes_u_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['u'][] = $m->get( $this->scheme['parts_outcomes_u_'.$j][$i] );
    //             if ( isset( $this->scheme['parts_outcomes_v_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['v'][] = $m->get( $this->scheme['parts_outcomes_v_'.$j][$i] );

    //         }
            
    //     }

    //     // Оценочные средства
            
    //     for ( $i = 0; $i < 10; $i++ ) {

    //         for ( $j = 0; $j < 10; $j++ ) { 

    //             if ( empty( $this->scheme['evaluations_name_'.$i][$j] ) ) continue;
    //             if ( empty( $m->get( $this->scheme['evaluations_name_'.$i][$j] ) ) ) continue;
                
    //             $arr['evaluations'][$j]['sem'] = $j;
    //             if ( isset( $this->scheme['evaluations_name_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['name'] = $m->get( $this->scheme['evaluations_name_'.$i][$j] );
    //             if ( isset( $this->scheme['evaluations_rating_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['rating'] = $m->get( $this->scheme['evaluations_rating_'.$i][$j] );
    //             if ( isset( $this->scheme['evaluations_cmp_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['cmp'] = $m->get( $this->scheme['evaluations_cmp_'.$i][$j] );
    
    //         }

    //     } 

    //     // Литература

    //     for ( $i = 0; $i < 10; $i++ ) {

    //         if ( empty( $this->scheme['biblio_basic'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['biblio_basic'][$i] ) ) ) continue;
            
    //         $arr['biblio']['basic'][] = $m->get( $this->scheme['biblio_basic'][$i] );

    //     } 

    //     for ( $i = 0; $i < 10; $i++ ) {
            
    //         if ( empty( $this->scheme['biblio_additional'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['biblio_additional'][$i] ) ) ) continue;
            
    //         $arr['biblio']['additional'][] = $m->get( $this->scheme['biblio_additional'][$i] );
        
    //     } 
        
    //     // Информационные технологии
        
    //     for ( $i = 0; $i < 10; $i++ ) {

    //         if ( empty( $this->scheme['it_inet'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['it_inet'][$i] ) ) ) continue;
            
    //         $arr['it']['inet'][] = $m->get( $this->scheme['it_inet'][$i] );

    //     } 
        
    //     for ( $i = 0; $i < 10; $i++ ) {

    //         if ( empty( $this->scheme['it_app'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['it_app'][$i] ) ) ) continue;
            
    //         $arr['it']['app'][] = $m->get( $this->scheme['it_app'][$i] );

    //     } 
    
    //     // Материально-техническое обеспечение

    //     for ( $i = 0; $i < 10; $i++ ) {

    //         if ( empty( $this->scheme['mto'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['mto'][$i] ) ) ) continue;
            
    //         $arr['mto']['mto'][] = $m->get( $this->scheme['mto'][$i] );

    //     } 
    
    //     // Разработчики

    //     for ( $i = 0; $i < 10; $i++ ) {

    //         if ( empty( $this->scheme['authors'][$i] ) ) continue;
    //         if ( empty( $m->get( $this->scheme['authors'][$i] ) ) ) continue;
            
    //         $arr['authors']['authors'][] = $m->get( $this->scheme['authors'][$i] );

    //     } 
    

    //     //
    //     // guidelines ???? !!!!
    //     //

    //     return $arr;
    // }


        
    
    //
    // Анализ
    // 
    
    public function analysis( $att = array() )
    {
        if ( empty( $att['att_id'] ) ) return;

        $a = $this->get_info_courses( $att['att_id'] );
        
        $arr_info['title'] = ( $a['is_course'] ) ? $a['title'] : 'Дисциплина не обнаружена';
        $arr_info['is_curriculum'] = ( $a['is_curriculum'] ) ? $this->description['curriculum-yes'] : $this->description['curriculum-no'];
        $arr_info['is_content_local'] = ( $a['is_content_local'] ) ? $this->description['local-yes'] : $this->description['local-no'];    
        $arr_info['id_local'] = ( $a['is_content_local'] ) ? ': ' . $this->get_link_local( $a ) . '': '';    
        $arr_info['is_content_lib'] = ( $a['is_content_lib'] ) ? $this->description['lib-yes'] : $this->description['lib-no'];    
        $arr_info['id_libs'] = ( $a['is_content_lib'] ) ? ': ' . $this->get_link_lib( $a ) . '' : '';    

        $comment = '';
        
        if ( ! $a['is_curriculum'] ) $comment .= 'curriculum-no';

        if ( $a['is_content_local'] && $a['percent_local'] == 100 ) $comment .= 'local-yes, 100';
        elseif ( $a['is_content_local'] && $a['percent_local'] < 100 ) $comment .= 'local-no, < 99';
        elseif ( $a['is_content_lib'] && $a['percent_lib_max'] == 100 ) $comment .= 'lib-yes, 100';
        elseif ( $a['is_content_lib'] && $a['percent_lib_max'] < 100 ) $comment .= 'lib-yes, < 99';
        elseif ( ! $a['is_content_local'] ) $comment .= 'local-no';
        
        if ( ! empty( $a['id_local'] ) ) {

            $course_id = $a['id_local'];
            
        } elseif ( ! empty( $a['id_libs'][0] ) ) {
            
            $course_id = $a['id_libs'][0];
            
        } else {
            
            $course_id = NULL;

        }

        if ( ! empty( $att['course_id'] ) ) $course_id = $att['course_id'];

        $out = '';

        if ( $att['course_id'] == 0 ) $out .= '<div class="row analysis-box" style="display:none;">';
      
        $out .= '<div class="col p-3">';
        
        $out .= '<div class="bg-light border p-3 rounded">';
        $out .= '<div>' . $arr_info['is_curriculum'] . '</div>';
        $out .= '<div>' . $arr_info['is_content_local'] . $arr_info['id_local'] . '</div>';
        $out .= '<div>' . $arr_info['is_content_lib'] . $arr_info['id_libs'] . '</div>';
        $out .= '</div>';
        
        $out .= $this->show_file_courses( $att['att_id'], $course_id );

        $out .= '<div class="bg-light p-4 pt-5 pb-5">';

        $out .= '<a href="#" class="mr-3 export-panel"><i class="fa-regular fa-floppy-disk fa-lg"></i></a>';
        $out .= '<a href="#" class="mr-3 remove-panel"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
        $out .= '<a href="#" class="mr-3 cancel-panel"><i class="fa-solid fa-angle-up"></i></a>';
        $out .= '<div  class="mt-3">' . $comment . '</div>';

        $out .= '</div>';

        $out .= '</div>';
        
        if ( $att['course_id'] == 0 ) $out .= '</div>';

        return $out;
    }




    private function get_link_local( $a )
    {
        return  '<a href="#" class="info-clarifications" data-id="' . $a['id_local'] . '">' . $a['percent_local'] . '%</a> (' . $a['id_local'] . ')';
    }




    private function get_link_lib( $a )
    {
        $b = array();
        foreach ( $a['id_libs'] as $k => $i ) $b[] = '<a href="#" class="info-clarifications" data-id="' . $i . '">' . $a['percent_libs'][$k] . '%</a> (' . $i . ')';
        return implode( ', ', $b );
    }




    //
    // Для имени файла
    //

    function set_save_title( $title, $tmp_name )
    {
        $m = new mif_mr_xlsx_tpl( $tmp_name );
        $name = $m->get_name_course();
        // $name = $m->get( $this->scheme['name'][0] );
        
        if ( ! empty( $name ) ) $title = $name;
        
        return $title;
    }
        

    // protected $scheme = array();
    protected $index = array();
    protected $description = array();

}

?>