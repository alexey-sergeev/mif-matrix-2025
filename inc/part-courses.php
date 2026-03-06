<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses extends mif_mr_courses_list {
    
    function __construct()
    {

        parent::__construct();
        
    }
    

    
    // 
    // Показывает часть 
    // 

    public function the_show()
    {

        global $wp_query;

        if ( isset( $wp_query->query_vars['id'] ) ) {

            if ( $template = locate_template( 'mr-course.php' ) ) {
               
                load_template( $template, false );
    
            } else {
    
                load_template( dirname( __FILE__ ) . '/../templates/mr-course.php', false );
    
            }

        } else {

            if ( $template = locate_template( 'mr-part-courses.php' ) ) {
               
                load_template( $template, false );
    
            } else {
    
                load_template( dirname( __FILE__ ) . '/../templates/mr-part-courses.php', false );
    
            }
        
        }

    }
    
    


    
    
    // 
    // Показывает дисциплины
    // 
    
    public function get_course()
    {
        global $wp_query;
        $course_id = $wp_query->query_vars['id'];
    
        global $tree;
        $arr = $tree['content']['courses']['clean'][$course_id];

        p($arr);

        $out = '';
    
        $out .= '<div class="container">';
        
        $out .= $this->row( $arr['name'], 'fw-bolder text-center mr-gray p-3' );
        $out .= $this->row( '<b>Компетенции: </b>' . $this->span( $arr['cmp'] ), 'mt-3' );
        $out .= $this->row( '<b>Академические часы: </b>' . $this->span( $arr['hours_raw'] ) . ' (лек, лаб, пр, СРС, кон)');
        $out .= $this->row( '<b>Семестры: </b>' . $this->span( $arr['semesters_num'] ) );

        $out .= '</div>';


        return apply_filters( 'mif_mr_part_get_courses', $out );
    }
    
    

    private function span( $t, $c = '' )
    {
        return '<span class="p-1 pl-3 pr-3 rounded mr-gray ' . $c. '">' . $t . '</span>';
    }


    private function row( $t, $cc = '', $rc = '' )
    {
        $out = '';

        $out .= '<div class="row ' . $rc . '">';
        $out .= '<div class="col p-1 ' . $cc . '">';
        $out .= $t;
        $out .= '</div>';
        $out .= '</div>';

        return $out;
    }




}

?>