<?php

//
// Матрица компетенций
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_attributes extends mif_mr_part_companion {
    
    function __construct()
    {

        parent::__construct();
       
        $this->do_save();

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-attributes.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-attributes.php', false );

        }
    }
    
    
    
    
    // 
    // Показывает attributes
    // 
    
    public function get_attributes()
    {
        global $tree;
        $arr = $tree['content']['attributes']['data'];
        $index = ( isset( $tree['param']['attributes']['data'] )) ? $tree['param']['attributes']['data'] : array();
        $title = array( 'Основные атрибуты', 'Прочие атрибуты');

        if ( isset( $_REQUEST['edit'] ) ) {
        
            if ( $_REQUEST['edit'] == 'easy' ) {

                return $this->edit_easy( $title, $arr, $index );

            } else {

                return $this->companion_edit( 'attributes' );

            }

        }
           
        // $arr = $this->get_companion_content( 'attributes' );
        
        // p($arr);
        // p($index);
        // p($_REQUEST);
        
        $out = '';
        // $out .= '<div class="content p-4">';

        $out .= $this->get_attributes_part( $title[0], $arr, $index );
        
        $out .= '<div class="mb-6"></div>';
        
        foreach ( $index as $item ) unset( $arr[$item] );
        if ( ! empty( $arr ) ) $out .= $this->get_attributes_part( $title[1], $arr );


        // $out .= '<div class="row pt-3 pb-3 bg-body-secondary fw-bold text-center border">';
        
        // $out .= '<div class="col">';
        // $out .= 'Основные атрибуты';
        // $out .= '</div>';
        
        // $out .= '</div>';

        // foreach ( $index as $item ) {
            
        //     $out .= '<div class="row bg-light fw-semibold mt-3 pt-2 pb-2">';
            
        //     $out .= '<div class="col-11">';
        //     $out .= $item;
        //     $out .= '</div>';
            
        //     $out .= '<div class="col-1 text-end">';
        //     $out .= '<a href="#"><i class="fa-regular fa-clone"></i></a>';
        //     $out .= '</div>';
            
        //     $out .= '</div>';
            
            
        //     $out .= '<div class="row mt-3">';
            
        //     $out .= '<div class="col-12">';
        //     $out .= ( ! empty( $arr[$item] ) ) ? $arr[$item] : '<i class="mr-yellow p-1 pr-5 pl-5">нет данных</i>';
        //     $out .= '</div>';
            
        //     $out .= '</div>';
           
        // }
        
        // foreach ( $index as $item ) unset( $arr[$item] );
        // if ( ! empty( $arr ) ) 
            

        


        // $out .= '</div>';
        

        // $out .= '@@@@@@';

        // p($arr);
        
        return apply_filters( 'mif_mr_part_get_attributes', $out );
    }
    
    



    // 
    // Показывает attributes part
    // 
    
    public function get_attributes_part( $title, $arr, $index = NULL, $f = false )
    {
        if ( $index === NULL ) foreach ( $arr as $k => $i ) $index[] = $k;
            
        $out = '';

        $out .= '<div class="content p-3 mb-0">';

        $out .= '<div class="row pt-2 pb-2 mb-5 bg-body-secondary fw-semibold text-center border">';
        
        $out .= '<div class="col">';
        $out .= $title;
        $out .= '</div>';
        
        $out .= '</div>';
        
        foreach ( $index as $item ) {
            
            $out .= '<span class="copy-wrapper">';
        
            $out .= '<div class="row bg-light fw-semibold mt-3 pt-2 pb-2">';
            
            $out .= '<div class="col-11">';
            $out .= $item;
            $out .= '</div>';
            
            $out .= '<div class="col-1 text-end">';
            // $out .= '<a href="#"><i class="fa-regular fa-clone"></i></a>';
            $out .= ( ! $f && ! empty( $arr[$item] ) ) ? '<span class="copy-button"><i class="fa-regular fa-clone"></i></span>' : '';
            // $out .= '<span class="copy-button"><i class="fa-regular fa-clone"></i></span>';
            $out .= '</div>';
            
            $out .= '</div>';
            
            
            $out .= '<div class="row mt-3">';
            
            $out .= ( ! $f ) ? '<div class="col-12 copy">' : '<div class="col-12 p-0">';
            $out .= ( ! $f ) ? '' : '<input type="hidden" name="name[]" value="' . $item . '">';
            $out .= ( ! $f ) ? '' : '<textarea name="data[]" class="textarea mr-mh-4">';
            // $out .= ( ! empty( $arr[$item] ) ) ? $arr[$item] : '<i class="mr-yellow p-1 pr-5 pl-5">нет данных</i>';
            $out .= ( ! $f ) ? ( ! empty( $arr[$item] ) ) ? $arr[$item] : '<i class="mr-yellow p-1 pr-5 pl-5">нет данных</i>' : $arr[$item];
            $out .= ( ! $f ) ? '' : '</textarea>';
            $out .= '</div>';
            
            $out .= '</div>';
            
            $out .= '</span>';
            
        }

        $out .= '</div>';

        return $out;
    }

  
    


    //
    //

    public function edit_easy( $title, $arr, $index )
    {
        global $tree;    
    
        $out = '';

        $out .= $this->get_attributes_part( $title[0], $arr, $index, true );
        
        foreach ( $index as $item ) unset( $arr[$item] );
        
        $arr2 = array();
        foreach ( $arr as $k => $i ) $arr2[] = $k . ': ' . $i;
        $data_other = implode( "\n", $arr2 );
        
        if ( ! empty( $arr ) ) {

            $out .= '<div class="mb-6"></div>';

            $out .= '<div class="content p-3 mb-0">';

            $out .= '<div class="row pt-3 pb-3 mb-5 bg-body-secondary fw-bold text-center border">';
            
            $out .= '<div class="col">';
            $out .= $title[1];
            $out .= '</div>';
            
            $out .= '</div>';

            $out .= '<div class="row mt-3">';
            
            $out .= '<div class="col-12 p-0">';
            $out .= '<textarea name="data_other" class="textarea mr-mh-16">';
            $out .= $data_other;
            $out .= '</textarea>';
            $out .= '</div>';
            
            $out .= '</div>';

            $out .= '</div>';


        } 
        
        
        return apply_filters( 'mif_mr_edit_easy', $out );
    }
        

    


    private function do_save() 
    {
        if ( isset( $_REQUEST['data'] ) ) {

            $arr = array();
            foreach ( (array) $_REQUEST['data'] as $k => $d ) {
                
                $n = sanitize_text_field( $_REQUEST['name'][$k] );
                $d = sanitize_textarea_field( $d );
                
                $arr[] =  $n . ': ' . $d;

            }

            $data = implode( "\n", $arr );
            $data .= "\n";
            $data .= sanitize_textarea_field( $_REQUEST['data_other'] );

            // p($data);
            // $this->save( 'attributes', implode( "\n", $arr ) ); 
            $this->save( 'attributes', $data ); 

        } else {

            $this->save( 'attributes' ); 

        }


    }




    //

}

?>