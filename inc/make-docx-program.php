<?php

//
// Обработка шаблонов учебных дисциплин
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_docx_program extends mif_mr_docx { 

    // private $blank = '';

    function __construct( $path = NULL )
    {
        
        parent::__construct( $path );

    }


    
    
    function arr_to_docx( $arr )
    {

        // p($arr);
        $a = array();

        $a['name'] = $arr['name'];
        $a['place'] = $this->course_place( $arr );
        $a['target'] = $this->p( $arr['data']['content']['target'] );
        $a['cmp_total'] = $this->cmp( $arr['meta']['cmp_descr'] );
        $a['prev_next'] = $this->prev_next( $arr );
        $a['outcomes_z_total'] = $this->ul( $arr['meta']['outcomes']['z'] );
        $a['outcomes_u_total'] = $this->ul( $arr['meta']['outcomes']['u'] );
        $a['outcomes_v_total'] = $this->ul( $arr['meta']['outcomes']['v'] );
        $a['biblio_basic'] = $this->ol( $arr['data']['biblio']['basic'] );
        $a['biblio_additional'] = $this->ol( $arr['data']['biblio']['additional'] );
        $a['it_inet'] = $this->ol( $arr['data']['it']['inet'] );
        $a['it_app'] = $this->ol( $arr['data']['it']['app'] );
        $a['mto'] = $this->ol( $arr['data']['mto']['mto'] );
        $a['authors'] = $this->dl( $arr['data']['authors']['authors'] );
        $a['parts_content'] = $this->parts_content( $arr['data']['content']['parts'] );
        $a['parts_hours'] = $this->parts_hours( $arr['data']['content']['parts'] );
        
        // 4. Объём дисциплины и виды учебной работы
        
        $a['all_aud'] = $this->all_aud( $arr['meta']['hours'] );
        $a['all_lec'] = ( $m = $arr['meta']['hours']['lec'] != 0 ) ? $m : '–';
        $a['all_prac'] = ( $m = $arr['meta']['hours']['prac'] != 0 ) ? $m : '–';
        $a['all_lab'] = ( $m = $arr['meta']['hours']['lab'] != 0 ) ? $m : '–';
        $a['all_srs'] = ( $m = $arr['meta']['hours']['srs'] != 0 ) ? $m : '–';
        $a['all_exam'] = ( $m = $arr['meta']['hours']['exam'] != 0 ) ? $m : '–';
        $a['all_hours'] = $arr['meta']['hours_stat']['hours'];
        $a['all_ze'] = $arr['meta']['hours_stat']['hours_ze'];
        
        $a['sem_list'] = $this->sem_list( $arr['meta']['semesters'] );
        $a['sem_aud'] = $this->sem_aud( $arr['meta']['semesters'] );
        $a['sem_lec'] = $this->sem_hours( $arr['meta']['semesters'], 'lec' );
        $a['sem_prac'] = $this->sem_hours( $arr['meta']['semesters'], 'prac' );
        $a['sem_lab'] = $this->sem_hours( $arr['meta']['semesters'], 'lab' );
        $a['sem_srs'] = $this->sem_hours( $arr['meta']['semesters'], 'srs' );
        $a['sem_exam'] = $this->sem_hours( $arr['meta']['semesters'], 'exam' );
        
        $a['sem_hours'] = $this->sem_stat( $arr['meta']['semesters_stat'], 'hours' );
        $a['sem_ze'] = $this->sem_stat( $arr['meta']['semesters_stat'], 'hours_ze' );
        $a['sem_exam_name'] = $this->sem_exam_name( $arr['meta']['semesters'] );
        $a['sem_exam_name'] = $this->sem_exam_name( $arr['meta']['semesters'] );
        
        // ФОС
        $a['stage_cmp'] = $this->stage_cmp( $arr['meta']['stage'] );
        $a['parts_outcomes'] = $this->parts_outcomes( $arr['data']['content']['parts'] );
        $a['evaluations'] = $this->evaluations( $arr['data']['evaluations'] );
        $a['evaluations_list'] = $this->evaluations_list( $arr['data']['evaluations'] );
        $a['cmp_indicators'] = $this->cmp_indicators( $arr['meta']['cmp'] );
        
        // Паспорт и программа формирования компетенций




        $path = $this->make_docx( $a );

        return $path;

    }

    
 



    


    private function all_aud( $a )
    {
        return $a['lec'] + $a['lab'] + $a['prac'];
    }




    private function sem_exam_name( $a )
    {
        ksort( $a );

        $b = array();
        foreach ( $a as $i ) $b[] = ( ! empty( $i['att'] ) ) ? implode( ', ', $i['att'] ) : '–'; 

        return implode( ' / ', $b );
    }



    private function sem_stat( $a, $k )
    {
        ksort( $a );

        $b = array();
        foreach ( $a as $i ) $b[] = ( $i[$k] != 0 ) ? $i[$k] : '–'; 

        return implode( ' / ', $b );
    }



    private function sem_aud( $a )
    {
        ksort( $a );

        $b = array();
        foreach ( $a as $i ) $b[] = $i['lec'] + $i['lab'] + $i['prac']; 

        return implode( ' / ', $b );
    }



    private function sem_hours( $a, $k )
    {
        ksort( $a );

        $b = array();
        foreach ( $a as $i ) $b[] = ( isset( $i[$k] ) && $i[$k] != 0 ) ? $i[$k] : '–'; 

        return implode( ' / ', $b );
    }



    private function sem_list( $a )
    {
        ksort( $a );
        return implode( ' / ', array_keys( $a ) );
    }



    private function evaluations_list( $a )
    {
        $b = array();
        $n = 1;
        
        foreach ( $a as $sem => $data ) 
            foreach ( $data['data'] as $item ) $b[] = $item['name'];

        $b = array_unique( $b );
        sort( $b );
        
        return $this->ol2( $b );
    }



    private function evaluations( $a )
    {
        $b = array();
        $n = 1;
        
        foreach ( $a as $sem => $data ) 
            foreach ( $data['data'] as $item ) 
                $b[] = array( 
                        'evaluations' => $n++, 
                        'evaluations_name' => $item['name'], 
                        'evaluations_rating' => $item['att']['rating'], 
                        'evaluations_cmp' => $item['att']['cmp'], 
                        'evaluations_sem' => $sem, 
                        );
        
        return $b;
    }



    private function cmp_indicators( $a )
    {
        global $tree;

        $name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );

        $b = array();
        $c = array();
        
        foreach ( $a as $cmp ) {
            
            // if ( empty( $tree['content']['competencies']['data'][$cmp]['indicators'][0] ) ) continue;
            
            // $d = '';
            $d = array();

            // p($tree['content']['competencies']['data']);
            // p($tree['content']['competencies']['data'][$cmp]['indicators']);
            if ( isset( $tree['content']['competencies']['data'][$cmp]['indicators'] ) )          
                foreach( $tree['content']['competencies']['data'][$cmp]['indicators'] as $key => $item ) $c[$key] = $this->ol2( $item );
                // p($item);
                // p($c);
            
            foreach( $c as $k => $i ) {
            
                $d[$k] = ( isset( $name_indicators[$k] ) ) ? mb_ucfirst( $name_indicators[$k] ) : 'Индикатор ' . $k;
                $d[$k] .= ":\n" . $i;
            
            }

            $b[] = array( 
                            'cmp_indicators' => $cmp, 
                            'cmp_data' => implode( "\n", $d ), 
                        );

        }
        // p($a);

        return $b;
    }



    private function stage_cmp( $a )
    {
        $b = array();
        
        foreach ( $a as $cmp => $item ) $b[] = array( 
                                                'stage_cmp' => $cmp, 
                                                'stage_1' => ( ! empty( $item[1][0] ) ) ? implode( ', ', $item[1] ) : '–',
                                                'stage_2' => ( ! empty( $item[2][0] ) ) ? implode( ', ', $item[2] ) : '–',
                                                'stage_3' => ( ! empty( $item[3][0] ) ) ? implode( ', ', $item[3] ) : '–',
                                                );
        
        return $b;
    }





    private function prev_next( $a )
    {
        $out = '';
        
        $prev = ( ! empty( $a['meta']['prev_next']['prev'] ) ) ? '«' . implode( '», «', $a['meta']['prev_next']['prev'] ) . '»' : '';
        $next = ( ! empty( $a['meta']['prev_next']['next'] ) ) ? '«' . implode( '», «', $a['meta']['prev_next']['next'] ) . '»' : '';

        if ( ! empty( $prev ) ) 
            $out .= 'Для освоения дисциплины «' . $a['name'] . 
                    '» обучающиеся используют знания, умения, способы деятельности и установки, сформированные в ходе ' .
                    $prev . '. ';
        
        if ( ! empty( $next ) ) 
            $out .= 'Освоение данной дисциплины является необходимой основой для последующего изучения дисциплин ' . $next . '.';

        return $out;
    }




    private function course_place( &$arr )
    {
        $msg1 = '';
        $msg2 = '';
        
        if ( $arr['unit'] == 'ОД' ) $msg1 = ' относится к базовой части блока дисциплин'; 
        if ( $arr['unit'] == 'ВД' ) $msg1 = ' относится к вариативной части блока дисциплин'; 
        if ( ! empty( $arr['module'] ) ) $msg2 = ' и расположена в модуле «' . $arr['module'] . '»'; 
        // if ( $course_data['alternative'] ) $msg2 = ' и является дисциплиной по выбору'; 

        $out = 'Дисциплина «' . $arr['name'] . '»' . $msg1 . $msg2 . '.';    

        return $out;
    }



    private function parts_outcomes( $a )
    {
        $b = array();

        foreach ( $a as $k => $i ) {

            $d = array();

            if ( ! empty( $i['outcomes']['z'] ) ) $d[] = 'знать:' . "\n" . $this->ul2( $i['outcomes']['z'] );
            if ( ! empty( $i['outcomes']['u'] ) ) $d[] = 'уметь:' . "\n" . $this->ul2( $i['outcomes']['u'] );
            if ( ! empty( $i['outcomes']['v'] ) ) $d[] = 'владеть:' . "\n" . $this->ul2( $i['outcomes']['v'] );
        
            $b[] = array( 
                'parts_outcomes' => $k + 1,
                'parts_name' => $i['name'],
                'parts_cmp' => $i['cmp'],
                'parts_data' => implode( "\n", $d ),
            );

        }

        return $b;
    }



    private function parts_hours( $a )
    {
        $b = array();

        foreach ( $a as $k => $i ) {

            $b[] = array( 
                'parts_hours' => $k + 1,
                'parts_name' => $i['name'],
                'lec' => $i['hours']['lec'],
                'lab' => $i['hours']['lab'],
                'prac' => $i['hours']['prac'],
                'srs' => $i['hours']['srs'],
                'exam' => $i['hours']['exam'],
                'all' => $i['hours_z'],
            );

        }

        return $b;
    }


    private function parts_content( $a )
    {
        $b = array();

        foreach ( $a as $k => $i ) {

            $b[] = array( 
                'parts_content' => $k + 1,
                'parts_name' => $i['name'],
                'parts_data' => $i['content'],
            );

        }

        return $b;
    }


    private function p( $t )
    {
        $s = trim( $t, $this->p );
        if ( ! empty( $s ) ) $s .= '.';
        // return trim( $t, $this->p ) . '.';
        return $s;
    }



    private function cmp( $a )
    {   
        $b = array();
        foreach ( $a as $i ) 
            if ( ! empty( $i['descr'] ) ) 
                $b[] = "</w:t></w:r><w:r><w:tab/></w:r><w:r><w:t>– " . mb_lcfirst( trim( $i['descr'], $this->p ) ) . ' (' . $i['name'] . ')';
        $s = ( ! empty( $b ) ) ? implode( ";\n\n", $b ) . '.' : '';
        return $s;
    }



    private function ul( $a )
    {
        $b = array();
        foreach ( $a as $i ) $b[] = "</w:t></w:r><w:r><w:tab/></w:r><w:r><w:t>– " . mb_lcfirst( trim( $i, $this->p ) );
        $s = ( ! empty( $b ) ) ? implode( ";\n", $b ) . '.' : '';
        return $s;
    }


    private function ul2( $a )
    {
        $b = array();
        foreach ( $a as $i ) $b[] = "– " . mb_lcfirst( trim( $i, $this->p ) );
        $s = ( ! empty( $b ) ) ? implode( ";\n", $b ) . ';' : '';
        return $s;
    }



    private function ol( $a )
    {   
        $b = array();
        foreach ( $a as $k => $i ) $b[] = "</w:t></w:r><w:r><w:tab/></w:r><w:r><w:t>" . $k+1 . '. ' . mb_ucfirst( trim( $i, $this->p ) ) . '.';
        $s = implode( "\n", $b );
        return $s;
    }



    private function ol2( $a )
    {
        $b = array();
        foreach ( $a as $k => $i ) $b[] = $k+1 . '. ' . mb_ucfirst( trim( $i, $this->p ) );
        $s = implode( "\n", $b );
        return $s;
    }



    private function dl( $a )
    {
        $b = array();
        foreach ( $a as $i ) $b[] = mb_ucfirst( trim( $i, $this->p ) );
        $s = ( ! empty( $b ) ) ? implode( ",\n", $b ) . '.' : '';
        return $s;
    }







   
    
    // 
    // Паспорт и программа формирования компетенций
    // 
    
    function arr_to_docx_passport( $arr )
    {

        $a = array();
        // p($arr);


        $a['cmp_name'] = $arr['name'];
        $a['cmp_descr'] = $arr['descr'];
        $a['cmp_unit'] = $arr['unit'][2];
        $a['cmp_category'] = $this->cmp_category( $arr['category'] );
        
        $a['cmp_indicators'] = ( isset( $arr['indicators'] ) ) ? $this->cmp_indicators2( $arr['indicators'] ) : NULL;

        $a['cmp_outcomes_z'] = $this->ul2( $arr['outcomes']['z'] );
        $a['cmp_outcomes_u'] = $this->ul2( $arr['outcomes']['u'] );
        $a['cmp_outcomes_v'] = $this->ul2( $arr['outcomes']['v'] );

        $a['cmp_forms'] = $this->cmp_forms( $arr['courses'] );
        $a['cmp_sem'] = $this->cmp_sem( $arr['courses'] );
        $a['cmp_evaluations'] = $this->cmp_evaluations( $arr['courses'] );

        // $a[''] = $arr[''];
        // $a[''] = $arr[''];
        // $a[''] = $arr[''];
        // $a[''] = $arr[''];

        // p($a);

        $path = $this->make_docx( $a );

        return $path;

    }





    private function cmp_indicators2( $indicators )
    {
        $arr = array();

        $name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );

        foreach ( $indicators as $k => $i ) {

            $arr[] = array(
                        'cmp_indicators' => $k + 1,
                        'cmp_indicators_name' => ( isset( $name_indicators[$k] ) ) ? mb_ucfirst( $name_indicators[$k] ) : 'Индикатор ' . $k,
                        'cmp_indicators_data' => $this->ol2( $i ),
                    );

        }

        return $arr;
    }



    private function cmp_evaluations( $courses )
    {
        global $tree;

        $arr = array();
        
        foreach ( $courses as $k => $c ) {
            
            $e = ( isset( $tree['content']['courses']['clean'][$c]['meta']['evaluations'] ) ) ? $tree['content']['courses']['clean'][$c]['meta']['evaluations'] : array(); 

            $arr[] = array(
                'cmp_evaluations' => $k + 1,
                'cmp_courses' => $c,
                'cmp_evaluations_data' => implode( ". ", $e ),

            );

        }

        return $arr;
    }



    private function cmp_sem( $courses )
    {
        global $tree;

        
        
        $arr = array();
        
        foreach ( $courses as $k => $c ) {

            $a = $tree['content']['courses']['clean'][$c]['meta']['semesters'];

            $arr[] = array(
                'cmp_sem' => $k + 1,
                'cmp_courses' => $c,
                'cmp_sem1' => ( isset( $a['1'] ) ) ? '+' : '',
                'cmp_sem2' => ( isset( $a['2'] ) ) ? '+' : '',
                'cmp_sem3' => ( isset( $a['3'] ) ) ? '+' : '',
                'cmp_sem4' => ( isset( $a['4'] ) ) ? '+' : '',
                'cmp_sem5' => ( isset( $a['5'] ) ) ? '+' : '',
                'cmp_sem6' => ( isset( $a['6'] ) ) ? '+' : '',
                'cmp_sem7' => ( isset( $a['7'] ) ) ? '+' : '',
                'cmp_sem8' => ( isset( $a['8'] ) ) ? '+' : '',
                'cmp_sem9' => ( isset( $a['9'] ) ) ? '+' : '',
                'cmp_sem10' => ( isset( $a['10'] ) ) ? '+' : '',

            );

        }

        return $arr;
    }



    private function cmp_forms( $courses )
    {
        global $tree;

        $arr = array();
        
        foreach ( $courses as $k => $c ) {

            $a = $tree['content']['courses']['clean'][$c]['meta'];
            // p($a['hours']);
            $aa = array();
            if ( ! empty( $a['outcomes']['z'] ) ) $aa[] = 'знать:' . "\n" . $this->ul2( $a['outcomes']['z'] );
            if ( ! empty( $a['outcomes']['u'] ) ) $aa[] = 'уметь:' . "\n" . $this->ul2( $a['outcomes']['u'] );
            if ( ! empty( $a['outcomes']['v'] ) ) $aa[] = 'владеть:' . "\n" . $this->ul2( $a['outcomes']['v'] );

            $bb = array();
            if ( ! empty( $a['hours']['lec'] ) ) $bb[] = 'лекции';
            if ( ! empty( $a['hours']['prac'] ) ) $bb[] = 'практические занятия';
            if ( ! empty( $a['hours']['lab'] ) ) $bb[] = 'лабораторные работы';
            if ( ! empty( $a['hours']['att']['зч'] ) ) $bb[] = 'зачет';
            if ( ! empty( $a['hours']['att']['зчо'] ) ) $bb[] = 'зачет с оценкой';
            if ( ! empty( $a['hours']['att']['эк'] ) ) $bb[] = 'экзамен';
            // if ( ! empty( $a['evaluations'] ) ) $bb = $a['evaluations'];

            $arr[] = array(
                'cmp_forms' => $k + 1,
                'cmp_courses' => $c,
                'cmp_outcomes' => implode( "\n", $aa ),
                'cmp_forms_data' => mb_strtolower( implode( ", ", $bb ) ),

            );

        }

        return $arr;
    }



    private function cmp_category( &$c )
    {
        if ( empty( $c ) || $c == 'default' ) return;
        return "\n" . 'Наименование категории компетенций: ' . mb_strtolower( $c ). ".\n";
    }











    private $p = ';:,.? ';

   
}

?>