<?php

//
// Создание xlsx-файлов
// 
//


defined( 'ABSPATH' ) || exit;


// 
// Требуется установка библиотеки
// 
// Команда для установки:
// composer require phpoffice/phpspreadsheet
// 
// Документация: https://phpspreadsheet.readthedocs.io/en/latest/
// 

require dirname( __FILE__ ) . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class mif_mr_xlsx  {

    private $blank = '';


    function __construct( $path = NULL )
    {   
        if ( ! is_file( $path ) ) $path = '';
        if ( empty( $path ) ) $path = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

        $this->spreadsheet = IOFactory::load( $path );
        $this->sheet = $this->spreadsheet->getActiveSheet();

    }

    // function __construct()
    // {


    // }


    //
    // Получить готовый xlsx-файл
    // 
    //  $arr - данные, которые надо вставить в таблицу
    //  $cell - левая верхняя ячейка, откуда начинать заполнять
    // 

    function make_xlsx_form_arr( $arr = array(), $cell = 'A1', $arr_att = array(), $end = 0 )
    // function make_xlsx_form_arr( $arr = array(), $blank = '', $cell = 'A1' )
    {
        // if ( empty( $blank ) ) $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

        // $spreadsheet = IOFactory::load( $blank );
        // $sheet = $spreadsheet->getActiveSheet();

        // $sheet->setCellValue( 'A1', 'Hello World !' );
        $this->sheet->fromArray( $arr, '', $cell, true );
       
        // $upload_dir = (object) wp_upload_dir();
        // $file = trailingslashit( $upload_dir->path ) . md5( serialize( $arr ) . $cell ) . '.xlsx';
        
        // p($this->sheet->getCell( 'B7' )->getRow());
        //         global $tree;
        // p($tree);

        $e = Coordinate::stringFromColumnIndex( $end );

        foreach ( $arr_att as $k => $i ) {

            // if ( isset( $i['bg-color'] ) ) $this->sheet->getStyle( 'A' . ( $k + 1 ) )->getFill()->setFillType( Fill::FILL_SOLID )->getStartColor()->setRGB( $i['bg-color'] );
            $r = $k + 1;
            // p($e);
            if ( isset( $i['bg-color'] ) ) $this->sheet->getStyle( 'A' . $r . ':' . $e . $r )->getFill()->setFillType( Fill::FILL_SOLID )->getStartColor()->setRGB( $i['bg-color'] );
            if ( isset( $i['bold'] ) ) $this->sheet->getStyle( 'A' . $r . ':' . $e . $r )->getFont()->setBold( true );
            if ( isset( $i['size'] ) ) $this->sheet->getStyle( 'A' . $r . ':' . $e . $r )->getFont()->setSize( $i['size'] );
            if ( isset( $i['merge-end'] ) ) $this->sheet->mergeCells( $i['merge-end'] . $r . ':' . $e . $r );
            if ( isset( $i['height'] ) ) $this->sheet->getRowDimension( $r )->setRowHeight( $i['height'] );

        }

        $set_row_auto = apply_filters( 'mif_mr_xlsx_set_row_auto', array() );
        foreach ( $set_row_auto as $i ) $this->sheet->getRowDimension( $i )->setRowHeight( -1 );
        // p($set_row_auto);
        // p($arr);

        $set_row_visible = apply_filters( 'mif_mr_xlsx_set_row_visible', array() );
        foreach ( $set_row_visible as $i ) $this->sheet->getRowDimension( $i )->setVisible( false );
        // foreach ( $set_row_visible as $i ) $this->sheet->removeRow($i);
        
        $set_col_visible = apply_filters( 'mif_mr_xlsx_set_col_visible', array() );
        foreach ( $set_col_visible as $i ) $this->sheet->getColumnDimensionByColumn( $i )->setVisible( false );

        $set_borders_array = apply_filters( 'mif_mr_xlsx_set_borders_array', array() );
        // foreach ( $set_col_visible as $i ) $this->sheet->getColumnDimensionByColumn( $i )->setVisible( false );

        if ( ! empty( $set_borders_array ) ) {

            $this->sheet->getStyle( 
                                Coordinate::stringFromColumnIndex( $set_borders_array[0] ) .
                                $set_borders_array[1] .
                                ':' .
                                Coordinate::stringFromColumnIndex( $set_borders_array[2] ) .
                                $set_borders_array[3]
                            )->applyFromArray( [ 'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000'], ], ], ] );
            $this->sheet->getStyle( 
                                Coordinate::stringFromColumnIndex( $set_borders_array[2] + 2 ) .
                                $set_borders_array[1] .
                                ':' .
                                Coordinate::stringFromColumnIndex( $set_borders_array[2] + 3 ) .
                                $set_borders_array[3]
                            )->applyFromArray( [ 'borders' => [ 'left' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000'], ], ], ] );
            $this->sheet->getStyle( 
                                Coordinate::stringFromColumnIndex( $set_borders_array[0] ) .
                                $set_borders_array[1] + 1 
                            )->applyFromArray( array() );
        
        }

        $file = mif_mr_download::get_path_tmp( 'xlsx' );

        $writer = new Xlsx( $this->spreadsheet );
        $writer->save( $file );

        return $file;
    }

    // //
    // // Получить готовый xlsx-файл
    // // 
    // //  $arr - данные, которые надо вставить в таблицу
    // //  $cell - левая верхняя ячейка, откуда начинать заполнять
    // // 

    // function make_xlsx_form_arr( $arr = array(), $blank = '', $cell = 'A1' )
    // {
    //     if ( empty( $blank ) ) $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

    //     $spreadsheet = IOFactory::load( $blank );
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // $sheet->setCellValue( 'A1', 'Hello World !' );
    //     $sheet->fromArray( $arr, '', $cell, true );
       
    //     // $upload_dir = (object) wp_upload_dir();
    //     // $file = trailingslashit( $upload_dir->path ) . md5( serialize( $arr ) . $cell ) . '.xlsx';
        
    //     $file = mif_mr_download::get_path_tmp( 'xlsx' );

    //     $writer = new Xlsx( $spreadsheet );
    //     $writer->save( $file );

    //     return $file;
    // }


    //
    // Получить готовый xlsx-файл
    // 
    // 

    function make_xlsx()
    {
   
        $file = mif_mr_download::get_path_tmp( 'xlsx' );

        $writer = new Xlsx( $this->spreadsheet );
        $writer->save( $file );


        return $file;
    }



    //
    // Получить из файла данные
    // 
    // 

    function get_arr()
    {
        $arr = array();
        $arr = $this->sheet->ToArray(null, true, true, true);
        return $arr;
    }



    //
    // Получить из файла данные
    // 
    // 

    function get( $cell = 'A1' )
    {
        $cell_value = $this->sheet->getCell($cell)->getValue();
        if ( empty( $cell_value ) ) $cell_value = '';
        return strim( $cell_value );
    }




    //
    // Записать данные
    // 
    // 

    function set( $cell = 'A1', $data = '' )
    {
        $this->sheet->setCellValue( $cell, $data );
        $this->sheet->getRowDimension( $this->sheet->getCell( $cell )->getRow() )->setRowHeight( -1 );
        // $this->sheet->getRowDimension( $this->sheet->getCell( $cell )->getRow() )->setRowHeight( mb_strlen( $data ) * 16 / 48 );
    }
    
    
    
    //
    // Коррекция высоты
    // 
    
    function сorrection_height()
    {
        $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['name'][0] )->getRow() )->setRowHeight( 30 );
        $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['cmp'][0] )->getRow() )->setRowHeight( 25 );
        $this->sheet->getRowDimension( $this->sheet->getCell( $this->scheme['hours'][0] )->getRow() )->setRowHeight( 25 );
    }



    private $spreadsheet;
    private $sheet;

}

?>