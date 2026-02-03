<?php

//
//  Справочники
//  
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_references_screen extends mif_mr_lib_references {
    
    
    function __construct()
    {
        parent::__construct();
   
        $this->save_all();

    }
    
    
    // 
    // Показывает 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-creferences.php' ) ) {
           
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-references.php', false );

        }
        
    }
    
    
    
    //
    // Показать 
    //
    
    public function show_references( $opop_id = NULL )
    {
        global $wp_query;
        global $tree;

        if ( empty( $wp_query->query_vars['id'] ) ) return;
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $references_id = $wp_query->query_vars['id']; 
        $out = '';
        
        $out .= '<div class="content-ajax">';
        
        if ( isset( $tree['content']['lib-references']['data'][$references_id] ) ) {
            
            $arr = $tree['content']['lib-references']['data'][$references_id];
            
            $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $arr['name'] . '</h4>';
            
            if ( isset( $_REQUEST['edit'] ) ) {

                $out .= $this->get_edit( $references_id );

            } else {

                $out .= $this->get_references_data( $arr );

                // $n = 0;                
                
                // $out .= '<table>';
                
                // $out .= '<thead><tr>';
                // $out .= '<th>№</th>';
                // $out .= '<th>Наименование</th>';
                // $out .= '<th>Данные</th>';
                // $out .= '</tr></thead>';

                // foreach ( $arr['data'] as $item ) {
                    
                //     $out .= '<tr>';
                //     $out .= '<td>';
                //     $out .= ++$n;
                //     $out .= '</td>';

                //     $out .= '<td>';
                //     $out .= ( isset( $item['name'] ) ) ? $item['name'] : '';
                //     $out .= '</td>';
                    
                //     $out .= '<td>';
                //     $out .= ( isset( $item['att'] ) ) ? $item['att'] : '';
                //     $out .= '</td>';

                //     $out .= '</tr>';
                // }

                // $out .= '</table>';
                
            }
            
        }
        

        // Hidden
        
        $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        $out .= '<input type="hidden" name="comp" value="' . $references_id . '">';
        $out .= '<input type="hidden" name="action" value="lib-references">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';

        $out .= '</div>';

        return apply_filters( 'mif_mr_show_references', $out );
    }    
        
        

        
    
    public static function get_references_data( $arr = array() )
    {
        // p($arr);
        
        $out = '';
        
        $n = 0;                
        
        $out .= '<table>';
        
        $out .= '<thead><tr>';
        $out .= '<th>№</th>';
        $out .= '<th>Наименование</th>';
        $out .= '<th>Данные</th>';
        $out .= '</tr></thead>';

        foreach ( $arr['data'] as $item ) {
            
            $out .= '<tr>';
            $out .= '<td>';
            $out .= ++$n;
            $out .= '</td>';

            $out .= '<td>';
            $out .= ( isset( $item['name'] ) ) ? $item['name'] : '';
            $out .= '</td>';
            
            $out .= '<td>';
            $out .= ( isset( $item['att'] ) ) ? $item['att'] : '';
            $out .= '</td>';

            $out .= '</tr>';
        }

        $out .= '</table>';
    
        return apply_filters( 'mif_mr_get_references_data', $out, $arr );
    }

    
    // //
    // // Показать cписок 
    // //

    // public function get_lib_references( $opop_id = NULL )
    // {
    //     global $tree;
    //     global $wp_query;
        
    //     if ( ! empty( $wp_query->query_vars['id'] ) ) return;
        
    //     if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
    //     ####!!!!!
        
    //     $this->create( $opop_id, 'lib-references' );
        
    //     $arr = array();
    //     if ( isset( $tree['content']['lib-references']['data'] ) ) $arr = $tree['content']['lib-references']['data'];
    
    //     $index = array();
    //     foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
    //     foreach ( $index as $key => $item ) sort( $index[$key] ); 
    //     ksort( $index );
    //     // p($index);
    //     $f = true;
        
    //     $out = '';
        
    //     $out .= '<div class="content-ajax">';
        
    //     $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        
    //     $out .= '<div class="row">';
        
    //     $out .= '<div class="col">';
    //     $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека справочников</h4>';
    //     $out .= '</div>';
        
    //     $out .= '</div>';

    //     foreach ( $index as $i ) {
            
    //         foreach ( $i as $ii ) {
                
    //             $item = $arr[$ii];
            
    //             $comp_id_text = ( count( $index[$item['name']] ) > 1 ) ? ' (' . $item['comp_id'] . ')' : '';

    //             $out .= '<div class="row mt-3 mb-3">';
                
    //             $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
    //             $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-references/' . $item['comp_id'] . '">' . $item['name'] . $comp_id_text . '</a>';
    //             $out .= '</div>';
                
    //             $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
    //             $out .= ( $item['from_id'] == mif_mr_opop_core::get_opop_id() ||  $item['from_id'] == 0 ) ?
    //                     '' :
    //                     '<a href="' .  get_permalink( $item['from_id'] ) . 'lib-references/' . $item['comp_id'] . '" title="' . 
    //                     $this->mb_substr( get_the_title( $item['from_id'] ), 20 ) . '">' . $item['from_id'] . '</a>';
    //             $out .= '</div>';
                
    //             $out .= '</div>';
                
    //         }
        
    //     }
        
    //     if ( $f ) $out .= $this->get_lib_create( array(
    //                                                 'action' => 'lib-references',
    //                                                 'button' => 'Создать справочник',
    //                                                 'title' => 'Название справочника',
    //                                                 'hint_a' => 'Например: Номера кафедр, Должностные лица',
    //                                                 'date' => 'Данные',
    //                                                 'hint_b' => '<a href="' . '123' . '">Помощь</a>',
    //                                             ) );
    
    //     $out .= '</div>';
    //     $out .= '</div>';
        
    //     return apply_filters( 'mif_mr_show_lib_references', $out, $opop_id );
    // }    
    
    //
    // Показать cписок 
    //

    public function get_lib_references( $opop_id = NULL )
    {
        global $tree;
        global $wp_query;
        
        if ( ! empty( $wp_query->query_vars['id'] ) ) return;
        
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        ####!!!!!
        
        $this->create( $opop_id, 'lib-references' );
        
        $arr = array();
        if ( isset( $tree['content']['lib-references']['data'] ) ) $arr = $tree['content']['lib-references']['data'];
    
        // $index = array();
        // foreach ( $arr as $item ) $index[$item['name']][] = $item['comp_id'];
        
        // foreach ( $index as $key => $item ) sort( $index[$key] ); 
        // ksort( $index );
        // p($index);
        $f = true;
        
        $out = '';
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        
        $out .= $this->get_lib_head( array( 'title' => 'Библиотека справочников' ) );
        
        // $out .= '<div class="row">';
        
        // $out .= '<div class="col">';
        // $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека справочников</h4>';
        // $out .= '</div>';
        
        // $out .= '</div>';
        
        
        
        
        
        
        // foreach ( $index as $i ) {
            
        //     foreach ( $i as $ii ) {
            
        //         $item = $arr[$ii];
        
        foreach ( $arr as $item ) {
            
            $out .= $this->get_lib_body( array( 
                                                'comp_id' => $item['comp_id'],    
                                                'name' => $item['name'],    
                                                'from_id' => $item['from_id'],    
                                                'type' => 'lib-references',    
                                            ) );
            
            
            // $comp_id_text = ( count( $index[$item['name']] ) > 1 ) ? ' (' . $item['comp_id'] . ')' : '';

            // $out .= '<div class="row mt-3 mb-3">';
            
            // $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            // $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-references/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            // $out .= '</div>';
            
            // $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            // $out .= ( $item['from_id'] == mif_mr_opop_core::get_opop_id() ||  $item['from_id'] == 0 ) ?
            //         '' :
            //         '<a href="' .  get_permalink( $item['from_id'] ) . 'lib-references/' . $item['comp_id'] . '" title="' . 
            //         $this->mb_substr( get_the_title( $item['from_id'] ), 20 ) . '">' . $item['from_id'] . '</a>';
            // $out .= '</div>';
            
            // $out .= '</div>';
            
            // }
        
        }
        
        if ( $f ) $out .= $this->get_lib_create( array(
                                                    'action' => 'lib-references',
                                                    'button' => 'Создать справочник',
                                                    'title' => 'Название справочника',
                                                    'hint_a' => 'Например: Номера кафедр, Должностные лица',
                                                    'date' => 'Данные',
                                                    'hint_b' => '<a href="' . '123' . '">Помощь</a>',
                                                ) );
    
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_lib_references', $out, $opop_id );
    }    
    
    
}


?>