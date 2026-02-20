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


class mif_mr_xlsx  {

    private $blank = '';

    // function __construct( $blank = '' )
    // {
    //     $this->blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
    //     if ( $blank ) $this->blank = $blank;        

    // }

    function __construct( $path = NULL )
    {
        if ( empty( $path ) ) $path = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

        $this->spreadsheet = IOFactory::load( $path );
        $this->sheet = $this->spreadsheet->getActiveSheet();


        // $this->scheme = apply_filters( 'scheme-data-courses', array() );
        // add_filter( 'scheme-data-courses', array( $this, 'scheme_data_courses'), 10 );



        // $cellValue = $spreadsheet->getActiveSheet()->getCell('C4')->getValue();
        // $cellValue = $this->sheet->getCell('C4')->getValue();
        // p($cellValue);
        // p($this->sheet);

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

    function make_xlsx_form_arr( $arr = array(), $blank = '', $cell = 'A1' )
    {
        if ( empty( $blank ) ) $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

        $spreadsheet = IOFactory::load( $blank );
        $sheet = $spreadsheet->getActiveSheet();

        // $sheet->setCellValue( 'A1', 'Hello World !' );
        $sheet->fromArray( $arr, '', $cell, true );
       
        // $upload_dir = (object) wp_upload_dir();
        // $file = trailingslashit( $upload_dir->path ) . md5( serialize( $arr ) . $cell ) . '.xlsx';
        
        $file = mif_mr_download::get_path_tmp( 'xlsx' );

        $writer = new Xlsx( $spreadsheet );
        $writer->save( $file );

        return $file;
    }


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





    //
    // Получить из файла данные
    // 
    // 

    function get_courses( $path )
    {
        $arr = $this->get( $path );

        p($arr);

        return $arr;
    }


    private $spreadsheet;
    private $sheet;

}

?>