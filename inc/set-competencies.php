<?php

//
// Настройки компетенций
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_comp extends mif_mr_set_core {
    
    function __construct()
    {

        parent::__construct();
        
        $this->save( 'set-competencies' );
        $this->save( 'set-competencies', $this->compose_set_comp() );

    }
    
    
    
    // 
    // Показывает часть 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-competencies.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-competencies.php', false );
            
        }
    }
    

    
    //
    // Показать cписок компетенций
    //
    
    public function show_set_comp()
    {
        global $mr;
        if ( ! $mr->user_can(2) ) return;

        global $tree;

        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            if ( $_REQUEST['edit'] == 'visual' ) {
                
                $out .= $this->edit_visual();
                
            } else {
                
                $out .= $this->companion_edit( 'set-competencies' );
                
            }
            
            $out .= '</div>';
            $out .= '</div>';
            
        } else {
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Компетенции в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= mif_mr_companion_core::get_show_all();
            $out .= '</div>';
            $out .= '</div>';
            
            $arr = $tree['content']['competencies']['data'];

            $old = '';
            $n = 0;
            $index = array();
            // p($arr);

            foreach ( $arr as $key => $item ) {
                
                if ( empty( $item['category'] ) ) {

                    $index[$n][] = $key;

                } else {

                    if ( $old == $item['category'] ) $n--;
                    $index[$n][] = $key;
                    $old = $item['category'];
                    $n++;

                }

            }
            
            foreach ( $index as $item ) {
                
                // p($item);
                if ( empty( $item ) ) continue;
                
                $out .= '<span class="content-ajax">';
                // $category = ( isset( $arr[$item[0]]['category'] ) ) ? $arr[$item[0]]['category'] : apply_filters( 'mif-mr-sub_default', 'default' );
                $out .= mif_mr_competencies_screen::get_sub_head( array( 'name' => $arr[$item[0]]['category'] ) );
                foreach ( $item as $item2 ) $out .= mif_mr_competencies_screen::get_item_body( $arr[$item2] );
                $out .= '</span>';
                
            }
            




               
        }
        
        return apply_filters( 'mif_mr_show_set_comp', $out );
    }
    
    
    
    
    //
    //
    //
    
    public function compose_set_comp()
    {
        $out = '';        
        
        // p( $_REQUEST );
        
        if ( isset( $_REQUEST['visual'] ) && $_REQUEST['visual'] == 'yes' ) {

            foreach ( (array) $_REQUEST['new_name'] as $key => $new_name ) {

                $new_name = sanitize_text_field( $new_name );

                if ( empty( $new_name ) ) continue;
                if ( $new_name == $_REQUEST['bak_name'][$key] && ! $_REQUEST['method'][$key] == 'manual' ) continue;

                $name = sanitize_text_field( $_REQUEST['name'][$key] );
                $comp_id = sanitize_text_field( $_REQUEST['comp_id'][$key] );

                $out .= $new_name . ':' . $name . ':' . $comp_id . "\n";

            }

        }

        return apply_filters( 'mif_mr_compose_set_comp', $out );
    }
    


    
    //
    //
    //

    public function edit_visual()
    {
        global $tree;
        $arr = $tree['content']['lib-competencies']['data'];
        $arr2 = array();
        
        foreach ( $arr as $item ) {
            
            foreach ( $item['data'] as $item2 ) {
                
                foreach ( $item2['data'] as $item3 ) {
                    
                    $arr2[$item['comp_id']][$item3['name']] = array(
                        'comp_id' => $item['comp_id'],
                        'lib_name' => $item['name'],
                        'name' => $item3['name'],
                        'descr' => $item3['descr'],
                        // 'category' => $item3['category'],
                        'category' => $item2['name'],
                    );
                    
                }
            }
        }
        
        // p($item);
        // p($arr2);

        $arr3 = $tree['content']['competencies']['data'];

        foreach ( $arr3 as $item ) {

            // p($i);

            if ( isset( $item['comp_id'] ) && isset( $arr2[$item['comp_id']][$item['old_name']] ) ) {

                $arr2[$item['comp_id']][$item['old_name']]['new_name'] = $item['name'];  
                $arr2[$item['comp_id']][$item['old_name']]['method'] = $item['method'];  

            }
                
        }


        
        // p($arr3);

        $arr4 = array();
        foreach ( $arr2 as $item )
            foreach ( $item as $item2 ) $arr4[] = $item2;


        $index = array();
        foreach ( $arr3 as $k => $i ) $index[] = $k;
        
        $arr5 = array();

        foreach ( $index as $k => $cmp )            
            foreach ( $arr4 as $k2 => $item )
                if ( isset( $item['new_name'] ) && $item['new_name'] == $cmp ) {

                    $arr5[] = $item;
                    unset( $index[$k] );
                    unset( $arr4[$k2] );
                    continue;

                } 

        foreach ( $arr4 as $item ) $arr5[] = $item;
            
        $out = '';
        
        if ( ! empty( $index ) ) {
            

            $out .= '<div class="p-2 mb-6 bg-light border rounded fiksa">';
            
            $out .= '<div class="m-3">';
            $out .= '<h4>Компетенции не задействованы:</h4>';
            $out .= '</div>';


            $out .= '<div class="m-3 mb-3">';
            // $out .= '<div class="col">';

            foreach ( $index as $i ) {
                
                $out .= '<span class="mr-2 mb-3 copy-wrapper d-inline-block">';
                $out .= mif_mr_opop_core::get_span_id( $i );
                $out .= '</span> ';
                
            }
            
            $out .= '</div>';
            $out .= '</div>';
        
        }

        $out .= '<table class="vedit">';
        
        $out .= '<thead><tr>';
        
        // $out .= '<th>';
        // $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Новое имя';
        $out .= '</th>';
        
        $out .= '<th>';
        // $out .= '<th colspan="2">';
        $out .= 'Компетенция';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Библиотека';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'ID';
        $out .= '</th>';
        
        // $out .= '<th>';
        // $out .= '</th>';
        
        $out .= '</tr></thead>';
        
        $out .= '<tbody>';
        foreach ( $arr5 as $item ) $out .= $this->edit_visual_comp( $item );
        $out .= '</tbody>';
        
        $out .= '</table>';
        
        $out .= '<input name="visual" type="hidden" value="yes">';

        return apply_filters( 'mif_mr_edit_visual', $out );
    }
    
    
    
    
    //
    //
    //
    
    public function edit_visual_comp( $item )
    {
        $out = '';
        
        $new_name = ( isset( $item['new_name'] ) ) ? $item['new_name'] : '';
        $method = ( isset( $item['method'] ) ) ? $item['method'] : '';

        // p($item);
        $out .= '<tr>';
     
        $out .= '<td>';
        $out .= '<input name="new_name[]" type="text" value="' . $new_name . '" class="new_name form-control mt-1">';
        $out .= '<input name="bak_name[]" type="hidden" value="' . $new_name . '">';
        $out .= '<input name="name[]" type="hidden" value="' . $item['name'] . '">';
        $out .= '<input name="comp_id[]" type="hidden" value="' . $item['comp_id'] . '">';
        $out .= '<input name="method[]" type="hidden" value="' . $method . '">';
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= '<span class="fw-bolder">' . $item['name'] . '.</span> ';
        $out .= $this->mb_substr( $item['descr'], 100 );
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= $this->mb_substr( $item['lib_name'], 30 );
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= mif_mr_opop_core::get_a_id( $item['comp_id'], 
                                            mif_mr_opop_core::get_opop_url() . 'lib-competencies/' . $item['comp_id'],
                                            mif_mr_functions::mb_substr( get_the_title( $item['comp_id'] ), 30 ) );
        $out .= '</td>';
        
        $out .= '</tr>';

        return apply_filters( 'mif_mr_edit_visual_comp', $out, $item );
    }

}

?>