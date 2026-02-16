<?php

//
// Ядро Инструменты разработки ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_tools_core {

    function __construct()
    {

    }
    
    

    // 
    // Показывает меню  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools.php', false );

        }
    }
    



    // 
    // Получить файлы 
    //    

    public function get_file( $att = array() )
    {
        global $post;

        $args = array(
            'numberposts' => -1,
            'post_parent' => mif_mr_opop_core::get_opop_id(), 
            'post_type' => 'attachment',
            // 'order' => 'ASC',
            'order' => 'DESC',
            'post_status' => 'inherit',
            // 'orderby' => 'menu_order',
        );
        
        if ( ! empty( $att['orderby'] ) ) $args['orderby'] = $att['orderby'];
        if ( ! empty( $att['order'] ) ) $args['order'] = $att['order'];

        $arr = get_posts( $args );
            
        // Удалить из ответа лишние файлы 
        
        if ( isset( $att['ext'] ) ) {
        
            foreach ( $arr as $key => $item ) {
                $arr_tmp = explode( '.', $item->guid );
                $ext = array_pop( $arr_tmp );
                if ( ! in_array( $ext, (array) $att['ext'] ) ) unset( $arr[$key] );
            }

        }

        return $arr;
    }



    //
    //
    //

    public function get_meta( $type = 'courses' )
    {
        $out = '';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';  
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '" />';  
        $out .= '<input type="hidden" name="type" value="' . $type . '" />';  
        $out .= '<input type="hidden" name="opop_title" value="' . mif_mr_opop_core::get_opop_title() . '" />';  
        
        return $out;
    }



    //
    //
    //   

    function remove( $attid )
    {
        // !!!!!!!

        $res = ( wp_delete_attachment( $attid, true ) === false ) ? false : true;
        return $res;
    }




}

?>