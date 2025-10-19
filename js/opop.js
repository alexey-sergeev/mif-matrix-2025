// 
// JS-методы плагина Matrix 2025
// 


jQuery( document ).ready( function( jq ) {
    
    // Показать контент
    
    jq( 'body' ).on( 'click', 'input[type="radio"]', function() {
        
        // console.log( '@@@' );
        // console.log( ajaxurl );
        // var key = jq( this ).attr( 'data-key' );
        // console.log  ( key );

        // const ajaxurl = '/wp-admin/admin-ajax.php'; //!!!!
        var data = new FormData( jq(this).closest('form').get(0) );
        // console.log( data );
        
        // var div = jq( this ).closest( 'div.content-part' );
        // var div = jq( 'div.content-part', this );
        var div = jq( 'div.content-ajax' );

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

                    div.replaceWith( response )
                    // console.log( response );
                    
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
    
    
    // #fullsize

    jq( 'body' ).on( 'click', '#fullsize', function() {

        // console.log('asd');

        jq( 'i', this ).toggleClass( 'd-none' );
        jq( 'div.container' ).toggleClass( 'fullsize' );
        jq( '#primary div.column' ).toggleClass( 'is-11-desktop is-12-desktop' );

        return false;

    })

    
    // Selectable cmp

    jq( 'body' ).on( 'click', '.matrix .selectable', function() {

        // console.log('asd');
        
        var cmp = jq( this ).attr( 'data-cmp' );
        console.log(cmp);
        
        return false;

    })
    


});