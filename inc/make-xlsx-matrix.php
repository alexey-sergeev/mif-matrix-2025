<?php

//
// Обработка матрицы компетенций
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_xlsx_matrix extends mif_mr_xlsx { 

    // private $blank = '';

    function __construct( $path = NULL )
    {
        
        parent::__construct( $path );

        add_filter( 'mif_mr_xlsx_set_row_auto', array( $this, 'set_row_auto'), 10 );
        // add_filter( 'mif_mr_xlsx_set_row_visible', array( $this, 'set_row_visible'), 10 );
        add_filter( 'mif_mr_xlsx_set_col_visible', array( $this, 'set_col_visible' ), 10 );
        add_filter( 'mif_mr_xlsx_set_borders_array', array( $this, 'set_borders_array' ), 10 );

    }


   
    // 
    // Перевести массив в файл xlsx  
    // 
    
    public function arr_to_xlsx()
    {
        global $tree;

        $is_by_courses = ( isset( $_REQUEST['key'] ) && $_REQUEST['key'] == 'courses'  ) ? true : false;

        $courses_arr = $tree['content']['courses']['data'];
        $matrix_arr = $tree['content']['matrix']['data'];
        $matrix_stat_all = $tree['content']['matrix']['stat']['all']; 
        
        $matrix_stat = ( $is_by_courses ) ? $tree['content']['matrix']['stat']['courses'] : $tree['content']['matrix']['stat']['modules'];
        

        if ( $is_by_courses ) {

            $m = new modules();
            $courses_arr = $m->get_courses_tree( $courses_arr );

        }

        $cmp_raw = array_keys( $tree['content']['competencies']['data'] );
        
        $index = array();

        foreach ( $cmp_raw as $c ) $index[  explode( '-', $c )[0] ][] = $c;
        
        foreach ( $index as $a ) {
        
            $b = array();
            for ( $i = 0; $i < $this->part_len - count( $a ); $i++ ) $b[] = '';
            
            $this->cmp = array_merge( $this->cmp, $a, $b );

        }
            
        $cmp = $this->cmp;

        $arr = array();
        $arr_att = array();



        // Утверждаю
        
        $arr[] = $this->get_row( 'Утверждаю:' );
        $arr_att[] = array( 'height' => 48, 'bold' => 'bold' );
        
        $approving = apply_filters( 'mif_mr_xlsx_matrix_approving', 'Проректор по учебной работе' );

        $arr[] = $this->get_row( $approving );
        $arr_att[] = array( 'height' => 32 );

        $arr[] = $this->get_row( '____________________/____________________' );
        $arr_att[] = array( 'height' => 32 );



        // Шапка в таблице

        $arr[] = $this->get_row( 'Матрица компетенций' );
        $arr_att[] = array( 'height' => 48, 'bold' => 'bold' );
        
        $text = ( isset( $tree['content']['attributes']['data']['Титул'] ) ) ? $tree['content']['attributes']['data']['Титул'] : $tree['main']['title'];
        $arr[] = $this->get_row( $text );
        $arr_att[] = array( 'height' => 24, 'bold' => 'bold' );

        $text = ( isset( $tree['content']['attributes']['data']['Форма обучения'] ) ) ? $tree['content']['attributes']['data']['Форма обучения'] . ' форма обучения' : '';
        $arr[] = $this->get_row( $text );
        $arr_att[] = array( 'height' => 24, 'bold' => 'bold' );
        
        $arr[] = $this->get_row();
        $arr_att[] = array( 'height' => 24, 'bold' => 'bold' );

        
        
        // Шапка в таблице
        
        $a = array();
        foreach ( $cmp as $c ) $a[] = ( ! empty( explode( '-', $c )[0] ) ) ? explode( '-', $c )[0] : '';
        
        $arr[] = $this->get_row( '', '', $a );
        $arr_att[] = '';
        
        
        // Шапка (вторая строчка) в таблице
        
        $a = array();
        foreach ( $cmp as $c ) $a[] = ( ! empty( explode( '-', $c )[1] ) ) ? explode( '-', $c )[1] : '';
        
        $arr[] = $this->get_row( '', '', $a );
        $arr_att[] = '';
        
        
        foreach ( $courses_arr as $key => $item ) {
            
            // Разделы

            $letter = 'B';            
            $code = ( isset( $item['att']['code'] ) ) ? $item['att']['code'] : '';
           
            if ( isset( $item['att']['plural'] ) ) { 
                $code = $item['att']['plural'];
                $letter = 'A';            
            }
            
            $text = ( $key == '__default__' ) ? mif_mr_opop_core::get_caption( 'default' ) : $key;
            
            $arr[] = $this->get_row( $code, $text );
            $arr_att[] = array( 'bg-color' => 'FFF2F2F2', 'bold' => 'bold', 'merge-end' => $letter );
            $this->table_len++;
            
            // Дисциплины
            
            foreach ( $item['courses'] as $key2 => $item2 ) {
                
                $code = ( isset( $item2['code'] ) ) ? $item2['code'] : '';
                
                $a = array();
                foreach ( $cmp as $c ) {
                    $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
                    $a[] = ( in_array( $c, $arr2 ) ) ? '1': '';
                } 
                
                $arr[] = $this->get_row( $code, $key2, $a );
                $arr_att[] = '';
                $this->table_len++;
                
            }
            
            // Статистика по разделам
            
            $a = array();
            foreach ( $cmp as $c ) $a[] = ( isset( $matrix_stat[$key][$c] ) ) ? $matrix_stat[$key][$c] : '';
            
            $arr[] = $this->get_row( '', mif_mr_opop_core::get_caption( 'total' ), $a );
            $arr_att[] = array( 'bg-color' => 'F2F2F2', 'bold' => 'bold' );
            $this->table_len++;
            
        }
        
        // Статистика по ОПОП
        
        $a = array();
        foreach ( $cmp as $c ) $a[] = ( isset( $matrix_stat_all[$c] ) ) ? $matrix_stat_all[$c] : '';
        
        $arr[] = $this->get_row( '', mif_mr_opop_core::get_caption( 'total_all' ), $a );
        $arr_att[] = array( 'bg-color' => 'F2F2F2', 'bold' => 'bold' );
        $this->table_len++;
        
        // Согласовано
        
        $arr[] = $this->get_row( 'Согласовано:' );
        $arr_att[] = array( 'height' => 48, 'bold' => 'bold', 'size' => 11 );
        
        $agreed = apply_filters( 'mif_mr_xlsx_matrix_agreed', array( 'Начальник учебного управления', 'Директор института' ) );

        foreach ( $agreed as $a ) {

            $arr[] = $this->get_row( $a . ': ____________________/____________________ ' );
            $arr_att[] = array( 'height' => 32, 'size' => 11 );

        }

        $path = $this->make_xlsx_form_arr( $arr, 'A1', $arr_att, $this->part_len * $this->part_n + 2 );

        return $path;
    }



    
    private function get_row( $s = '', $s2 = '', $a = array() )
    {
        // $b = array();
        // if ( ! empty( $a ) ) for ( $i = 0; $i < $this->n_part - count( $a ); $i++ ) $b[] = '';
        // return array_merge( array( $s, $s2 ), $a, $b );
        return array_merge( array( $s, $s2 ), $a );
    }
    
    
    private function get_empty( $a )
    {
        $a = array();
        for ( $i = 0; $i < $this->part_len; $i++ ) $a[] = '';
        return $a;
    }
  
    

    public function set_row_auto()
    {
        // p($this->row);
        $b = array();
        // for ( $i = $this->row_y;  $i < ( $this->row_len + 2 ) * $this->row_n;  $i++ ) if ( ! empty( $this->table[$i] ) ) $b[] = $i;
        // for ( $i = $this->row_y;  $i < ( $this->row_len + 2 ) * $this->row_n;  $i++ ) $b[] = $i;
        for ( $i = $this->row_y;  $i < $this->table_len + $this->row_y;  $i++ ) $b[] = $i;
        return $b;
    }
  
    

    public function set_row_visible()
    {
        // p($this->row);

        $a = array();
        foreach ( $this->table as $k => $i ) if ( empty( $i ) ) $a[] = $k + 1;
        return $a;
    }

    
    public function set_col_visible()
    {
        // p($this->cmp);
        //         global $tree;
        // p($tree);

        $c = $this->cmp;
        for ( $i = count( $this->cmp );  $i < $this->part_len * $this->part_n;  $i++ ) $c[] = '';

        $a = array();
        foreach ( $c as $k => $i ) if ( empty( $i ) ) $a[] = $k + 3;
        return $a;
    }


    public function set_borders_array()
    {
        return array( 1, $this->row_y, $this->part_len * $this->part_n + 2, $this->row_y + $this->table_len - 1 );
    }
  
    




    // //
    // // Коррекция высоты
    // // 
    
    // private function сorrection_height()
    // {
    //     $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['name'][0] )->getRow() )->setRowHeight( 30 );
    //     $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['cmp'][0] )->getRow() )->setRowHeight( 25 );
    //     $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['hours'][0] )->getRow() )->setRowHeight( 25 );
    // }

    private $part_n = 8;
    private $part_len = 16;
    
    // private $row_n = 16;
    // private $row_len = 32;
    private $row_y = 10;
    
    
    // private $n_table = 200;
    
    
    private $cmp = array();
    private $table = array();
    private $table_len = 0;


}

?>