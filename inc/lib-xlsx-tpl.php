<?php

//
// Обработка шаблонов учебных дисциплин
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_xlsx_tpl extends mif_mr_xlsx { 

    // private $blank = '';

    function __construct( $path = NULL )
    {
        
        parent::__construct( $path );

        // add_filter( 'scheme-data-courses', array( $this, 'scheme_data'), 10 );
        // $this->scheme = apply_filters( 'scheme-data-courses', array() );

        $this->scheme = apply_filters( 'scheme-data-courses', $this->get_scheme() );

    
    }




   
    // 
    // Перевести массив в файл xlsx  
    // 
    
    public function arr_to_xlsx( $arr = array() )
    {
        if ( empty( $arr['data'] ) ) return;

        $a = (array) $arr['data']; 
        // p($arr);
        $arr2 = array();

        // p($this->scheme['name'][0]);

        // $this->set( $this->scheme['name'][0], 'name' );

        // f($this->scheme['name'][0]);

        // Название, Цель

        $this->set( $this->scheme['name'][0], $arr['name'] );
        $this->set( $this->scheme['target'][0], $a['content']['target'] );
        $this->set( $this->scheme['target_max'][0], $a['content']['target'] );

        // Разделы

        for ( $i = 0; $i < 10; $i++ ) { 

            if ( ! empty( $a['content']['parts'][$i]['name'] ) ) $this->set( $this->scheme['parts_name'][$i], $a['content']['parts'][$i]['name'] );
            if ( ! empty( $a['content']['parts'][$i]['content'] ) ) $this->set( $this->scheme['parts_content'][$i], $a['content']['parts'][$i]['content'] );
            if ( ! empty( $a['content']['parts'][$i]['cmp'] ) ) $this->set( $this->scheme['parts_cmp'][$i], $a['content']['parts'][$i]['cmp'] );
            if ( ! empty( $a['content']['parts'][$i]['hours_raw'] ) ) $this->set( $this->scheme['parts_hours'][$i], $a['content']['parts'][$i]['hours_raw'] );
            
            for ( $j = 0; $j < 2; $j++ ) { 
                
                if ( ! empty( $a['content']['parts'][$i]['outcomes']['z'][$j] ) ) 
                    $this->set( $this->scheme['parts_outcomes_z_'.$j][$i], $a['content']['parts'][$i]['outcomes']['z'][$j] );
                if ( ! empty( $a['content']['parts'][$i]['outcomes']['u'][$j] ) ) 
                    $this->set( $this->scheme['parts_outcomes_u_'.$j][$i], $a['content']['parts'][$i]['outcomes']['u'][$j]);
                if ( ! empty( $a['content']['parts'][$i]['outcomes']['v'][$j] ) ) 
                    $this->set( $this->scheme['parts_outcomes_v_'.$j][$i], $a['content']['parts'][$i]['outcomes']['v'][$j]);

            }
            
        }

        // Ячейки для выравнивания размеров

        $this->set( $this->scheme['parts_name_max'][0], $this->max( $a['content']['parts'], 'name' ) );
        $this->set( $this->scheme['parts_content_max'][0], $this->max( $a['content']['parts'], 'content' ) );
        
        for ( $j = 0; $j < 2; $j++ ) { 
            
            $this->set( $this->scheme['parts_outcomes_z_' . $j . '_max'][0], $this->max( $a['content']['parts'], 'outcomes', 'z', $j ) );
            $this->set( $this->scheme['parts_outcomes_u_' . $j . '_max'][0], $this->max( $a['content']['parts'], 'outcomes', 'u', $j ) );
            $this->set( $this->scheme['parts_outcomes_v_' . $j . '_max'][0], $this->max( $a['content']['parts'], 'outcomes', 'v', $j ) );

        }
        
        // Оценочные средства
            
        for ( $i = 0; $i < 10; $i++ ) {

            for ( $j = 0; $j < 10; $j++ ) { 

                if ( ! empty( $a['evaluations'][$j]['data'][$i]['name'] ) ) 
                    $this->set( $this->scheme['evaluations_name_'.$i][$j], $a['evaluations'][$j]['data'][$i]['name'] );
                if ( ! empty( $a['evaluations'][$j]['data'][$i]['att']['rating'] ) )
                    $this->set( $this->scheme['evaluations_rating_'.$i][$j], $a['evaluations'][$j]['data'][$i]['att']['rating'] );
                if ( ! empty( $a['evaluations'][$j]['data'][$i]['att']['cmp'] ) )
                    $this->set( $this->scheme['evaluations_cmp_'.$i][$j], $a['evaluations'][$j]['data'][$i]['att']['cmp'] );
    
            }

        } 

        // Литература

        for ( $i = 0; $i < 5; $i++ ) {

            if ( empty( $a['biblio']['basic'][$i] ) ) continue;
            $this->set( $this->scheme['biblio_basic'][$i], $a['biblio']['basic'][$i] ); 

        } 

        for ( $i = 0; $i < 10; $i++ ) {
            
            if ( empty( $a['biblio']['additional'][$i] ) ) continue;
            $this->set( $this->scheme['biblio_additional'][$i], $a['biblio']['additional'][$i] ); 

        } 
        
        // Информационные технологии
        
        for ( $i = 0; $i < 5; $i++ ) {

            if ( empty( $a['it']['inet'][$i] ) ) continue;
            $this->set( $this->scheme['it_inet'][$i], $a['it']['inet'][$i] ); 

        } 

        for ( $i = 0; $i < 5; $i++ ) {

            if ( empty( $a['it']['app'][$i] ) ) continue;
            $this->set( $this->scheme['it_app'][$i], $a['it']['app'][$i] ); 

        } 

        // Материально-техническое обеспечение

        for ( $i = 0; $i < 5; $i++ ) {

            if ( empty( $a['mto']['mto'][$i] ) ) continue;
            $this->set( $this->scheme['mto'][$i], $a['mto']['mto'][$i] ); 

        } 
    
        // Разработчики

        for ( $i = 0; $i < 5; $i++ ) {

            if ( empty( $a['authors']['authors'][$i] ) ) continue;
            $this->set( $this->scheme['authors'][$i], $a['authors']['authors'][$i] ); 

        } 

        // Сведения о шаблоне
        
        $this->set( $this->scheme['opop'][0], get_the_title( $arr['from_id'] ) );
        $this->set( $this->scheme['opop_url'][0], get_permalink( $arr['from_id'] ) );

        return $arr;
    }




    private function max( $a = array(), $k = 'name', $k2 = NULL, $k3 = NULL )
    {
        $s = '';

        if ( $k == 'outcomes' ) {

            foreach ( $a as $i ) 
                if ( ! empty( $i[$k][$k2][$k3] ) ) 
                    if ( mb_strlen( $s ) < mb_strlen( $i[$k][$k2][$k3] ) ) $s = $i[$k][$k2][$k3];

        } else {

            foreach ( $a as $i ) 
                if ( ! empty( $i[$k] ) ) 
                    if ( mb_strlen( $s ) < mb_strlen( $i[$k] ) ) $s = $i[$k];

        }
        
        return $s;
    }



   
    // 
    // Перевести файл xlsx в массив
    // 
    
    public function xlsx_to_arr()
    {
        $arr = array();
        // p($this->scheme);
        
        // Название, Цель

        $arr['name'] = $this->get( $this->scheme['name'][0] );
        $arr['content']['target'] = $this->get( $this->scheme['target'][0] );

        // Разделы

        for ( $i = 0; $i < 10; $i++ ) { 

            if ( empty( $this->get( $this->scheme['parts_name'][$i] ) ) ) continue;

            $arr['content']['parts'][$i]['name'] = $this->get( $this->scheme['parts_name'][$i] );
            $arr['content']['parts'][$i]['content'] = $this->get( $this->scheme['parts_content'][$i] );
            $arr['content']['parts'][$i] ['cmp'] = $this->get( $this->scheme['parts_cmp'][$i] );
            $arr['content']['parts'][$i] ['hours_raw'] = $this->get( $this->scheme['parts_hours'][$i] );
            $arr['content']['parts'][$i] ['hours'] = content::get_hours( $this->get( $this->scheme['parts_hours'][$i] ) );
            
            for ( $j = 0; $j < 10; $j++ ) { 
                
                if ( isset( $this->scheme['parts_outcomes_z_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['z'][] = $this->get( $this->scheme['parts_outcomes_z_'.$j][$i] );
                if ( isset( $this->scheme['parts_outcomes_u_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['u'][] = $this->get( $this->scheme['parts_outcomes_u_'.$j][$i] );
                if ( isset( $this->scheme['parts_outcomes_v_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['v'][] = $this->get( $this->scheme['parts_outcomes_v_'.$j][$i] );

            }
            
        }

        // Оценочные средства
            
        for ( $i = 0; $i < 10; $i++ ) {

            for ( $j = 0; $j < 10; $j++ ) { 

                if ( empty( $this->scheme['evaluations_name_'.$i][$j] ) ) continue;
                if ( empty( $this->get( $this->scheme['evaluations_name_'.$i][$j] ) ) ) continue;
                
                $arr['evaluations'][$j]['sem'] = $j;
                if ( isset( $this->scheme['evaluations_name_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['name'] = $this->get( $this->scheme['evaluations_name_'.$i][$j] );
                if ( isset( $this->scheme['evaluations_rating_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['rating'] = $this->get( $this->scheme['evaluations_rating_'.$i][$j] );
                if ( isset( $this->scheme['evaluations_cmp_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['cmp'] = $this->get( $this->scheme['evaluations_cmp_'.$i][$j] );
    
            }

        } 

        // Литература

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['biblio_basic'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['biblio_basic'][$i] ) ) ) continue;
            
            $arr['biblio']['basic'][] = $this->get( $this->scheme['biblio_basic'][$i] );

        } 

        for ( $i = 0; $i < 10; $i++ ) {
            
            if ( empty( $this->scheme['biblio_additional'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['biblio_additional'][$i] ) ) ) continue;
            
            $arr['biblio']['additional'][] = $this->get( $this->scheme['biblio_additional'][$i] );
        
        } 
        
        // Информационные технологии
        
        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['it_inet'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['it_inet'][$i] ) ) ) continue;
            
            $arr['it']['inet'][] = $this->get( $this->scheme['it_inet'][$i] );

        } 
        
        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['it_app'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['it_app'][$i] ) ) ) continue;
            
            $arr['it']['app'][] = $this->get( $this->scheme['it_app'][$i] );

        } 
    
        // Материально-техническое обеспечение

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['mto'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['mto'][$i] ) ) ) continue;
            
            $arr['mto']['mto'][] = $this->get( $this->scheme['mto'][$i] );

        } 
    
        // Разработчики

        for ( $i = 0; $i < 10; $i++ ) {

            if ( empty( $this->scheme['authors'][$i] ) ) continue;
            if ( empty( $this->get( $this->scheme['authors'][$i] ) ) ) continue;
            
            $arr['authors']['authors'][] = $this->get( $this->scheme['authors'][$i] );

        } 
    
        //
        // guidelines ???? !!!!
        //

        return $arr;
    }




    //
    // Получить название дисциплины
    // 

    function get_name_course()
    {
        return $this->get( $this->scheme['name'][0] );
    }
        



    //
    // Схема данных дисциплины
    //
    

    function get_scheme()
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
        $arr["cmp"][] = "C5";
        $arr["hours"][] = "C6";
        $arr["target"][] = "C7";
        $arr["target_max"][] = "AG7";
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
        $arr["parts_name_max"][] = "AG9";
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
        $arr["parts_content_max"][] = "AG12";
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
        $arr["parts_outcomes_z_0_max"][] = "AG13";
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
        $arr["parts_outcomes_u_0_max"][] = "AG14";
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
        $arr["parts_outcomes_v_0_max"][] = "AG15";
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
        $arr["parts_outcomes_z_1_max"][] = "AG16";
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
        $arr["parts_outcomes_u_1_max"][] = "AG17";
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
        $arr["parts_outcomes_v_1_max"][] = "AG18";
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
        $arr["opop"][] = "C81";
        $arr["opop_url"][] = "C82";

        return $arr;
    }




    // private 
    protected $scheme = array();


}

?>