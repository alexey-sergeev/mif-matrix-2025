// 
// JS-методы плагина Matrix 2025
// 


jQuery( document ).ready( function( jq ) {
    
    // Показать контент
    
    jq( 'body' ).on( 'click', 'input[type="radio"]', function() {
        
        var data = new FormData( jq(this).closest('form').get(0) );
        var div = jq( 'div.content-ajax' );

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




    // // Показать окно редактирования
    
    // jq( 'body' ).on( 'click', 'a.edit', function() {
        
    //     let opop_id = jq( 'input[name=opop]' ).val();
    //     let comp_id = jq( 'input[name=comp]' ).val();
    //     let sub_id = jq(this).attr( 'data-sub' );
    //     let nonce = jq( 'input[name=_wpnonce]' ).val();

    //     let div = jq( 'div.content-ajax', jq(this).closest('span') );
    //     jq( 'i.fa-spinner', jq(this).closest('div') ).removeClass('d-none');

    //     jq.ajax( {
    //         url: ajaxurl,
    //         type: 'POST',
    //         data: {
    //                 action: 'edit',
    //                 opop: opop_id,
    //                 comp: comp_id,
    //                 sub: sub_id,
    //                 _wpnonce: nonce,
    //         },
    //         success: function( response ) {

    //             if ( response ) {

    //                 div.replaceWith( response )
    //                 // console.log( response );
                    
    //             } else {
                    
    //                 console.log( 'error 5' );
                    
    //             }
                
    //         },
    //         error: function( response ) {
                
    //             console.log( 'error 6' );
                
    //         },
            
    //     } );

    //     jq( 'i.fa-spinner', jq(this).closest('div') ).addClass('d-none');

    //     return false;
        
    // } );    
    
    
    
    // Показать окно редактирования
    
    jq( 'body' ).on( 'click', 'a.edit', function() {
        sub_do( this, 'edit' );
        return false;
    } );    



    // Отмена
    
    jq( 'body' ).on( 'click', 'button.cancel', function() {
        sub_do( this, 'cancel' );
        return false;
    } );    



    // save
    
    jq( 'body' ).on( 'click', 'button.save', function() {
        sub_do( this, 'save' );
        return false;
    } );    





    function sub_do( elem, action ) {
        
        let sub_id = jq(elem).attr( 'data-sub' );
        
        let opop_id = jq( 'input[name=opop]' ).val();
        let comp_id = jq( 'input[name=comp]' ).val();
        let nonce = jq( 'input[name=_wpnonce]' ).val();
        let content = jq( 'textarea.content', jq(elem).closest('span') ).val();
        
        let div = jq( 'div.content-ajax', jq(elem).closest('span') );
        jq( 'i.fa-spinner', jq(elem).closest('div') ).removeClass('d-none');

        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: {
                    action: action,
                    opop: opop_id,
                    comp: comp_id,
                    sub: sub_id,
                    content: content,
                    _wpnonce: nonce,
            },
            success: function( response ) {

                if ( response ) {

                    div.replaceWith( response )
                    // console.log( response );
                    // jq(div).fadeOut('fast', function() {
                    //     jq(div).replaceWith(response);
                    //     $(this).fadeIn('fast');
                    // });


                } else {
                    
                    console.log( 'error 5' );
                    
                }
                
            },
            error: function( response ) {
                
                console.log( 'error 6' );
                
            },
            
        } );

        jq( 'i.fa-spinner', jq(elem).closest('div') ).addClass('d-none');
        // jq( 'div.row', jq(elem).closest('span') ).removeClass('display');
        // jq( 'div.row', jq(elem).closest('span') ).each( function ( index, elem2 ) { jq(elem2).css('display', 'flex'); });
        // jq( 'div.row', jq(elem).closest('span') ).each( function ( index, elem2 ) { jq(elem2).removeAttr('display'); });
        
        
        // return false;
        
    }




    // #fullsize
    
    jq( 'body' ).on( 'click', '#fullsize', function() {
        
        jq( 'i', this ).toggleClass( 'd-none' );
        jq( 'div.container' ).toggleClass( 'fullsize' );
        jq( '#primary div.column' ).toggleClass( 'is-11-desktop is-12-desktop' );
        
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', '#bnt-roll-up', function() {
        
        jq( '.coll', jq(this).closest('.part') ).slideUp();
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', '#bnt-show', function() {
        
        jq( '.coll', jq(this).closest('.part') ).slideDown();
        return false;
        
    })
    
    
    // Selectable cmp, curriculum...
    
    jq( 'body' ).on( 'click', '.part .selectable', function() {
        
        f = ( jq(this).hasClass('active') ) ? false : true;
        
        jq('th', jq(this).parent()).each( function ( index, elem ) { jq(elem).removeClass('active'); });
        
        let tbody = jq('tbody', jq(this).closest('table'));
        jq( 'tr', tbody ).each( function ( index, elem ) { jq(elem).removeClass('d-none'); });
        
        if ( f ) {
            
            let cmp = jq(this).attr( 'data-cmp' );
            // console.log( cmp );
            
            jq(this).addClass('active');
            
            jq('th.selectable.' + cmp, jq(this).closest('table')).each( function ( index, elem ) { 
                
                console.log( elem );
                jq(elem).addClass('active'); 
            
            });


            jq( 'tr.can-select:not(.' + cmp + ')', tbody ).each( function ( index, elem ) { jq(elem).addClass('d-none'); });
            
        }
        
        return false;
        
    })
    
    
    
    // Selectable row
    
    jq( 'body' ).on( 'click', '.part input.rsm', function() {
        
        let row = jq(this).attr( 'data-row' );
        jq('.' + row, jq(this).closest('table')).each( function ( index, elem ) { jq(elem).toggleClass('d-none'); });
        jq('th input.rsm', jq(this).closest('table')).each( function ( index, elem ) { jq(elem).prop('checked', false); });
        jq('th.matser input.rsm', jq(this).closest('table')).each( function ( index, elem ) { jq(elem).prop('checked', true); });


        // return false;
        
    })
    

    jq( 'body' ).on( 'click', '.part input.all', function() {
        
        // console.log( '@' );
        
        let ch = jq('.matser.d-none input.rsm', jq(this).closest('table'));
        let ch2 = jq('.matser input.rsm', jq(this).closest('table'));
        
        if ( ! jq(this).is(':checked') ) {
                        
            if ( ch.length != 0 ) {

                ch.each( function ( index, elem ) { jq(elem).trigger('click'); });
                jq(this).prop('checked', true);
                
            } else {
                
                ch2.each( function ( index, elem ) { jq(elem).trigger('click'); });
                jq(this).prop('checked', false);
                
            }
            
        } else {
            
            ch2.each( function ( index, elem ) { jq(elem).trigger('click'); });
            jq(this).prop('checked', true);

        }
        

        // let row = jq(this).attr( 'data-row' );
        // jq('.' + row, jq(this).closest('table')).each( function ( index, elem ) { jq(elem).toggleClass('d-none'); });

        // return false;
        
    })
    
    




    
});