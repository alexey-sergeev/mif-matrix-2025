<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_curriculum extends mif_mr_table {
    
    // private $explanation = array();


    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        // add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
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

        // $m = new curriculum( $this->get_companion_content( 'curriculum' ) );
        // $html = $m->get_html();
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
    

    


    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        // global $tree;
        
        // // $curriculum_arr = $tree['content']['curriculum']['data'][$key2];
        // p($tree['content']['curriculum']['data']);
        $curriculum_arr = $this->get_curriculum_arr();
        
        $n = $this->get_num_semesters();
        
        if ( isset( $curriculum_arr[$key2] ) ) {
            
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



            // p($key2);
            // p($item);
            // p($curriculum_arr[$key2]);


            for ( $i = 1; $i <= $n; $i++ ) {
                 
                $lec = ''; 
                $lab = '';
                $prac = '';
                $srs = '';
                $exam = '';
                $exam_att = '';
                
                if ( isset( $curriculum_arr[$key2]['semesters'][$i] ) ) {
                    
                    $item = $curriculum_arr[$key2]['semesters'][$i];
                    
                    $lec = ( ! empty( $item['lec'] ) ) ? (int) $item['lec'] : ''; 
                    $lab = ( ! empty( $item['lab'] ) ) ? (int) $item['lab'] : '';
                    $prac = ( ! empty( $item['prac'] ) ) ? (int) $item['prac'] : '';
                    $srs = ( ! empty( $item['srs'] ) ) ? (int) $item['srs'] : '';
                    $exam = ( ! empty( $item['exam'] ) ) ? (int) $item['exam'] : '';
                    $exam_att = ( ! empty( $item['att'] ) ) ? implode( ', ', $item['att'] ) : '';
                    // p($item);
                }
                
                $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';

                $arr[] = $this->add_to_col( $lec, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $lab, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $prac, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $srs, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $exam, array('elem' => 'td', 'class' => $class) );
                $arr[] = $this->add_to_col( $exam_att, array('elem' => 'td', 'class' => $class) );

            }
            // $course = $curriculum_arr[$key2];
            // p($course);




        };
        
        
        

        // p($curriculum_arr);

        // foreach ( $curriculum_arr as $name => $data ) {
            
            // Определить заголовок и не повторяться

            // $title = ( isset( $data['elective'] ) ) ? implode( ' / ', $data['elective'] ) : $name;
            // if ( in_array( $title, $titles ) ) continue;
            // $titles[] = $title;

            // // Сформировать строку

            // $html .= "<tr>\n";
            // $html .= "<td>$n</td>\n";

            // $html .= "<td>$title</td>\n";
            // $html .= $this->get_row( $data['semesters'] );
            
            // // p($data);
            
            // $html .= "</tr>\n";

            // $n++;

        // }
      
      
      
      
        
        
        
        
        
        
        
        return $arr;
    }
    
    
    
    public function filter_thead_row( $arr, $courses_arr )
    {
        $arr = array();
        // // p($key);
        // // p($key2);
        // global $tree;
        // $matrix_arr = $tree['content']['matrix']['data'];
        // $cmp = $this->get_cmp( $matrix_arr );
        
        // $index = array();
        
        $n = $this->get_num_semesters();
        
        if ( ! empty( $n ) ) {
            
            $arr2 = array();
            
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2) );
            $arr2[] = $this->add_to_col( '', array('elem' => 'th', 'rowspan' => 2) );
            
            $arr2[] = $this->add_to_col( 'ЗЕ', array('elem' => 'th', 'class' => 'mr-blue align-middle', 'rowspan' => 2) );
            $arr2[] = $this->add_to_col( 'Всего', array('elem' => 'th', 'colspan' => 4) );
            
            for ( $i = 1; $i <= $n; $i++ ) {
                
                // $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';
                $text = 'Семестр ' . $i;
                
                $arr2[] = $this->add_to_col( $text, array('elem' => 'th', 'class' => 'selectable', 'colspan' => 6) );
                
                // $arr2[] = $this->add_to_col( 'л', array('elem' => 'td', 'class' => $class) );
                // $arr2[] = $this->add_to_col( 'л', array('elem' => 'td', 'class' => $class) );
                // $arr2[] = $this->add_to_col( 'п', array('elem' => 'td', 'class' => $class) );
                // $arr2[] = $this->add_to_col( 'с', array('elem' => 'td', 'class' => $class) );
                // $arr2[] = $this->add_to_col( 'к', array('elem' => 'td', 'class' => $class) );
                // $arr2[] = $this->add_to_col( 'к', array('elem' => 'td', 'class' => $class) );
                
            }
        
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            $arr2 = array();
                        
            $arr2[] = $this->add_to_col( 'в', array('elem' => 'th', 'class' => '$class') );
            $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => '$class') );
            $arr2[] = $this->add_to_col( 'с', array('elem' => 'th', 'class' => '$class') );
            $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => '$class') );
    
            for ( $i = 1; $i <= $n; $i++ ) {
                
                $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';

                $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => $class) );
                $arr2[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => $class) );
                $arr2[] = $this->add_to_col( 'п', array('elem' => 'th', 'class' => $class) );
                $arr2[] = $this->add_to_col( 'с', array('elem' => 'th', 'class' => $class . ' long') );
                $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => $class) );
                $arr2[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => $class) );
                
            }
        
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            
        } else {

            
        }
        
        return $arr;
    }



    // public function filter_thead_col( $arr )
    // {

    //     $arr[] = $this->add_to_col( 'ЗЕ', array('elem' => 'th', 'class' => 'mr-blue') );
    //     $arr[] = $this->add_to_col( 'в', array('elem' => 'th', 'class' => '$class') );
    //     $arr[] = $this->add_to_col( 'л', array('elem' => 'th', 'class' => '$class') );
    //     $arr[] = $this->add_to_col( 'с', array('elem' => 'th', 'class' => '$class') );
    //     $arr[] = $this->add_to_col( 'к', array('elem' => 'th', 'class' => '$class') );

    //     $n = $this->get_num_semesters();
        
    //     for ( $i = 1; $i <= $n; $i++ ) {
            
    //         $class = ( $i % 2 == 0 ) ? 'cell' : 'cell mr-blue';
            
    //         $arr[] = $this->add_to_col( 'л', array('elem' => 'td', 'class' => $class) );
    //         $arr[] = $this->add_to_col( 'л', array('elem' => 'td', 'class' => $class) );
    //         $arr[] = $this->add_to_col( 'п', array('elem' => 'td', 'class' => $class) );
    //         $arr[] = $this->add_to_col( 'с', array('elem' => 'td', 'class' => $class) );
    //         $arr[] = $this->add_to_col( 'к', array('elem' => 'td', 'class' => $class) );
    //         $arr[] = $this->add_to_col( 'к', array('elem' => 'td', 'class' => $class) );
            
    //     }
    //     // $course = $c
        
    //     return $arr;
    // }
    
    
    public function filter_tbody_colspan( $n )
    {
        $nn = $this->get_num_semesters();
        return $n + 5 + $nn * 6;
    }




}

?>