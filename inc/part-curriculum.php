<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_curriculum extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-tbody-class-tr', array( $this, 'filter_tbody_class_tr'), 10, 2 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
        add_filter( 'mif-mr-thead-row', array( $this, 'filter_thead_row'), 10, 2 );

        $this->save( 'curriculum' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-curriculum.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-curriculum.php', false );

        }
    }
    



    
    
    
    // 
    // Показывает учебный план
    // 
    
    public function get_curriculum()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'curriculum' );

        $arr = $this->get_courses_arr(); 

        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $this->get_table_html( $arr );;

        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_curriculum', $out );
    }
    


    
    private function get_num_semesters()
    {
        $curriculum_arr = $this->get_curriculum_arr();
        $n = 0;

        foreach ( $curriculum_arr as $item ) 
            foreach ( $item['semesters'] as $key => $item2 ) if ( $key > $n ) $n = $key;

        return $n;
    }
    

  
    
    public function filter_tbody_class_tr( $class, $key2 )
    {
        $curriculum_arr = $this->get_curriculum_arr();
        
        $arr = array();
        if ( ! empty($class) ) $arr[] = $class;

        if ( isset( $curriculum_arr[$key2] ) ) {

            foreach ( $curriculum_arr[$key2]['semesters'] as $key3 => $item ) $arr[] = 'csm-' . $key3;
        
        }

        return implode( ' ', $arr );
    }




    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        $curriculum_arr = $this->get_curriculum_arr();
        
        $n = $this->get_num_semesters();
        
        // if ( isset( $curriculum_arr[$key2] ) ) {
        if ( ! empty( $n ) && ! empty( $curriculum_arr[$key2]['course_stat'] ) ) {
            
            $item = $curriculum_arr[$key2]['course_stat'];
            
            $lec = ( ! empty( $item['lec'] ) ) ? (int) $item['lec'] : 0; 
            $lab = ( ! empty( $item['lab'] ) ) ? (int) $item['lab'] : 0;
            $prac = ( ! empty( $item['prac'] ) ) ? (int) $item['prac'] : 0;
            $srs = ( ! empty( $item['srs'] ) ) ? (int) $item['srs'] : 0;
            $exam = ( ! empty( $item['exam'] ) ) ? (int) $item['exam'] : 0;
            
            $v = $lec + $lab + $prac + $srs + $exam;
            $a = $lec + $lab + $prac;
            $s = $srs;
            $e = $exam;
            $z = $v / 36;
            
            $v = ( ! empty($v) ) ? $v : '';
            $a = ( ! empty($a) ) ? $a : '';
            $s = ( ! empty($s) ) ? $s : '';
            $e = ( ! empty($e) ) ? $e : '';
            $z = ( ! empty($z) ) ? $z : '';


            $arr[] = $this->add_to_col( $z, array('elem' => 'th', 'class' => 'cell long text-center mr-blue') );
            $arr[] = $this->add_to_col( $v, array('elem' => 'th', 'class' => 'cell long text-center') );
            $arr[] = $this->add_to_col( $a, array('elem' => 'th', 'class' => 'cell long text-center') );
            $arr[] = $this->add_to_col( $s, array('elem' => 'th', 'class' => 'cell long text-center') );
            $arr[] = $this->add_to_col( $e, array('elem' => 'th', 'class' => 'cell long text-center') );


            for ( $i = 1; $i <= $n; $i++ ) {
                 
                $lec = ''; 
                $lab = '';
                $prac = '';
                $srs = '';
                $exam = '';
                $exam_att = '';
                $v = 0;

                $f = false;

                if ( isset( $curriculum_arr[$key2]['semesters'][$i] ) ) {
                    
                    $item = $curriculum_arr[$key2]['semesters'][$i];
                    
                    $lec = ( ! empty( $item['lec'] ) ) ? (int) $item['lec'] : 0; 
                    $lab = ( ! empty( $item['lab'] ) ) ? (int) $item['lab'] : 0;
                    $prac = ( ! empty( $item['prac'] ) ) ? (int) $item['prac'] : 0;
                    $srs = ( ! empty( $item['srs'] ) ) ? (int) $item['srs'] : 0;
                    $exam = ( ! empty( $item['exam'] ) ) ? (int) $item['exam'] : 0;
                    $exam_att = ( ! empty( $item['att'] ) ) ? implode( ', ', $item['att'] ) : '';

                    $v = $lec + $lab + $prac + $srs + $exam;

                    $f = ! empty ( $v ) || ! empty( $exam_att );

                    if ( $lec == 0 ) $lec = '';
                    if ( $lab == 0) $lab = '';
                    if ( $prac == 0 ) $prac = '';
                    if ( $srs == 0) $srs = '';
                    if ( $exam == 0) $exam = '';
                    
                }
                
                if ( empty ( $v ) ) $v = ( ! empty( $exam_att ) ) ? $exam_att : '';
               
                $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';
                if ( $f ) $class = 'cell mr-green';
                $class .= ' rsm-' . $i;
                
                $arr[] = $this->add_to_col( $lec, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $lab, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $prac, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $srs, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $exam, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $exam_att, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $v, array('elem' => 'td', 'class' => $class . ' d-none') );
                
            }
            
        } else {
            
            for ( $i = 1; $i <= 12; $i++ ) $arr[] = $this->add_to_col( '', array('elem' => 'td', 'class' => 'cell') );
            
        };
        
        return $arr;
    }
    
    
    
    public function filter_thead_row( $arr, $courses_arr )
    {
        $arr = array();
        
        $n = $this->get_num_semesters();
        
        if ( ! empty( $n ) ) {
            
            $arr2 = array();
            
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2) );
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2, 'class' => 'name-courses') );
            
            $arr2[] = $this->add_to_col( 'ЗЕ', array('elem' => 'th', 'class' => 'mr-blue align-middle', 'rowspan' => 2, 'title' => 'Зачетных единиц') );
            $arr2[] = $this->add_to_col( 'Всего', array('elem' => 'th', 'colspan' => 4) );
            
            for ( $i = 1; $i <= $n; $i++ ) {
                
                $text = 'Семестр ' . $i;
                $sm = 'csm-' . $i;
                $class = 'selectable rsm-' . $i;
                $class .= ' csm-' . $i;

                $arr2[] = $this->add_to_col( $text, array('elem' => 'th', 'class' => $class, 'colspan' => 6, 'data-cmp' => $sm) );
                $arr2[] = $this->add_to_col( $i, array('elem' => 'th', 'class' => $class . ' d-none', 'data-cmp' => $sm) );
                
            }
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            $arr2 = array();
            
            $arr2[] = $this->add_to_col( 'в', array('elem' => 'th', 'title' => 'Всего часов') );
            $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'title' => 'Аудиторных часов') );
            $arr2[] = $this->add_to_col( 'с', array('elem' => 'th', 'title' => 'Часов СРС') );
            $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'title' => 'Часов контроля') );

            for ( $i = 1; $i <= $n; $i++ ) {
                
                $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';
                $class .= ' rsm-' . $i;

                $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => $class, 'title' => 'Лекции') );
                $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => $class, 'title' => 'Лабораторные работы') );
                $arr2[] = $this->add_to_col( 'п', array('elem' => 'th', 'class' => $class, 'title' => 'Практики') );
                $arr2[] = $this->add_to_col( 'с', array('elem' => 'th', 'class' => $class, 'title' => 'Самостоятельная работа') );
                $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => $class, 'title' => 'Контроль') );
                $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => $class, 'title' => 'Тип контроля') );
                $arr2[] = $this->add_to_col( 'в', array('elem' => 'th', 'class' => $class . ' d-none', 'title' => 'Всего часов') );
                
            }
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            
            $arr2 = array();
            $ch = '<input type="checkbox" class="all" name="all" value="on" checked>';
            // $ch = '<a href="#" class="row-on">on</a> / <a href="#" class="row-off">off</a>';
            
            $arr2[] = $this->add_to_col( $ch, array('elem' => 'th', 'colspan' => 2, 'class' => 'text-start') );
            // $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'colspan' => 2) );
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'class' => 'mr-blue') );
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'colspan' => 4) );
            
            for ( $i = 1; $i <= $n; $i++ ) {
                
                $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';
                $class .= ' rsm-' . $i;
                
                $ch = '<input type="checkbox" class="rsm" data-row="rsm-' . $i . '" name="rsm-' . $i . '" value="on" checked />';
                $arr2[] = $this->add_to_col( $ch, array('elem' => 'th', 'class' => $class . ' matser', 'colspan' => 6) );
                
                $ch = '<input type="checkbox" class="rsm" data-row="rsm-' . $i . '" name="rsm-' . $i . '" value="on" />';
                $arr2[] = $this->add_to_col( $ch, array('elem' => 'th', 'class' => $class . ' d-none' ) );
                
            }
            
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            


        } else {
            
            $arr2 = array();
            
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2) );
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2) );
            $arr2[] = $this->add_to_col( 'Семестр 1', array('elem' => 'th', 'colspan' => 6) );
            $arr2[] = $this->add_to_col( 'Семестр 2', array('elem' => 'th', 'colspan' => 6) );
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            $arr2 = array();
            
            for ( $i = 1; $i <= 12; $i++ ) $arr2[] = $this->add_to_col( '&nbsp;', array('elem' => 'th') );
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
        }
        
        return $arr;
    }
    
    
    
    public function filter_tbody_colspan( $n )
    {
        $nn = $this->get_num_semesters();
        
        $n = ( ! empty( $nn ) ) ? $n + 5 + $nn * 7 : $n + 14;
        
        return $n;
    }
    
}

?>