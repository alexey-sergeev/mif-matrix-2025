// 
// JS-методы плагина Matrix 2025
// 


jQuery( document ).ready( function( jq ) {
    
    // Показать контент
    
    jq( 'body' ).on( 'click', 'input', function() {
        
        // console.log( '@@@' );
        // console.log( ajaxurl );
        // var key = jq( this ).attr( 'data-key' );
        // console.log  ( key );

        // const ajaxurl = '/wp-admin/admin-ajax.php'; //!!!!
        var data = new FormData( jq(this).closest('form').get(0) );
        // console.log( data );
        
        // // var div = jq( this ).closest( 'div.next-page' );
        // var div = jq( 'div.next-page', this );

        // var button = jq( 'button', div );
        // var loading = jq( '.loading', div );

        // // button.hide();
        // // loading.fadeIn();

        // // Запрос на обновление каталога

        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function( response ) {

                if ( response ) {

                    // div.replaceWith( response )
                    console.log( response );
                    
                } else {
                    
                    console.log( 'error 3' );
                    
                }
                
            },
            error: function( response ) {
                
                console.log( 'error 4' );

            },

        } );

        
        jq( 'body .nav-tabs label' ).each( function ( index, elem ) { jq(elem).removeClass('active'); });
        jq(this).closest('label').addClass('active');
        




        return false;
        
    } );    
    
    
    


});