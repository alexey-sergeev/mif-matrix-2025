<?php

//
// Ядро Инструменты разработки ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_tools_core {

    function __construct()
    {

        $this->remove_all();

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
        // global $post;

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
    // Мета-поля
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



    //
    //
    //   

    function remove_all()
    {
        // !!!!!!!

        if ( ! isset( $_REQUEST['remove_all'] ) ) return;
        
        global $wp_query;

        $m = new mif_mr_upload();

        if ( $wp_query->query['part'] == 'tools-curriculum' ) $arr = $this->get_file( array( 'ext' => array( 'plx' ) ) );
        if ( $wp_query->query['part'] == 'tools-courses' ) $arr = $this->get_file( array( 'ext' => array( 'xls', 'xlsx' ) ) );

        // p($arr);

        $res = true;

        foreach ( $arr as $a ) {

            if ( wp_delete_attachment( $a->ID, true ) === false ) $res = false;

        }
        
        global $messages;
        $messages[] = ( $res ) ? array( 'Все файлы были удалены', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 1802', 'danger' );
        
        return;
    }




    //
    //
    //   

    function remove_all_link( $type = 'courses' )
    {
        return '<div class="pb-4"><a href="' . mif_mr_opop_core::get_opop_url() . 'tools-' . $type . '/?remove_all">Удалить все файлы</a></div>';
    }




}

?>