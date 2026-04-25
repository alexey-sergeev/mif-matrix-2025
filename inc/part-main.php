<?php

//
// Главная
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_main extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        
        $this->index_by_place = $this->get_index_by_place();


        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
       

        $this->save( 'courses' );

    }
    

    
    // 
    // Показывает часть 
    // 

    public function the_show()
    {

        if ( $template = locate_template( 'mr-part-main.php' ) ) {
            
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-main.php', false );

        }
    
    }
    
    
    
    // 
    // Показывает список competencies
    // 
    
    public function get_competencies()
    {
        global $tree;
        global $mr;

        $out = '';

        // p( $tree['content']['competencies']['clean'] );
        
        $out .= '<div class="col p-0 mb-6 mt-3">';
        $out .= '<table border="1"><tr><th>Паспорта и программы формирования компетенций</th></tr><tr><td>';
        $out .= '<div class="p-1 pb-3">';
        
        foreach ( $tree['content']['competencies']['clean'] as $name => $data ) {
            
            $out .= '<span class="d-inline-block m-0 mb-2 p-0" style="width: 110px;">';
            
            $s = '<span class="mr-btn mr-gray rounded p-1 pt-2 pb-2 m-0"><i class="fa-regular fa-file-lines fa-xl" aria-hidden="true"></i></span>';
            $s .= '<span class="ml-1"><small><b>' . $name . '</b></small></span>';   
            
            $out .= ( ! empty( $tree['templates']['passport']['data']['path'] ) && $mr->user_can(2) ) ? '<a href="?download=passport&comp=' . $name . '">' . $s . '</a>' : $s;

            // $out .= '<span class="mr-btn mr-gray rounded p-1 pt-2 pb-2 m-0"><i class="fa-regular fa-file-lines fa-xl" aria-hidden="true"></i></span>';
            // $out .= '<span class="ml-1"><small><b>' . $name . '</b></small></span>';       
            // $out .= '</a>';       
            
            $out .= '</span>';
            
        }

        $out .= '</div>';
        $out .= '</tb></tr></table>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_competencies', $out );
    }
    
    

    
    
    // 
    // Показывает список дисциплины
    // 
    
    public function get_main()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'courses' );
        
        $arr = $this->get_courses_arr(); 
        // return '@2';
        
        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $this->get_table_html( $arr );       
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_list_courses', $out );
    }
    
    



  
    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        global $tree;
        global $mr;
        
        $a = $tree['content']['courses']['complete'];

        // $making = ( in_array( $courses_arr[$key]['courses'][$key2]['unit'], array( 'ЭК', 'ЗЧ', 'ЗЧО', 'К', 'КР', 'КРП', 'ГИА' ) )  ) ? false : true;
        $course = ( isset( $a[$key2]['course_id'] ) ) ? $a[$key2]['course_id'] : $key2;


        $text = '<div class="text-center"><small>' . $courses_arr[$key]['courses'][$key2]['unit'] . '</small></div>';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        // Файлы 
        foreach ( $this->index_by_place as $k => $item ) {

            if ( ! preg_match( '/^b.*/', $k ) ) continue;
           
            $text = '';

            foreach ( $item as $i ) {

                if ( ! in_array( $courses_arr[$key]['courses'][$key2]['unit'], array_map( 'trim', explode( ',', $i['param'] ) ) ) ) continue;

                $text = '<span class="mr-btn mr-gray rounded p-0 pl-1 pr-1 m-0"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>';
                if ( ! empty( $i['file'] ) && $mr->user_can(2) ) $text = '<a href="?download=course-d-' . $i['slug'] . '&course=' . $course . '">' . $text . '</a>';

                break;

            }


            $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        }


        return $arr;
    }
    
    
    public function filter_thead_col( $arr )
    {

        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );

        foreach ( $this->index_by_place as $k => $item ) {

            if ( ! preg_match( '/^b.*/', $k ) ) continue;

            $text = '<span title="' . $item[0]['name'] . '">' . mb_substr( $item[0]['name'], 0, 1 ) . '</span>';
            $arr[] = $this->add_to_col( $text, array('elem' => 'th' ) );

        }

        return $arr;
    }



    public function filter_tbody_colspan( $n )
    {
        return $n + count( $this->index_by_place ) + 1;
    }



    private function get_index_by_place()
    {
        global $tree;

        $index = array();

        // p($tree['param']['templates']['data_att']);

        foreach( $tree['param']['templates']['data_att'] as $item ) {

            if ( isset( $item['att'][1] ) && in_array( $item['att'][1], $this->templates_place ) ) {

                $index[$item['att'][1]][] = array(

                    'name' => $item['name'],
                    'slug' => $item['att'][0],
                    'param' => ( ! empty( $item['att'][2] ) ) ? $item['att'][2] : '',
                    'file' => ( isset( $tree['templates'][$item['att'][0]]['data']['path'] ) ) ? $tree['templates'][$item['att'][0]]['data']['path'] : '',

                );

            }

        };

        // p($index);

        return $index;
    }


    private $index_by_place = array();

}

?>