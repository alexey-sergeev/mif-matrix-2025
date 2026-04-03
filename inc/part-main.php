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
        
        $a = $tree['content']['courses']['complete'];

        // $making = ( in_array( $courses_arr[$key]['courses'][$key2]['unit'], array( 'ЭК', 'ЗЧ', 'ЗЧО', 'К', 'КР', 'КРП', 'ГИА' ) )  ) ? false : true;
        $course = ( isset( $a[$key2]['course_id'] ) ) ? $a[$key2]['course_id'] : $key2;


        $arr[] = $this->add_to_col( $courses_arr[$key]['courses'][$key2]['unit'], array('elem' => 'td', 'class' => '', 'title' => '') );

        // Файлы 

        foreach ( $this->index_by_place as $item ) {

            $text = '';

            foreach ( $item as $i ) {

                if ( ! in_array( $courses_arr[$key]['courses'][$key2]['unit'], explode( ', ', $i['param'] ) ) ) continue;

                $text = '<a href="?download=course-d-' . $i['slug'] . '&course=' . $course . 
                                        '"><span class="mr-btn mr-gray rounded p-0 pl-1 pr-1 m-0"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span></a>';

                break;

                // p($item);
            }


            $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        }


        return $arr;
    }
    
    
    public function filter_thead_col( $arr )
    {

        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );

        foreach ( $this->index_by_place as $item ) {

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
                    'param' => $item['att'][2],

                );

            }

        };

        // p($index);

        return $index;
    }


    private $index_by_place = array();

}

?>