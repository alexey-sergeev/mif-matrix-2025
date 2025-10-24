<?php

//
// Матрица компетенций
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_matrix extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col' ), 10, 4 );
        add_filter( 'mif-mr-tbody-class-tr', array( $this, 'filter_tbody_class_tr' ), 10, 2 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan' ), 10 );
        add_filter( 'mif-mr-tbody-section-row', array( $this, 'filter_tbody_section_row' ), 10, 3 );
        add_filter( 'mif-mr-tbody-end-row', array( $this, 'filter_tbody_end_row' ), 10, 2 );
        
        add_filter( 'mif-mr-thead-row', array( $this, 'filter_thead_row' ), 10, 2 );

        $this->save( 'matrix' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-matrix.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-matrix.php', false );

        }
    }
    
    
    
    
    // 
    // Показывает матрицу компетенций
    // 
    
    public function get_matrix()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'matrix' );
        
        $arr = $this->get_courses_arr();    
        
        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        $out .= $this->get_table_html( $arr );
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_matrix', $out );
    }
    
    
   
    
    //
    //
    //

    public function filter_tbody_end_row( $arr, $courses_arr )
    {
        $matrix_arr = $this->get_matrix_arr();
        $cmp = $this->get_cmp( $matrix_arr );

        $arr2 = array();

        $arr2[] = $this->add_to_col( '', array( 'elem' => 'th' ) );
        $arr2[] = $this->add_to_col( 'ИТОГО:', array( 'elem' => 'th' ) );
        
        $count_arr = array();

        // p($key);
        // p( $courses_arr[$key]['courses'] );
        foreach ( (array) $courses_arr as $key => $item )
            foreach ( (array) $item['courses'] as $key2 => $item2 ) {

                foreach ( $cmp as $c ) 
                    if ( in_array( $c, $matrix_arr[$key2] ) ) {
                        
                        if ( ! isset( $count_arr[$c] ) ) $count_arr[$c] = 0;
                        $count_arr[$c]++;
                        
                        // p('@');
                        // p($key2);
                        // p($matrix_arr[$key2]);
                        // p($c);
                        
                        // break;
                    }
                    
            }
                
            // p($count_arr);        
            
        foreach ( $cmp as $c ) {

            $text = ( isset( $count_arr[$c] ) ) ? $count_arr[$c] : ''; 
            $class = ( ! empty( $text ) ) ? 'text-center mr-green': '';

            $arr2[] = $this->add_to_col( $text, array( 'elem' => 'th', 'class' => $class ) );

        } 
        
        
        $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );

        return $arr;
    }

    
    
   
    
    //
    //
    //

    public function filter_tbody_section_row( $arr, $key, $courses_arr )
    {
        $matrix_arr = $this->get_matrix_arr();
        $cmp = $this->get_cmp( $matrix_arr );

        $arr2 = array();

        $arr2[] = $this->add_to_col( '', array( 'elem' => 'th' ) );
        $arr2[] = $this->add_to_col( 'итого по разделу:', array( 'elem' => 'th' ) );
        
        $count_arr = array();

        // p($key);
        // p( $courses_arr[$key]['courses'] );
        foreach ( (array) $courses_arr[$key]['courses'] as $key2 => $item2 ) {

            foreach ( $cmp as $c ) 
                if ( in_array( $c, $matrix_arr[$key2] ) ) {
                    
                    if ( ! isset( $count_arr[$c] ) ) $count_arr[$c] = 0;
                    $count_arr[$c]++;
                    
                    // p('@');
                    // p($key2);
                    // p($matrix_arr[$key2]);
                    // p($c);
                    
                    // break;
                }
                
        }
            
            // p($count_arr);        
            
        foreach ( $cmp as $c ) {

            $text = ( isset( $count_arr[$c] ) ) ? $count_arr[$c] : ''; 
            $class = ( ! empty( $text ) ) ? 'text-center mr-green': '';

            $arr2[] = $this->add_to_col( $text, array( 'elem' => 'th', 'class' => $class ) );

        } 
        
        
        $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );

        return $arr;
    }





    public function filter_tbody_class_tr( $class, $key2 )
    {
        $matrix_arr = $this->get_matrix_arr();    
        $cmp = $this->get_cmp( $matrix_arr );

        $arr = array();
        if ( ! empty( $class ) ) $arr[] = $class;

        foreach ( $cmp as $c ) {

            $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
            if ( in_array( $c, $arr2 ) ) $arr[] = $c;

        }

        return implode( ' ', $arr );
    }




    public function filter_tbody_colspan( $n )
    {
        $matrix_arr = $this->get_matrix_arr();    
        $cmp = $this->get_cmp( $matrix_arr );
        
        $nn = ( ! empty( $cmp ) ) ? count($cmp) : 10;

        return $n + $nn;
    }




    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        $matrix_arr = $this->get_matrix_arr();    
        $cmp = $this->get_cmp( $matrix_arr );
        
        if ( ! empty( $cmp ) ) {

            foreach ( $cmp as $c ) {

                $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
                $text = ( in_array( $c, $arr2 ) ) ? '1': '';
                $class = ( ! empty( $text ) ) ? 'cmp on': 'cmp';
                $title = ( ! empty( $text ) ) ? $c : '';

                $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => $class, 'title' => $title) );

            }
        
        } else {

           for ( $i = 0; $i < 10; $i++ ) $arr[] = $this->add_to_col( '', array( 'elem' => 'td', 'class' => 'cmp' ) );  

        }

        return $arr;
    }



    public function filter_thead_row( $arr, $courses_arr )
    {
        $arr = array();

        // global $tree;
        // $matrix_arr = $tree['content']['matrix']['data'];
        $matrix_arr = $this->get_matrix_arr();    
        $cmp = $this->get_cmp( $matrix_arr );

        $index = array();
        
        if ( ! empty( $cmp ) ) {

            foreach ( $cmp as $item ) {

                $data = explode( '-', $item );
                $index[$data[0]][] = $data[1];
                $data_cmp[$data[0]][] = $item;

            }

            $arr2 = array();
            $arr2[] = $this->add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
            $arr2[] = $this->add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
            
            foreach ( $index as $key => $numerics ) {
                $c = count( $numerics );
                $arr2[] = $this->add_to_col( $key, array( 'elem' => 'th', 'colspan' => $c ) ); 
            }
            
            $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
            
            $arr2 = array();
            
            foreach ( $index as $key => $numerics )
                foreach ( $numerics as $key2 => $item ) 
            $arr2[] = $this->add_to_col( $item, array( 'elem' => 'th', 'class' => 'selectable', 'data-cmp' => $data_cmp[$key][$key2] ) ); 
        
        $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
        
    } else {

        $arr2 = array();

        $arr2[] = $this->add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
        $arr2[] = $this->add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
        $arr2[] = $this->add_to_col( 'Компетенции не определены', array( 'elem' => 'th', 'colspan' => 10 ) ); 
        
        $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
        
        
        $arr2 = array();

        for ( $i = 1; $i <= 10; $i++ ) $arr2[] = $this->add_to_col( $i, array( 'elem' => 'th' ) ); 
        
        $arr[] = $this->add_to_row( $arr2, array( 'elem' => 'tr' ) );
        
    }
    
    return $arr;
}
    
    
    // 
    // Функция возвращает все возможные компетенции
    // 

    private function get_cmp( $matrix_arr )
    {
        $arr = array();
        foreach ( $matrix_arr as $item ) $arr = array_merge( $arr, $item );
        
        $c = new cmp();
        $arr2 = $c->get_cmp( $arr, 'arr' );
    
        return $arr2;
    }

}

?>