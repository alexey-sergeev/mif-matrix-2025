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
        add_filter( 'mif_mr_xlsx_set_row_visible', array( $this, 'set_row_visible'), 10 );
        add_filter( 'mif_mr_xlsx_set_col_visible', array( $this, 'set_col_visible'), 10 );

    }


   
    // 
    // Перевести массив в файл xlsx  
    // 
    
    public function arr_to_xlsx()
    // public function arr_to_xlsx( $arr = array() )
    {
        global $tree;

        $courses_arr = $tree['content']['courses']['data'];
        $matrix_arr = $tree['content']['matrix']['data'];
        $matrix_stat = $tree['content']['matrix']['stat']['modules']; // !!!
        $matrix_stat_all = $tree['content']['matrix']['stat']['all']; // !!!
        
        $cmp_raw = array_keys( $tree['content']['competencies']['data'] );
        
        // $cmp = array();
        $index = array();

        foreach ( $cmp_raw as $c ) $index[  explode( '-', $c )[0] ][] = $c;
        
        foreach ( $index as $a ) {
        
            $b = array();
            for ( $i = 0; $i < $this->part_len - count( $a ); $i++ ) $b[] = '';
            
            $this->cmp = array_merge( $this->cmp, $a, $b );
            // $cmp = array_merge( $cmp, $b );


        }
            
        $cmp = $this->cmp;


        // p($index);
        // p($cmp);
        

        
        $arr = array();

        // $arr[0][0] = 'A1';
        // $arr[0][1] = 'A2';
        // $arr[1][0] = 'B1';
        // $arr[1][1] = 'B2';

        // $m = new modules();
        // $courses_arr = $m->get_courses_tree( $courses_arr );

        // p($courses_arr);
        // p($matrix_arr);


        // Шапка в таблице
        $arr[] = $this->get_row( 'Матрица компетенций' );
        
        $text = ( isset( $tree['content']['attributes']['data']['Титул'] ) ) ? $tree['content']['attributes']['data']['Титул'] : $tree['main']['title'];
        $arr[] = $this->get_row( $text );

        $text = ( isset( $tree['content']['attributes']['data']['Форма обучения'] ) ) ? $tree['content']['attributes']['data']['Форма обучения'] . ' форма обучения' : '';
        $arr[] = $this->get_row( $text );
        
        $arr[] = $this->get_row();
        

        
        
        // Шапка в таблице
        
        $a = array();
        foreach ( $cmp as $c ) $a[] = ( ! empty( explode( '-', $c )[0] ) ) ? explode( '-', $c )[0] : '';
        
        $arr[] = $this->get_row( '', '', $a );
        
        
        // Шапка (вторая строчка) в таблице
        
        $a = array();
        foreach ( $cmp as $c ) $a[] = ( ! empty( explode( '-', $c )[1] ) ) ? explode( '-', $c )[1] : '';
        
        $arr[] = $this->get_row( '', '', $a );
        

        foreach ( $courses_arr as $key => $item ) {
            
            // Разделы
            
            $code = ( isset( $item['att']['code'] ) ) ? $item['att']['code'] : '';
            $text = ( $key == '__default__' ) ? mif_mr_opop_core::get_caption( 'default' ) : $key;
            
            $arr[] = $this->get_row( $code, $text );
            
            
            // Дисциплины
            
            $n = 0;
            $row = array();
            for ( $i = 0; $i < $this->row_len; $i++ ) $row[] = array();

            foreach ( $item['courses'] as $key2 => $item2 ) {
                
                $code = ( isset( $item2['code'] ) ) ? $item2['code'] : '';
                
                $a = array();
                foreach ( $cmp as $c ) {
                    $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
                    $a[] = ( in_array( $c, $arr2 ) ) ? '1': '';
                } 

                $row[$n] = $this->get_row( $code, $key2, $a );
                // $arr[] = $this->get_row( $code, $key2, $a );
                // $arr[] = $this->get_empty( $a );

                $n++;

            }

            $arr = array_merge( $arr, $row );
            // $this->row = array_merge( $this->row, $row );


            // p($row);
            // p($arr);
            
            // Статистика по разделам
        
            $a = array();
            foreach ( $cmp as $c ) $a[] = ( isset( $matrix_stat[$key][$c] ) ) ? $matrix_stat[$key][$c] : '';
            
            $arr[] = $this->get_row( '', mif_mr_opop_core::get_caption( 'total' ), $a );
            // $arr[] = $this->get_empty( $a );

        }

        for ( $i = (int) array_key_last( $arr ); $i < ( $this->row_len + 2 ) * $this->row_n + $this->row_y - 2; $i++ ) $arr[] = array();
        
        // p($arr);
        // Статистика по ОПОП

        $a = array();
        foreach ( $cmp as $c ) $a[] = ( isset( $matrix_stat_all[$c] ) ) ? $matrix_stat_all[$c] : '';

        $arr[] = $this->get_row( '', mif_mr_opop_core::get_caption( 'total_all' ), $a );
        // $arr[] = $this->get_empty( $a );

        // p($courses_arr);


        

        // Согласовано

        $arr[] = $this->get_row( 'Согласовано:' );
        $arr[] = $this->get_row( 'Начальник учебного управления: __________________/__________________ ' );
        $arr[] = $this->get_row( 'Директор института: __________________/__________________' );


        $this->table = $arr;
        
        $path = $this->make_xlsx_form_arr( $arr, 'A1' );

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
        for ( $i = $this->row_y;  $i < ( $this->row_len + 2 ) * $this->row_n;  $i++ ) $b[] = $i;
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
    
    private $row_n = 16;
    private $row_len = 32;
    private $row_y = 7;
    
    
    // private $n_table = 200;
    
    
    private $cmp = array();
    private $table = array();


}

?>