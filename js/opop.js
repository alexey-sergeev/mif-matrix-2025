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

   
    
    
    // Показать окно редактирования
    
    jq( 'body' ).on( 'click', '.comp a.edit', function() {
        sub_do( this, 'edit', jq(this).closest('span.content-ajax') );
        return false;
    } );    



    // Отмена
    
    jq( 'body' ).on( 'click', '.comp button.cancel', function() {
        sub_do( this, 'cancel', jq(this).closest('span.content-ajax') );
        jq(this).closest('span.new').remove();
        return false;
    } );    


    
    // save
    
    jq( 'body' ).on( 'click', '.comp button.save', function() {
        sub_do( this, 'save', jq(this).closest('div.content-ajax') );
        return false;
    } );    
    
    
    
    // new
    
    jq( 'body' ).on( 'click', '.comp a.new', function() {
        jq(this).closest('div.row').before('<span class="new"><span class="content-ajax"><a href="#" class="edit d-none" id="new" data-sub="-1">#</a></span></span>');
        jq( '#new' ).trigger('click');
        return false;
    } );    
    
    
    // remove
    
    jq( 'body' ).on( 'click', 'a.msg-remove', function() {
        jq( 'input[name=yes]', jq(this).closest('div.row') ).removeClass('is-invalid');
        jq( 'div.alert', jq(this).closest('div.row') ).slideToggle();
        return false;
    } );    
    
    jq( 'body' ).on( 'click', '.comp .cancel', function() {
        jq( 'input[name=yes]', jq(this).closest('div.alert') ).removeClass('is-invalid');
        jq( 'div.alert', jq(this).closest('div.row') ).slideToggle();
        return false;
    } );    
    
    jq( 'body' ).on( 'click', '.comp .remove', function() {
        
        if ( jq( 'input[name=yes]', jq(this).closest('div.alert') ).is(':checked') ) {
            
            sub_do( this, 'remove', jq(this).closest('div.content-ajax') );
            // console.log( '@' );
            
        } else {
            
            jq( 'input[name=yes]', jq(this).closest('div.alert') ).addClass('is-invalid');
            jq( 'input[name=yes]', jq(this).closest('div.alert') ).focus();
            
        }

        return false;
    } );    





    function sub_do( elem, action, div ) {
        
        let sub_id = jq(elem).attr( 'data-sub' );
        
        let opop_id = jq( 'input[name=opop]' ).val();
        let comp_id = jq( 'input[name=comp]' ).val();
        let nonce = jq( 'input[name=_wpnonce]' ).val();
        let content = jq( 'textarea.content', jq(elem).closest('span') ).val();
        let title = jq( 'input[name=title]' ).val();
        let data = jq( 'textarea[name=data]' ).val();

        // let div = jq(elem).closest('span.content-ajax');
        jq( 'i.fa-spinner', jq(elem).closest('button') ).removeClass('d-none');

        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: {
                    action: action,
                    opop: opop_id,
                    comp: comp_id,
                    sub: sub_id,
                    content: content,
                    title: title,
                    data: data,
                    _wpnonce: nonce,
            },
            success: function( response ) {

                if ( response ) {

                    div.replaceWith( response )
                    console.log( response );

                } else {
                    
                    console.log( 'error 5' );
                    
                }
                
            },
            error: function( response ) {
                
                console.log( 'error 6' );
                
            },
            
        } );
        
        jq( 'i.fa-spinner', jq(elem).closest('div') ).addClass('d-none');
        
        // return false;
        
    }
    
    
    
    
    // list-comp
    
    jq( 'body' ).on( 'click', '.set-comp button.new', function() {
        jq( 'div.new', jq(this).closest('div.container') ).slideToggle();
        return false;
    })
    
    // 
    
    jq( 'body' ).on( 'click', '.set-comp button.cancel', function() {
        jq( 'input[name=title]', jq(this).closest('div.container') ).val('');
        jq( 'textarea[name=data]', jq(this).closest('div.container') ).val('');
        jq( 'div.new', jq(this).closest('div.container') ).slideToggle();
        return false;
    })
    
    // 
    
    jq( 'body' ).on( 'click', '.set-comp button.create', function() {
        
        if ( jq( 'input[name=title]', jq(this).closest('div.container') ).val() == false ) {

            jq( 'input[name=title]', jq(this).closest('div.container') ).addClass('is-invalid');
            jq( 'input[name=title]', jq(this).closest('div.container') ).focus();

        } else {

            sub_do( this, 'create', jq(this).closest('div.content-ajax') );
        
        }
        return false;
    })
    
    
    





    // #fullsize
    
    jq( 'body' ).on( 'click', '#fullsize', function() {
        
        jq( 'i', this ).toggleClass( 'd-none' );
        jq( 'div.container' ).toggleClass( 'fullsize' );
        jq( '#primary div.column' ).toggleClass( 'is-11-desktop is-12-desktop' );
        
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', '#roll-up-all', function() {
        jq( 'a.roll-up', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).addClass('d-none'); });
        jq( 'a.roll-down', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).removeClass('d-none'); });
        jq( '.coll', jq(this).closest('.part') ).slideUp();
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', '#roll-down-all', function() {
        jq( 'a.roll-down', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).addClass('d-none'); });
        jq( 'a.roll-up', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).removeClass('d-none'); });
        jq( '.coll', jq(this).closest('.part') ).slideDown();
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', 'a.roll-up', function() {
        jq( '.coll', jq(this).closest('span') ).slideUp();
        jq( 'a.roll-up', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'a.roll-down', jq(this).closest('span') ).toggleClass('d-none');
        return false;
        
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', 'a.roll-down', function() {
        jq( '.coll', jq(this).closest('span') ).slideDown();
        jq( 'a.roll-up', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'a.roll-down', jq(this).closest('span') ).toggleClass('d-none');
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
                
                // console.log( elem );
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
   
    

    // set comp


    jq( 'body' ).on( 'click', '.part .comp input[type=checkbox]', function() {
        
        console.log( '@' );
        
        let tr = jq(this).closest('tr');
        
        // let name = jq( 'input[name=name[]]', tr ).val();
        let name = jq( 'input.name', tr ).val();
        // let descr = jq( 'input.descr', tr ).val();
        let comp_id = jq( 'input.comp_id', tr ).val();
        
        if ( jq( 'input.new_name', tr ).val() == '' ) jq( 'input.new_name', tr ).val(name);
        



        console.log( name );
        // console.log( descr );
        console.log( comp_id );

        // return false;
        
    })
    
    




    
});