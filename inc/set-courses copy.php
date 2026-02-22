<?php

//
// Настройки дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_courses extends mif_mr_set_core {
    
    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );

        $this->save( 'set-courses' );

    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-courses.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-courses.php', false );
            
        }
    }
    
    


    //
    // Показать cписок дисциплин
    //
    
    public function show_set_courses()
    {
        global $tree;
        
        $arr = $tree['content']['courses']['data'];
        $m = new modules();
        $arr = $m->get_courses_tree( $arr );

        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
                
            $out .= $this->companion_edit( 'set-courses' );
            
        } else {
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Дисциплины в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';
            
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0 mb-3">';
            $out .= mif_mr_companion_core::get_show_all();
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= $this->get_table_html( $arr );       
            $out .= '</div>';
            $out .= '</div>';
            
        }
        
        
        
        return apply_filters( 'mif_mr_show_set_courses', $out );
    }
    
    
    

    //
    //
    //

    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        global $tree;
        
        $i = $tree['content']['courses']['index'][$key2];
        
        if ( empty( $i['course_id'] ) ) {
            
            $text = '';
            $text .= '<span class="mr-red text-danger p-0 rounded"><i class="fa-solid fa-xmark fa-sm"></i></span> ';
            $text .= $arr[1]['text'];
            $arr[1]['text'] = $text;

            $arr[] = $this->add_to_col( '', array( 'elem' => 'td' ) );
            $arr[] = $this->add_to_col( '', array( 'elem' => 'td' ) );
            
        } else {
            
            
            // Col 2
            
            $selection_method = ( $i['auto'] ) ? 'автоматически' : 'ручной';
            $up = ' d-none';
            $down = '';

            $text = '';

            $text .= '<span>';
            $text .= '<div class="container">';
            $text .= '<div class="row">';
            $text .= '<div class="col-10 p-0">';
            $text .= '<span class="mr-green text-success p-0 rounded"><i class="fa-solid fa-check fa-sm"></i></span> ';
            $text .= $arr[1]['text'];
            $text .= '</div>';
            $text .= '<div class="col-2 p-0 text-end">';
            $text .= '<a href="#" class="roll-up' . $up . '"><i class="fa-solid fa-angle-up"></i></a>';
            $text .= '<a href="#" class="roll-down' . $down . '"><i class="fa-solid fa-chevron-down"></i></a>';
            $text .= '</div>';
            $text .= '</div>';
            $text .= '</div>';

            $text .= '<div class="coll" style="display: none;">';
            $text .= '<div class="p-3 pt-5">';
            $text .= '<div class="mr-gray p-3 rounded">';
           
            $text .= '<p class="mb-2">Метод выбора: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $selection_method . '</span></p>';
            
            if ( ! empty( $i['name_old'] ) && $i['name_old'] != $i['name'] ) 
                $text .= '<p class="mb-2">Старое название: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['name_old'] . '</span></p>';
           
            if ( ! empty( $i['course_id'] ) ) 
                $text .= '<p class="mb-2">Идентификатор дисциплины: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['course_id'] . '</span></p>';
           
            if ( ! empty( $i['from_id'] ) ) {
                $text .= '<p class="mb-2">Идентификатор ОПОП: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['from_id'] . '</span></p>';
                $text .= '<p class="mb-2">Название ОПОП: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . 
                        $this->mb_substr( get_the_title( $i['from_id'] ), 50 ) . '</span></p>';
            }

            $text .= '</div>';
            $text .= '</div>';
            $text .= '</div>';
            $text .= '</span>';
            
            $arr[1]['text'] = $text;
            

            // Col 3
            
            $text = '';

            $text .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $i['course_id'] . '">';
            $text .= $i['course_id'];
            $text .= '</a>';
            
            $arr[] = $this->add_to_col( $text, array( 'elem' => 'td' ) );
            
            
            // Col 4
            
            $text = '';
            
            if ( $i['auto'] ) $text .= '<span class="hint" title="Автоматически">А</span> ';
            if ( count( $i['course_id_all'] ) > 1 ) $text .= '<span class="hint" title="Есть другие дисциплины">М</span> ';
            if ( ! empty( $i['name_old'] ) && $i['name_old'] != $i['name'] ) $text .= '<span class="hint" title="Поменяли название">П</span> ';
            if ( $i['from_id'] != mif_mr_opop_core::get_opop_id() ) $text .= '<span class="hint" title="По наследованию">Н</span> ';
            
            $arr[] = $this->add_to_col( $text, array( 'elem' => 'td' ) );

        }

        return $arr;
    }



    public function filter_thead_col( $arr )
    {
        $arr[] = $this->add_to_col( 'id', array( 'elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Прим.', array( 'elem' => 'th' ) );
        return $arr;
    }


    public function filter_tbody_colspan( $n )
    {
        return $n + 2;
    }

}

?>