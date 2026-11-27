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
        
        // $this->index_by_place = $this->get_index_by_place();


        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
        
        // add_filter( 'mif-mr-download-panel-names', array( $this, 'download_panel_names'), 10 );
       

        $this->save( 'courses' );

    }
    

    
    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        global $mr;

        if ( ! $mr->user_can( 1 ) ) {
            
            echo $mr->access_denied();
            return false;

        }

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

        $competencies = $tree['content']['competencies']['data'];

        $out = '';

        // p( $tree['content']['competencies']['clean'] );
        
        $out .= '<div class="col p-0 mb-6 mt-3">';
        $out .= '<table border="1"><tr><th>Паспорта и программы формирования компетенций</th></tr>';
        
        if ( ! empty( $competencies ) ) {

            $out .= '<tr><td>';
            $out .= '<div class="p-1 pb-3">';
            
            foreach ( $competencies as $name => $data ) {
                
                $out .= '<span class="d-inline-block m-0 mb-2 p-0" style="width: 110px;">';
                
                $s = '<span class="mr-btn mr-gray rounded p-1 pt-2 pb-2 m-0"><i class="fa-regular fa-file-lines fa-xl" aria-hidden="true"></i></span>';
                $s .= '<span class="ml-1"><small><b>' . $name . '</b></small></span>';   
                
                $out .= ( ! empty( $tree['content']['templates']['data']['templates']['index_by_slug']['passport'] ) && $mr->user_can(2) ) ? '<a href="?download=passport&comp=' . $name . '">' . $s . '</a>' : $s;
                
                $out .= '</span>';
                
            }
    
            $out .= '</div>';
            $out .= '</tb></tr>';

        }

        $out .= '</table>';
        
        if ( empty( $competencies ) ) $out .= mif_mr_functions::get_callout( 'Нет данных', 'warning' ); 
        
        $out .= '</div>';

        return apply_filters( 'mif_mr_part_get_competencies', $out );
    }
    
    
    
    
    
    // 
    // Показывает Панель загрузки
    // 
    
    public function get_download_panel()
    {
        global $tree;
    
        $names = apply_filters( 'mif-mr-download-panel-names', mif_mr_functions::get_download_panel_names() );
        
        $out = '';
        $s = '';

        // $out .= '<div class="col p-0 mb-6 mt-3">';

        $flags = array();
        foreach ( $names as $key => $item ) $flags[$key] = true;

        if ( empty( $tree['content']['competencies']['data'] ) ) $flags['a'] = false;
        if ( empty( $this->get_courses_arr() ) ) foreach( $flags as $k => $f ) if ( preg_match( '/^b.*/', $k ) ) $flags[$k] = false;

        foreach ( $names as $key => $item ) if ( $flags[$key] ) $s .= $this->get_one_archive( $item, 'docx-' . $key );
            
        // $out .= '</div>';

        if ( ! empty( $s ) ) $out .= '<div class="col p-0 mb-6 mt-3">' . $s . '</div>';

        return apply_filters( 'mif_mr_part_get_download_panel', $out );
    }
        
    


    public function get_download_panel_names()
    {
        return $this->download_panel_names();
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

        $course = ( isset( $a[$key2]['course_id'] ) ) ? $a[$key2]['course_id'] : $key2;

        $text = '<div class="text-center"><small>' . $courses_arr[$key]['courses'][$key2]['unit'] . '</small></div>';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        foreach ( $tree['content']['templates']['data']['templates']['index_by_place'] as $k => $item ) {

            if ( ! preg_match( '/^b.*/', $k ) ) continue;
           
            $text = '';

            if ( ! empty( $item[ $courses_arr[$key]['courses'][$key2]['unit'] ] ) ) {

                $item2 = $item[ $courses_arr[$key]['courses'][$key2]['unit'] ];

                $text = '<span class="mr-btn mr-gray rounded p-0 pl-1 pr-1 m-0"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>';
                if ( ! empty( $item2['path'] ) && $mr->user_can(2) ) $text = '<a href="?download=course-d-' . $item2['slug'] . '&course=' . $course . '">' . $text . '</a>';

            }

            $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        }


        return $arr;
    }
    
    


    public function filter_thead_col( $arr )
    {
        global $tree;

        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );

        foreach ( $tree['content']['templates']['data']['templates']['index_by_place'] as $k => $item ) {

            if ( ! preg_match( '/^b.*/', $k ) ) continue;

            $name = $item[ array_keys( $item )[0] ]['name'];
            // p($item[ array_keys( $item )[0] ]);
            $text = '<span title="' . $name . '">' . mb_substr( $name, 0, 1 ) . '</span>';
            $arr[] = $this->add_to_col( $text, array('elem' => 'th' ) );

        }

        return $arr;
    }



    public function filter_tbody_colspan( $n )
    {
        global $tree;

        $nn = 0;

        foreach ( $tree['content']['templates']['data']['templates']['index_by_place'] as $k => $i ) {

            if ( ! preg_match( '/^b.*/', $k ) ) continue;
            $nn++;

        }

        return $n + $nn + 1;
    }



    // private function get_index_by_place()
    // {
    //     global $tree;

    //     $index = array();

    //     // p($tree['param']['templates']['data_att']);
    //     // p( $tree['content']['templates']['data']['templates']['data']);
    //     // if ( isset( $tree['param']['templates']['data_att'] ) ) {
    //     if ( isset( $tree['content']['templates']['data']['templates']['data'] ) ) {
    
    //         // foreach( $tree['param']['templates']['data_att'] as $item ) {
    //         foreach( $tree['content']['templates']['data']['templates']['data'] as $item ) {
    //             // p($item);
    
    //             if ( isset( $item['att'][1] ) && in_array( $item['att'][1], $this->templates_place ) ) {
    
    //                 $index[$item['att'][1]][] = array(
    
    //                     'name' => $item['name'],
    //                     'slug' => $item['att'][0],
    //                     'param' => ( ! empty( $item['att'][2] ) ) ? $item['att'][2] : '',
    //                     // 'file' => ( isset( $tree['templates'][$item['att'][0]]['data']['path'] ) ) ? $tree['templates'][$item['att'][0]]['data']['path'] : '',
    //                     'file' => ( isset( $item['file']['path'] ) ) ? $item['file']['path'] : '',
    
    //                 );
    
    //             }
    
    //         };

    //     }

        // p($index);
        
    //     return $index;
    // }


    // private $index_by_place = array();

}

?>