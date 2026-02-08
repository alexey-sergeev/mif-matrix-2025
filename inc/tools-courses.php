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
        
        p($this->scheme);

        // $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
        // $c = $m->get( $this->scheme['name'][0] );

        
        //
        //  ## @@@ ### !!!!!!!!!!
        //
        // $arr = $m->get_arr();
        //
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
        
        $out .= '<div class="container show-file" data-attid="' . $att_id . '">';


        $out .= '</div>';

        $out .= '<input type="hidden" name="attid" value="' . $att_id . '" />'; 
        
        $out .= '';
        
        return $out;
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

        $arr["name"][] = "C4";
        $arr["target"][] = "C7";
        $arr["parts_name_0"][] = "C9";
        $arr["parts_name_1"][] = "F9";
        $arr["parts_name_2"][] = "I9";
        $arr["parts_name_3"][] = "L9";
        $arr["parts_name_4"][] = "O9";
        $arr["parts_name_5"][] = "R9";
        $arr["parts_name_6"][] = "U9";
        $arr["parts_name_7"][] = "X9";
        $arr["parts_name_8"][] = "AA9";
        $arr["parts_name_9"][] = "AD9";
        $arr["parts_cmp_0"][] = "C10";
        $arr["parts_cmp_1"][] = "F10";
        $arr["parts_cmp_2"][] = "I10";
        $arr["parts_cmp_3"][] = "L10";
        $arr["parts_cmp_4"][] = "O10";
        $arr["parts_cmp_5"][] = "R10";
        $arr["parts_cmp_6"][] = "U10";
        $arr["parts_cmp_7"][] = "X10";
        $arr["parts_cmp_8"][] = "AA10";
        $arr["parts_cmp_9"][] = "AD10";
        $arr["parts_hours_0"][] = "C11";
        $arr["parts_hours_1"][] = "F11";
        $arr["parts_hours_2"][] = "I11";
        $arr["parts_hours_3"][] = "L11";
        $arr["parts_hours_4"][] = "O11";
        $arr["parts_hours_5"][] = "R11";
        $arr["parts_hours_6"][] = "U11";
        $arr["parts_hours_7"][] = "X11";
        $arr["parts_hours_8"][] = "AA11";
        $arr["parts_hours_9"][] = "AD11";
        $arr["parts_content_0"][] = "C12";
        $arr["parts_content_1"][] = "F12";
        $arr["parts_content_2"][] = "I12";
        $arr["parts_content_3"][] = "L12";
        $arr["parts_content_4"][] = "O12";
        $arr["parts_content_5"][] = "R12";
        $arr["parts_content_6"][] = "U12";
        $arr["parts_content_7"][] = "X12";
        $arr["parts_content_8"][] = "AA12";
        $arr["parts_content_9"][] = "AD12";
        $arr["parts_outcomes_z_0"][] = "C13";
        $arr["parts_outcomes_z_1"][] = "F13";
        $arr["parts_outcomes_z_2"][] = "I13";
        $arr["parts_outcomes_z_3"][] = "L13";
        $arr["parts_outcomes_z_4"][] = "O13";
        $arr["parts_outcomes_z_5"][] = "R13";
        $arr["parts_outcomes_z_6"][] = "U13";
        $arr["parts_outcomes_z_7"][] = "X13";
        $arr["parts_outcomes_z_8"][] = "AA13";
        $arr["parts_outcomes_z_9"][] = "AD13";
        $arr["parts_outcomes_u_0"][] = "C14";
        $arr["parts_outcomes_u_1"][] = "F14";
        $arr["parts_outcomes_u_2"][] = "I14";
        $arr["parts_outcomes_u_3"][] = "L14";
        $arr["parts_outcomes_u_4"][] = "O14";
        $arr["parts_outcomes_u_5"][] = "R14";
        $arr["parts_outcomes_u_6"][] = "U14";
        $arr["parts_outcomes_u_7"][] = "X14";
        $arr["parts_outcomes_u_8"][] = "AA14";
        $arr["parts_outcomes_u_9"][] = "AD14";
        $arr["parts_outcomes_v_0"][] = "C15";
        $arr["parts_outcomes_v_1"][] = "F15";
        $arr["parts_outcomes_v_2"][] = "I15";
        $arr["parts_outcomes_v_3"][] = "L15";
        $arr["parts_outcomes_v_4"][] = "O15";
        $arr["parts_outcomes_v_5"][] = "R15";
        $arr["parts_outcomes_v_6"][] = "U15";
        $arr["parts_outcomes_v_7"][] = "X15";
        $arr["parts_outcomes_v_8"][] = "AA15";
        $arr["parts_outcomes_v_9"][] = "AD15";
        $arr["parts_outcomes_z_0"][] = "C16";
        $arr["parts_outcomes_z_1"][] = "F16";
        $arr["parts_outcomes_z_2"][] = "I16";
        $arr["parts_outcomes_z_3"][] = "L16";
        $arr["parts_outcomes_z_4"][] = "O16";
        $arr["parts_outcomes_z_5"][] = "R16";
        $arr["parts_outcomes_z_6"][] = "U16";
        $arr["parts_outcomes_z_7"][] = "X16";
        $arr["parts_outcomes_z_8"][] = "AA16";
        $arr["parts_outcomes_z_9"][] = "AD16";
        $arr["parts_outcomes_u_0"][] = "C17";
        $arr["parts_outcomes_u_1"][] = "F17";
        $arr["parts_outcomes_u_2"][] = "I17";
        $arr["parts_outcomes_u_3"][] = "L17";
        $arr["parts_outcomes_u_4"][] = "O17";
        $arr["parts_outcomes_u_5"][] = "R17";
        $arr["parts_outcomes_u_6"][] = "U17";
        $arr["parts_outcomes_u_7"][] = "X17";
        $arr["parts_outcomes_u_8"][] = "AA17";
        $arr["parts_outcomes_u_9"][] = "AD17";
        $arr["parts_outcomes_v_0"][] = "C18";
        $arr["parts_outcomes_v_1"][] = "F18";
        $arr["parts_outcomes_v_2"][] = "I18";
        $arr["parts_outcomes_v_3"][] = "L18";
        $arr["parts_outcomes_v_4"][] = "O18";
        $arr["parts_outcomes_v_5"][] = "R18";
        $arr["parts_outcomes_v_6"][] = "U18";
        $arr["parts_outcomes_v_7"][] = "X18";
        $arr["parts_outcomes_v_8"][] = "AA18";
        $arr["parts_outcomes_v_9"][] = "AD18";
        $arr["evaluations_name_0"][] = "C22";
        $arr["evaluations_rating_0"][] = "D22";
        $arr["evaluations_cmp_0"][] = "E22";
        $arr["evaluations_name_1"][] = "F22";
        $arr["evaluations_rating_1"][] = "G22";
        $arr["evaluations_cmp_1"][] = "H22";
        $arr["evaluations_name_2"][] = "I22";
        $arr["evaluations_rating_2"][] = "J22";
        $arr["evaluations_cmp_2"][] = "K22";
        $arr["evaluations_name_3"][] = "L22";
        $arr["evaluations_rating_3"][] = "M22";
        $arr["evaluations_cmp_3"][] = "N22";
        $arr["evaluations_name_4"][] = "O22";
        $arr["evaluations_rating_4"][] = "P22";
        $arr["evaluations_cmp_4"][] = "Q22";
        $arr["evaluations_name_5"][] = "R22";
        $arr["evaluations_rating_5"][] = "S22";
        $arr["evaluations_cmp_5"][] = "T22";
        $arr["evaluations_name_6"][] = "U22";
        $arr["evaluations_rating_6"][] = "V22";
        $arr["evaluations_cmp_6"][] = "W22";
        $arr["evaluations_name_7"][] = "X22";
        $arr["evaluations_rating_7"][] = "Y22";
        $arr["evaluations_cmp_7"][] = "Z22";
        $arr["evaluations_name_8"][] = "AA22";
        $arr["evaluations_rating_8"][] = "AB22";
        $arr["evaluations_cmp_8"][] = "AC22";
        $arr["evaluations_name_9"][] = "AD22";
        $arr["evaluations_rating_9"][] = "AE22";
        $arr["evaluations_cmp_9"][] = "AF22";
        $arr["evaluations_name_0"][] = "C23";
        $arr["evaluations_rating_0"][] = "D23";
        $arr["evaluations_cmp_0"][] = "E23";
        $arr["evaluations_name_1"][] = "F23";
        $arr["evaluations_rating_1"][] = "G23";
        $arr["evaluations_cmp_1"][] = "H23";
        $arr["evaluations_name_2"][] = "I23";
        $arr["evaluations_rating_2"][] = "J23";
        $arr["evaluations_cmp_2"][] = "K23";
        $arr["evaluations_name_3"][] = "L23";
        $arr["evaluations_rating_3"][] = "M23";
        $arr["evaluations_cmp_3"][] = "N23";
        $arr["evaluations_name_4"][] = "O23";
        $arr["evaluations_rating_4"][] = "P23";
        $arr["evaluations_cmp_4"][] = "Q23";
        $arr["evaluations_name_5"][] = "R23";
        $arr["evaluations_rating_5"][] = "S23";
        $arr["evaluations_cmp_5"][] = "T23";
        $arr["evaluations_name_6"][] = "U23";
        $arr["evaluations_rating_6"][] = "V23";
        $arr["evaluations_cmp_6"][] = "W23";
        $arr["evaluations_name_7"][] = "X23";
        $arr["evaluations_rating_7"][] = "Y23";
        $arr["evaluations_cmp_7"][] = "Z23";
        $arr["evaluations_name_8"][] = "AA23";
        $arr["evaluations_rating_8"][] = "AB23";
        $arr["evaluations_cmp_8"][] = "AC23";
        $arr["evaluations_name_9"][] = "AD23";
        $arr["evaluations_rating_9"][] = "AE23";
        $arr["evaluations_cmp_9"][] = "AF23";
        $arr["evaluations_name_0"][] = "C24";
        $arr["evaluations_rating_0"][] = "D24";
        $arr["evaluations_cmp_0"][] = "E24";
        $arr["evaluations_name_1"][] = "F24";
        $arr["evaluations_rating_1"][] = "G24";
        $arr["evaluations_cmp_1"][] = "H24";
        $arr["evaluations_name_2"][] = "I24";
        $arr["evaluations_rating_2"][] = "J24";
        $arr["evaluations_cmp_2"][] = "K24";
        $arr["evaluations_name_3"][] = "L24";
        $arr["evaluations_rating_3"][] = "M24";
        $arr["evaluations_cmp_3"][] = "N24";
        $arr["evaluations_name_4"][] = "O24";
        $arr["evaluations_rating_4"][] = "P24";
        $arr["evaluations_cmp_4"][] = "Q24";
        $arr["evaluations_name_5"][] = "R24";
        $arr["evaluations_rating_5"][] = "S24";
        $arr["evaluations_cmp_5"][] = "T24";
        $arr["evaluations_name_6"][] = "U24";
        $arr["evaluations_rating_6"][] = "V24";
        $arr["evaluations_cmp_6"][] = "W24";
        $arr["evaluations_name_7"][] = "X24";
        $arr["evaluations_rating_7"][] = "Y24";
        $arr["evaluations_cmp_7"][] = "Z24";
        $arr["evaluations_name_8"][] = "AA24";
        $arr["evaluations_rating_8"][] = "AB24";
        $arr["evaluations_cmp_8"][] = "AC24";
        $arr["evaluations_name_9"][] = "AD24";
        $arr["evaluations_rating_9"][] = "AE24";
        $arr["evaluations_cmp_9"][] = "AF24";
        $arr["evaluations_name_0"][] = "C25";
        $arr["evaluations_rating_0"][] = "D25";
        $arr["evaluations_cmp_0"][] = "E25";
        $arr["evaluations_name_1"][] = "F25";
        $arr["evaluations_rating_1"][] = "G25";
        $arr["evaluations_cmp_1"][] = "H25";
        $arr["evaluations_name_2"][] = "I25";
        $arr["evaluations_rating_2"][] = "J25";
        $arr["evaluations_cmp_2"][] = "K25";
        $arr["evaluations_name_3"][] = "L25";
        $arr["evaluations_rating_3"][] = "M25";
        $arr["evaluations_cmp_3"][] = "N25";
        $arr["evaluations_name_4"][] = "O25";
        $arr["evaluations_rating_4"][] = "P25";
        $arr["evaluations_cmp_4"][] = "Q25";
        $arr["evaluations_name_5"][] = "R25";
        $arr["evaluations_rating_5"][] = "S25";
        $arr["evaluations_cmp_5"][] = "T25";
        $arr["evaluations_name_6"][] = "U25";
        $arr["evaluations_rating_6"][] = "V25";
        $arr["evaluations_cmp_6"][] = "W25";
        $arr["evaluations_name_7"][] = "X25";
        $arr["evaluations_rating_7"][] = "Y25";
        $arr["evaluations_cmp_7"][] = "Z25";
        $arr["evaluations_name_8"][] = "AA25";
        $arr["evaluations_rating_8"][] = "AB25";
        $arr["evaluations_cmp_8"][] = "AC25";
        $arr["evaluations_name_9"][] = "AD25";
        $arr["evaluations_rating_9"][] = "AE25";
        $arr["evaluations_cmp_9"][] = "AF25";
        $arr["evaluations_name_0"][] = "C26";
        $arr["evaluations_rating_0"][] = "D26";
        $arr["evaluations_cmp_0"][] = "E26";
        $arr["evaluations_name_1"][] = "F26";
        $arr["evaluations_rating_1"][] = "G26";
        $arr["evaluations_cmp_1"][] = "H26";
        $arr["evaluations_name_2"][] = "I26";
        $arr["evaluations_rating_2"][] = "J26";
        $arr["evaluations_cmp_2"][] = "K26";
        $arr["evaluations_name_3"][] = "L26";
        $arr["evaluations_rating_3"][] = "M26";
        $arr["evaluations_cmp_3"][] = "N26";
        $arr["evaluations_name_4"][] = "O26";
        $arr["evaluations_rating_4"][] = "P26";
        $arr["evaluations_cmp_4"][] = "Q26";
        $arr["evaluations_name_5"][] = "R26";
        $arr["evaluations_rating_5"][] = "S26";
        $arr["evaluations_cmp_5"][] = "T26";
        $arr["evaluations_name_6"][] = "U26";
        $arr["evaluations_rating_6"][] = "V26";
        $arr["evaluations_cmp_6"][] = "W26";
        $arr["evaluations_name_7"][] = "X26";
        $arr["evaluations_rating_7"][] = "Y26";
        $arr["evaluations_cmp_7"][] = "Z26";
        $arr["evaluations_name_8"][] = "AA26";
        $arr["evaluations_rating_8"][] = "AB26";
        $arr["evaluations_cmp_8"][] = "AC26";
        $arr["evaluations_name_9"][] = "AD26";
        $arr["evaluations_rating_9"][] = "AE26";
        $arr["evaluations_cmp_9"][] = "AF26";
        $arr["evaluations_name_0"][] = "C27";
        $arr["evaluations_rating_0"][] = "D27";
        $arr["evaluations_cmp_0"][] = "E27";
        $arr["evaluations_name_1"][] = "F27";
        $arr["evaluations_rating_1"][] = "G27";
        $arr["evaluations_cmp_1"][] = "H27";
        $arr["evaluations_name_2"][] = "I27";
        $arr["evaluations_rating_2"][] = "J27";
        $arr["evaluations_cmp_2"][] = "K27";
        $arr["evaluations_name_3"][] = "L27";
        $arr["evaluations_rating_3"][] = "M27";
        $arr["evaluations_cmp_3"][] = "N27";
        $arr["evaluations_name_4"][] = "O27";
        $arr["evaluations_rating_4"][] = "P27";
        $arr["evaluations_cmp_4"][] = "Q27";
        $arr["evaluations_name_5"][] = "R27";
        $arr["evaluations_rating_5"][] = "S27";
        $arr["evaluations_cmp_5"][] = "T27";
        $arr["evaluations_name_6"][] = "U27";
        $arr["evaluations_rating_6"][] = "V27";
        $arr["evaluations_cmp_6"][] = "W27";
        $arr["evaluations_name_7"][] = "X27";
        $arr["evaluations_rating_7"][] = "Y27";
        $arr["evaluations_cmp_7"][] = "Z27";
        $arr["evaluations_name_8"][] = "AA27";
        $arr["evaluations_rating_8"][] = "AB27";
        $arr["evaluations_cmp_8"][] = "AC27";
        $arr["evaluations_name_9"][] = "AD27";
        $arr["evaluations_rating_9"][] = "AE27";
        $arr["evaluations_cmp_9"][] = "AF27";
        $arr["evaluations_name_0"][] = "C28";
        $arr["evaluations_rating_0"][] = "D28";
        $arr["evaluations_cmp_0"][] = "E28";
        $arr["evaluations_name_1"][] = "F28";
        $arr["evaluations_rating_1"][] = "G28";
        $arr["evaluations_cmp_1"][] = "H28";
        $arr["evaluations_name_2"][] = "I28";
        $arr["evaluations_rating_2"][] = "J28";
        $arr["evaluations_cmp_2"][] = "K28";
        $arr["evaluations_name_3"][] = "L28";
        $arr["evaluations_rating_3"][] = "M28";
        $arr["evaluations_cmp_3"][] = "N28";
        $arr["evaluations_name_4"][] = "O28";
        $arr["evaluations_rating_4"][] = "P28";
        $arr["evaluations_cmp_4"][] = "Q28";
        $arr["evaluations_name_5"][] = "R28";
        $arr["evaluations_rating_5"][] = "S28";
        $arr["evaluations_cmp_5"][] = "T28";
        $arr["evaluations_name_6"][] = "U28";
        $arr["evaluations_rating_6"][] = "V28";
        $arr["evaluations_cmp_6"][] = "W28";
        $arr["evaluations_name_7"][] = "X28";
        $arr["evaluations_rating_7"][] = "Y28";
        $arr["evaluations_cmp_7"][] = "Z28";
        $arr["evaluations_name_8"][] = "AA28";
        $arr["evaluations_rating_8"][] = "AB28";
        $arr["evaluations_cmp_8"][] = "AC28";
        $arr["evaluations_name_9"][] = "AD28";
        $arr["evaluations_rating_9"][] = "AE28";
        $arr["evaluations_cmp_9"][] = "AF28";
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



    private $scheme = array();


}

?>