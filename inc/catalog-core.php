<?php

//
// Ядро каталога
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_catalog_core {

    // Количество элементов на одной странице каталога
    
    private $opops_per_page = 9;
    


    function __construct()
    {

    }
    
    
    
    
    
    //
    // Аргументы выбора элементов каталога
    //
    
    public function get_catalog_args( $page = 1 )
    {
    
        $args = array(
            'posts_per_page' => $this->opops_per_page,
            'post_type' => 'opop',
            'post_status' => 'publish',
            'order' => 'ASC',
            'orderby' => 'title',
            'paged' => $page,
            'suppress_filters' => false
        );
    
        if ( isset( $_REQUEST['opop_search'] ) ) $args['s'] = sanitize_text_field( $_REQUEST['opop_search'] );
        
        // Добавить информацию о категориях
        
        if ( isset( $_REQUEST['categories'] ) ) {
            
            $arr_category = array();

            foreach ( (array) $_REQUEST['categories'] as $item ) $cats[] = get_term_by( 'slug', $item, 'opop_category' )->term_id;

            $categories = array_diff( $cats, array( '' ) );
            if ( ! empty( $categories ) ) $args['tax_query'] = array( array( 'taxonomy' => 'opop_category', 'terms' => $categories, 'operator' => 'AND' ) );
            
        }        

        return apply_filters( 'mif_mr_screen_get_catalog_args', $args, $page );
    }

    
    
    
    
    //
    // Получить массив категорий
    //
    
    public function get_category_arr()
    {
        $arr = wp_cache_get( 'category_arr' );
        
        if( false === $arr ) {

            $index = $this->get_category_main_index();
            
            $index2 = array();
            foreach ( (array) $index as $key => $item ) $index2[$item['id']] = $key;

            $args = array(
                'taxonomy' => 'opop_category',
                'hide_empty' => true,
            );
            
            $categories = get_categories( $args );
            
            $arr = array();
            foreach ( (array) $categories as $category )
                if ( ! empty( $index2[ $category->parent ] ) )
                    $arr[ $index2[ $category->parent ] ][] = array(
                        'name' => $category->name,
                        'slug' => $category->slug,
                        // 'description' => explode( "/n", $category->description )[0],
                        'description' => $category->description,
                    );

                
            // ksort( $index_tree );
            // p( $arr );
            
            wp_cache_set( 'category_arr', $arr );
            
        }

        return apply_filters( 'mif_mr_screen_get_category_arr', $arr );
    }
    

    

    //
    // Получить индекс главный категорий
    //
    
    public function get_category_main_index()
    {
        $index = wp_cache_get( 'category_main_index' );
        
        if ( false === $index ) {
            
            $args = array(
                'taxonomy' => 'opop_category',
                'hide_empty' => 0,
                'parent' => 0
            );
            
            $categories = get_categories( $args );
            
            $index = array();
            
            foreach ( $categories as $item ) {
                
                $index[$item->slug] = array(
                    'name' => $item->name,
                    'id' => $item->term_id,
                );
                
            }

            wp_cache_set( 'category_main_index', $index );
            
        }
        
        return apply_filters( 'mif_mr_screen_get_category_index', $index );
    }
    
    
    
    
    //
    // Получить список категорий
    //
    
    public function get_category_list( $opop_id, $slug, $key = 'description', $n = 0 )
    {
        
        $index = $this->get_category_main_index();
        // p($index);
        $category = get_the_terms( $opop_id, 'opop_category' );
        
        $arr = array();

        foreach ( (array) $category as $item ) 
            if ( ! empty( $item ) && $item->parent === $index[$slug]['id'] ) {

                $item2 = explode( "\n", $item->$key );
                $arr[] = ( ! empty( $item2[$n] ) ) ? $item2[$n] : NULL;
                
            }
                
        return apply_filters( 'mif_mr_screen_get_category_list', $arr );
    }
    
     
    
    
    //
    // Получить цвет обложки
    //
    
    public function get_cover_color( $opop_id )
    {
        $arr = $this->get_category_list( $opop_id, 'department', 'description', 1 );
        $color = ( ! empty( $arr[0] ) ) ? $arr[0] : NULL;

        return apply_filters( 'mif_mr_get_cover_color', $color );
    }
 
    

}

?>