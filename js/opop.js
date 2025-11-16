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
        // jq( 'a.roll-down', jq(this).closest('span') ).trigger('click');
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
        // console.log( '@' );
        jq(this).closest('div.row').before('<span class="new"><span class="content-ajax"><a href="#" class="edit d-none" id="new" data-sub="-1">#</a></span></span>');
        jq('#new').trigger('click');
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
    
    
    
    
    
    function sub_do( elem, action_do, div ) {
        
        let sub_id = jq(elem).attr( 'data-sub' );
        let part = jq(elem).attr( 'data-part' );
        
        let action = jq( 'input[name=action]' ).val();
        let opop_id = jq( 'input[name=opop]' ).val();
        let comp_id = jq( 'input[name=comp]' ).val();
        let nonce = jq( 'input[name=_wpnonce]' ).val();
        let content = jq( 'textarea.content', jq(elem).closest('span') ).val();
        let title = jq( 'input[name=title]' ).val();
        let data = jq( 'textarea[name=data]' ).val();
        let name = jq( 'div.name', jq(elem).closest('span') ).text();
        
        let coll = {};
        jq( 'input.coll' ).each( function() { coll[jq(this).attr( 'data-key' )] = jq(this).attr( 'data-value' ); });
        
        // console.log( action );
        // console.log( action_do );
        // console.log( nonce );
        // console.log( sub_id );
        // console.log( name );
        // console.log( part );
        // console.log( coll );
        
        // let div = jq(elem).closest('span.content-ajax');
        jq( 'i.fa-spinner', jq(elem).closest('button') ).removeClass('d-none');
        
        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: {
                action: action,
                do: action_do,
                opop: opop_id,
                comp: comp_id,
                sub: sub_id,
                part: part,
                content: content,
                title: title,
                data: data,
                name: name,
                _wpnonce: nonce,
                coll: coll,
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
        // jq( 'input.coll', jq(elem).closest('span') ).attr( 'data-value', 'on' );
        
        // return false;
        
    }
    
    
    
    
    // list-comp
    
    jq( 'body' ).on( 'click', '.comp button.new', function() {
        jq( 'div.new', jq(this).closest('div.container') ).slideToggle();
        return false;
    })
    
    // 
    
    jq( 'body' ).on( 'click', '.comp button.cancel', function() {
        jq( 'input[name=title]', jq(this).closest('div.container') ).val('');
        jq( 'textarea[name=data]', jq(this).closest('div.container') ).val('');
        jq( 'div.new', jq(this).closest('div.container') ).slideToggle();
        return false;
    })
    
    // 
    
    jq( 'body' ).on( 'click', '.comp button.create', function() {
        
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
        jq( 'div.coll', jq(this).closest('.part') ).slideUp();
        jq( 'input.coll' ).each( function() { jq(this).attr( 'data-value', 'off' ); });
        return false;
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', '#roll-down-all', function() {
        jq( 'a.roll-down', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).addClass('d-none'); });
        jq( 'a.roll-up', jq(this).closest('.part') ).each( function ( index, elem ) { jq(elem).removeClass('d-none'); });
        jq( 'div.coll', jq(this).closest('.part') ).slideDown();
        jq( 'input.coll' ).each( function() { jq(this).attr( 'data-value', 'on' ); });
        return false;
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', 'a.roll-up', function() {
        jq( 'div.coll', jq(this).closest('span') ).slideUp();
        jq( 'a.roll-up', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'a.roll-down', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'input.coll', jq(this).closest('span') ).attr( 'data-value', 'off' );
        return false;
    })
    
    
    // 
    
    jq( 'body' ).on( 'click', 'a.roll-down', function() {
        jq( 'div.coll', jq(this).closest('span') ).slideDown();
        jq( 'a.roll-up', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'a.roll-down', jq(this).closest('span') ).toggleClass('d-none');
        jq( 'input.coll', jq(this).closest('span') ).attr( 'data-value', 'on' );
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
        
        let sorts = []; 
        jq( 'input.sort', jq(this).closest('tbody') ).each( function ( index, elem ) { sorts.push( jq(elem).val() ) });
        
        let max = -1;
        for ( key in sorts ) {
            if ( sorts[key] == 65535 ) continue;
            if ( sorts[key] > max ) max = sorts[key];
        }
        
        // console.log( sorts );
        
        jq( 'input.sort', tr ).val( ( jq( 'input.sel', tr ).is(':checked') ) ? ++max : 65535 );
        

        tr.addClass('mr-yellow flash');
        // jq( 'td', tr ).each( function ( index, elem ) { jq( elem ).addClass('mr-red flash') });
        
        setTimeout( function () {

            if ( jq( 'input.sort', tr ).val() == 65535 ) {
                
                let n = 0;
                while ( jq( 'input.sort', tr.next() ).val() != 65535 && n++ < sorts.length ) jq( tr.next() ).after( tr );
                
                jq( 'i.up', tr ).addClass('d-none');
                jq( 'i.down', tr ).addClass('d-none');
                
                
            } else {
                
                while ( jq( 'input.sort', tr.prev() ).val() == 65535 ) jq( tr.prev() ).before( tr );
                
                jq( 'i.up', tr ).removeClass('d-none');
                jq( 'i.down', tr ).removeClass('d-none');

            };
            
            setTimeout( function () { tr.removeClass('mr-yellow'); }, 1) ;
            setTimeout( function () { tr.removeClass('flash'); }, 2000 );
            // setTimeout( function () { jq( 'td', tr ).each( function ( index, elem ) { jq( elem ).removeClass('mr-red') }); }, 1 );
            // setTimeout( function () { jq( 'td', tr ).each( function ( index, elem ) { jq( elem ).removeClass('flash') }); }, 5000 );
            
        }, 200 );


        // sorts = []; 
        // jq( 'input.sort', jq(this).closest('tbody') ).each( function ( index, elem ) { sorts.push( jq(elem).val() ) });
        // console.log( sorts );
        // sorts.sort();
        // console.log( sorts );





        // let tbody = jq( 'tr', jq(this).closest('tbody') );

        // // console.log( tbody.length );

        // for ( let i = 0; i < tbody.length - 1; i++ ) {
            
        //     // console.log( '@' );
        //     for ( let j = 0; j < tbody.length - 1; j++ ) {
            
        //     // let ii = jq( 'input.sort', jq( tbody[i] )).val();
        //     // let jj = jq( 'input.sort', jq( tbody[j] )).val();
        //         // console.log( jj );
        //         // console.log( jj );
        //         let j0 = jq( 'input.sort', jq( tbody[j] )).val();
        //         let j1 = jq( 'input.sort', jq( tbody[j + 1] )).val();
                
        //         if ( j0 > j1 ) jq( tbody[j + 1] ).after(  jq( tbody[j] ) );
        //             console.log( j0 );
    
        //     } 
        // }

        // for ( key in tbody ) {

        //     console.log( jq(tbody[key]).html() );

        // }
        


        // console.log( max );

        // console.log( name );
        // console.log( descr );
        // console.log( comp_id );

        // return false;
        
    })
    
    

    jq( 'body' ).on( 'click', '.part .comp i.up', function() {
        
        // console.log( '@' );
        let sort_1 = jq( 'input.sort', jq(this).closest('tr').prev() ).val();
        let sort_2 = jq( 'input.sort', jq(this).closest('tr') ).val();
        let tr = jq(this).closest('tr');
        
        if ( sort_1 < 65535 && sort_2 < 65535 ) {
            
            tr.addClass('mr-yellow flash');

            jq( 'input.sort', jq(this).closest('tr') ).val( sort_1 );
            jq( 'input.sort', jq(this).closest('tr').prev() ).val( sort_2 );
            jq( jq(this).closest('tr').prev() ).before( jq(this).closest('tr') );
        
            setTimeout( function () { tr.removeClass('mr-yellow'); }, 1) ;
            setTimeout( function () { tr.removeClass('flash'); }, 2000 );

        }

        // jq( 'input.sort', jq(this).closest('tr').prev() ).val( jq( 'input.sort', jq(this).closest('tr') ).val() );

        // console.log( sort );
        
    })
    
    jq( 'body' ).on( 'click', '.part .comp i.down', function() {
        

        let sort_1 = jq( 'input.sort', jq(this).closest('tr').next() ).val();
        let sort_2 = jq( 'input.sort', jq(this).closest('tr') ).val();
        let tr = jq(this).closest('tr');
        
        if ( sort_1 < 65535 && sort_2 < 65535 ) {
            
            tr.addClass('mr-yellow flash');

            jq( 'input.sort', jq(this).closest('tr') ).val( sort_1 );
            jq( 'input.sort', jq(this).closest('tr').next() ).val( sort_2 );
            jq( jq(this).closest('tr').next() ).after( jq(this).closest('tr') );
            // jq( jq(this).closest('tr').prev() ).before( jq(this).closest('tr') );
                
            setTimeout( function () { tr.removeClass('mr-yellow'); }, 1) ;
            setTimeout( function () { tr.removeClass('flash'); }, 2000 );

        }


        // let sort = jq( 'input.sort', jq(this).closest('tr').next() ).val();
        // jq( 'input.sort', jq(this).closest('tr').next() ).val( jq( 'input.sort', jq(this).closest('tr') ).val() );
        // jq( 'input.sort', jq(this).closest('tr') ).val( sort );

        // jq( jq(this).closest('tr').next() ).after( jq(this).closest('tr') );
        // console.log( '@@' );
      
        
    })


    
});