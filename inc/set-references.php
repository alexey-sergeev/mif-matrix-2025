<?php

//
// Настройки справочников
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_references extends mif_mr_set_core {
    
    function __construct()
    {
        parent::__construct();

        $this->reference_types = apply_filters( 'mif-mr-reference_types', array(
                                                    array( 'name' => 'kaf', 'title' => 'Номера кафедр', 'is_numeric' => true, 'is_string' => false ),
                                                    array( 'name' => 'staff', 'title' => 'Должностные лица', 'is_numeric' => false, 'is_string' => true ),
                                                ) );

        $this->do_save();                                       
                                                
    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-references.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-references.php', false );
            
        }
    }
    
    


    //
    // Показать cписок references
    //
    
    public function show_set_references()
    {
        global $tree;
        
        // $arr = $tree['content']['lib-references']['data'];
        // p($arr);
        // p($_REQUEST);

        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
            
            // $out .= $this->companion_edit( 'set-references' );

            // $out .= '<div class="row">';
            // $out .= '<div class="col p-0">';
            // $out .= '<div class="p-0">';
            // $out .= '@';
            
            if ( $_REQUEST['edit'] == 'easy' ) {
                
                $out .= $this->edit_easy();
                
            } else {
                
                $out .= $this->companion_edit( 'set-references' );
                
            }
            
            // $out .= '@';
            // $out .= '</div>';
            // $out .= '</div>';
            
        } else {
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Справочники в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';
            
            // $out .= $this->get_table_html( $arr );       
            
            foreach ( $this->reference_types as $item ) {
                
                if ( ! empty( $tree['content']['set-references']['data'][$item['name']] ) ) {

                    $out .= '<div class="row">';
                    $out .= '<div class="col p-3">';
                    $out .= '<span class="fw-bolder">' . $item['title'] . '</span>';
                    $out .= '</div>';
                    $out .= '</div>';
                    
                    if ( ! empty( $tree['content']['lib-references']['data'][ $tree['content']['set-references']['data'][$item['name']] ] ) ) {
                        
                        $reference = $tree['content']['lib-references']['data'][ $tree['content']['set-references']['data'][$item['name']] ];

                        // p($reference);
                        // p($references_id);
                        
                        $out .= '<div class="row bg-light border rounded">';
                        
                        $out .= '<div class="col-10 col-md-11 pt-5 pb-5">';
                        $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-references/' . $reference['comp_id'] . '">' . $reference['name'] . '</a>';
                        $out .= '</div>';
                        
                        $out .= '<div class="col-2 col-md-1 pt-5 pb-5 text-end">';
                        $out .= '<span class="bg-secondary text-light rounded pl-3 pr-3 p-1 copy">' . $reference['comp_id'] . '</span>';
                        $out .= '</div>';
                        
                        $out .= '</div>';

                        // p($reference);
                        // p($item);

                        $out .= '<div class="row">';
                        $out .= '<div class="col p-0 pt-3 pb-5">';
                        
                        if ( $reference['is_numeric'] === $item['is_numeric'] && $reference['is_string'] === $item['is_string'] ) {

                            if ( ! empty( $reference['data'] ) ) {
            
                                $out .= mif_mr_lib_references_screen::get_references_data( $reference );
                                
                            }
                            
                        } else {
                            
                            $out .= mif_mr_functions::get_callout( 'Кажется, что данные не те', 'warning' );
                            
                        }

                        $out .= '</div>';
                        $out .= '</div>';
                        
                    } else {
                        
                        $out .= '<div class="row">';
                        $out .= '<div class="col p-0">';
                        
                        $out .= mif_mr_functions::get_callout( 'Нет данных', 'warning' );
                        
                        $out .= '</div>';
                        $out .= '</div>';
                   
                   }
                
                } 
                


            //     $out .= $item['name'];
            
            // p($item);

            }
           
        }
        
        return apply_filters( 'mif_mr_show_set_references', $out );
    }
    

    

    //
    //
    //

    public function edit_easy()
    {
        global $tree;    
    
        $out = '';

        $n = 0; 

        // $out .= '<div class="container">';
        // $out .= '<div class="row">';
        // $out .= '<div class="col">';
        
        $out .= '<table>';
    
        $out .= '<thead><tr>';
        $out .= '<th><div class="p-2">№</div></th>';
        $out .= '<th><div class="p-2">Наименование справочника</div></th>';
        $out .= '<th><div class="p-2">Идентификатор</div></th>';
        $out .= '</tr></thead>';    
        
        foreach ( $this->reference_types as $item ) {
            
            $old_data = ( ! empty( $tree['content']['set-references']['data'][$item['name']] ) ) ? $tree['content']['set-references']['data'][$item['name']] : ''; 
        
            $out .= '<tr>';
            $out .= '<td><div class="p-2">';
            $out .= ++$n;
            $out .= '</div></td>';

            $out .= '<td><div class="p-2">';
            $out .= $item['title'];
            $out .= '</div></td>';
            
            $out .= '<td style="vertical-align: middle;">';
            $out .= '<input class="form-control" type="text" name="data[' . $item['name'] . ']" value="' . $old_data . '">';
            $out .= '</td>';

            $out .= '</tr>';
            // p($item);
        }
            

        $out .= '</table>';

        // $out .= '</div>';
        // $out .= '</div>';
        // $out .= '</div>';
        
        
        return apply_filters( 'mif_mr_edit_easy', $out );
    }
        

    


    private function do_save() 
    {
        if ( isset( $_REQUEST['data'] ) ) {

            $arr = array();
            foreach ( (array) $_REQUEST['data'] as $k => $d ) $arr[] =  $k . '::' . $d;

            $this->save( 'set-references', implode( "\n", $arr ) ); 

        } else {

            $this->save( 'set-references' ); 

        }


    }



    private $reference_types = array();

}

?>