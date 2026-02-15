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
        // console.log( '@' );
        jq( 'input[name=yes]', jq(this).closest('div.row') ).removeClass('is-invalid');
        jq( 'div.alert', jq(this).closest('div.row') ).slideToggle();
        return false;
    } );    
    
    // jq( 'body' ).on( 'click', '.comp .cancel', function() {
    jq( 'body' ).on( 'click', '.sidebar .cancel', function() {
        // console.log( '@' );
        jq( 'input[name=yes]', jq(this).closest('div.alert') ).removeClass('is-invalid');
        jq( 'div.alert', jq(this).closest('div.row') ).slideToggle();
        return false;
    } );    
    
    // jq( 'body' ).on( 'click', '.comp .remove', function() {
    jq( 'body' ).on( 'click', '.sidebar .remove', function() {
        //  console.log( '@@@' );
        if ( jq( 'input[name=yes]', jq(this).closest('div.alert') ).is(':checked') ) {
            
            // sub_do( this, 'remove', jq(this).closest('div.content-ajax') );
            sub_do( this, 'remove', jq( 'div.content-ajax', jq(this).closest('div.container') ) );
            jq( 'div.alert', jq(this).closest('div.row') ).slideUp();
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
        
        console.log( action );
        console.log( action_do );
        console.log( nonce );
        console.log( sub_id );
        console.log( name );
        console.log( part );
        console.log( coll );
        
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
                
                console.log( response );
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
        
        // console.log( '@' );
        
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
        
    })
    
    
    // 
    // tools curriculum
    // 

    // "remove"

    jq( 'body' ).on( 'click', '.tools .remove', function() {
        
        let elem = jq(this).closest('div.row');
        let attid = jq(this).attr( 'data-attid' );    
        let attid_show = jq( '.show-file' ).attr( 'data-attid' );    

        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'tools',
                do: 'remove',
                attid: attid,
                opop: jq( 'input[name=opop]' ).val(),
                _wpnonce: jq( 'input[name=_wpnonce]' ).val(),
            },
            success: function( response ) {
                
                // console.log( response );
                if ( response == '1' ) {
                    
                    jq('.analysis-box').remove();
                    
                    jq( elem ).slideUp( function(){ 
                        
                        jq(this).remove();
                        // jq('.analysis-box').remove();
                        
                        if ( jq('.tools .remove').length === 0 ) {
                            jq( '.no-plans' ).slideDown();
                            // jq( '.bottom-panel' ).slideUp();
                            jq( '.bottom-panel' ).hide();
                            // jq( '.bottom-panel' ).slideUp( function(){ jq( '.no-plans' ).slideDown(); } );
                        }  
                    
                    });
                    
                    if ( attid == attid_show ) jq( '.show-file' ).slideUp( function(){ jq(this).remove(); });
                    
                    // console.log( response );
                    
                } else {
                    
                    console.log( 'error 7' );
                    
                }
                
            },
            error: function( response ) {
                
                console.log( 'error 8' );
                
            },
            
        } );



        return false;
        
    })
    
    


    // "Сохранить"
    
    
    jq( 'body' ).on( 'click', '.tools .save', function() {
        
        let elem = jq(this).closest('div.plx-item');

        if ( jq('.report', elem).attr('data-visible') == undefined ) { 
            
            // jq( '.report', elem ).attr( 'data-visible', '1' ),

            jq.ajax( {
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tools',
                    do: 'save',
                    opop: jq( 'input[name=opop]' ).val(),
                    attid: jq( 'input[name=attid]' ).val(),
                    opop_title: jq( 'input[name=opop_title]' ).val(),
                    explanation: jq( 'input[name=explanation]', elem ).val(),
                    yes: jq(this).attr( 'data-yes' ),
                    key: jq( 'textarea', jq(this).closest('div.plx-item') ).attr( 'name' ),
                    _wpnonce: jq( 'input[name=_wpnonce]' ).val(),
                },
                success: function( response ) {
                    
                    // console.log( response );
                    if ( response ) {

                        // jq( '.report', elem ).html( response );
                        // jq( '.report', elem ).slideDown();
                        
                        jq( '.report', elem ).slideUp( function() {
                            jq( '.report', elem ).html( response );
                            jq( '.report', elem ).slideDown();
                        });

                        jq( '.analysis-box', elem ).slideUp( function ( index, elem ) { jq(elem).html(''); });
                        // console.log( response );
                        
                    } else {
                        
                        console.log( 'error 9' );
                        
                    }
                    
                },
                error: function( response ) {
                    
                    console.log( 'error 10' );
                    
                },
                
            } );
        
        } else {

            // jq('.report', elem).removeAttr('data-visible');
            // jq( '.report', elem ).slideUp( function() { jq( '.report', elem ).html(''); });

        }    
        
        return false;
        
    })
    
    
    
    // Отменить
    
    jq( 'body' ).on( 'click', '.tools .cancel', function() {
        jq( '.report',  jq(this).closest('div.plx-item') ).slideUp();
        return false;
    })
    
    
    jq( 'body' ).on( 'click', '.tools .cancel-analysis', function() {
        // console.log( '@' );
        jq( '.analysis-box',  jq(this).closest('div.plx-item') ).slideUp(function ( index, elem ) {jq(elem).html('');});
        jq( '.analysis-box' ).each( function ( index, elem ) { jq(elem).removeAttr('data-visible'); } );                    
        jq( 'div.container' ).removeClass( 'fullsize' );
        jq( '#primary div.column' ).addClass( 'is-11-desktop' );
        jq( '#primary div.column' ).removeClass( 'is-12-desktop' );

        return false;
    })
    


    // "Анализ"
        
    jq( 'body' ).on( 'click', '.tools .analysis', function() {
        
        let elem = jq(this).closest('div.plx-item');
        jq( '.report', elem ).slideUp();

        if ( jq('.analysis-box', elem).attr('data-visible') == undefined ) { 

            jq( '.analysis-box' ).each( function ( index, elem ) { jq(elem).removeAttr('data-visible'); } );
            jq( '.analysis-box', elem ).attr( 'data-visible', '1' ),

            // console.log( jq( 'textarea', jq(this).closest('div.row') ).val() );
            
            jq.ajax( {
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tools',
                    do: 'analysis',
                    opop: jq( 'input[name=opop]' ).val(),
                    attid: jq( 'input[name=attid]' ).val(),
                    method: jq( 'input[name=method]', jq(this).closest('div.row') ).val(),
                    // data: jq( 'textarea', jq(this).closest('div.row') ).val(),
                    key: jq( 'textarea', jq(this).closest('div.row') ).attr( 'name' ),
                    _wpnonce: jq( 'input[name=_wpnonce]' ).val(),
                },
                success: function( response ) {
                    
                    if ( response ) {

                        jq( '.analysis-box' ).each( function ( index, elem ) { 
                            
                            jq(elem).html(''); 
                            jq(elem).slideUp(); 
                        
                        } );

                        
                        jq( '.analysis-box', elem ).slideUp( function() {
                            jq( '.analysis-box', elem ).html( response );
                            jq( '.analysis-box', elem ).slideDown();
                        });

                        // console.log( response );
                        
                    } else {
                        
                        console.log( 'error 11' );
                        
                    }
                    
                },
                error: function( response ) {
                    
                    console.log( 'error 12' );
                    
                },
                
            } );

                
        } else {

            // jq( '.analysis-box', elem ).removeAttr('data-visible');
            // jq( '.analysis-box', elem ).slideUp( function() { jq( '.analysis-box', elem ).html(''); });
            jq('.tools-curriculum .cancel-analysis').trigger('click');

        }   
        
        return false;
        
    })


    // Help
    
    jq( 'body' ).on( 'click', '.tools-curriculum .help', function() {
        jq( '.help-box',  jq(this).closest('div.plx-item') ).slideToggle();
        return false;
    })
    
    jq( 'body' ).on( 'click', '.comp .help', function() {
        jq( '.help-box',  jq(this).closest('div.comp') ).slideToggle();
        return false;
    })


    // "Копировать"

    jq( 'body' ).on( 'click', 'a.copy-button', function() {
        jq( 'textarea', jq(this).closest('div.row') ).select();    
        document.execCommand('copy'); 
        return false;
    }); 
    
    
    jq( 'body' ).on( 'click', 'span.copy-button', function() {
     
        // navigator.clipboard.writeText( jq(this).text() );
        navigator.clipboard.writeText( jq( '.copy',  jq(this).closest('.copy-wrapper') ).text() );
     
        elem = this;
        jq(this).addClass('act'); 
        setTimeout( function() { jq(elem).removeClass('act') }, 200);
        return false;
        
    }); 
    
    // jq( 'body' ).on( 'click', 'a.copy', function() {
    //     navigator.clipboard.writeText( jq(this).text() );
    //     return false;
    // }); 
    

    
    // 
    // tools courses
    // 

    jq( 'body' ).on( 'click', '.tools .select-menu input', function() {
        
        jq('.select-item', jq(this).closest('.container')).addClass('select-yes');
        jq('.select-item input[type="checkbox"]', jq(this).closest('.container')).prop('checked', false );
        jq('input[name="all"]', jq(this).closest('.container')).prop('checked', false );

        jq('.select-menu input', jq(this).closest('.container')).each( function() { 
            if ( ! jq(this).is(':checked') ) jq('.select-item.' + jq(this).val(), jq(this).closest('.container')).removeClass('select-yes');
        });
        
        jq('.select-item', jq(this).closest('.container')).each( function() { 
            if ( ! jq(this).hasClass('select-yes') ) jq(this).slideUp(); else jq(this).slideDown();
        });
    
    }); 


    // All

    jq( 'body' ).on( 'click', '.tools input[name=all]', function() {
        
        jq('.select-item.select-yes input[type="checkbox"]', jq(this).closest('.container')).prop('checked', jq(this).is(':checked') );

    }); 


    // Save

    jq( 'body' ).on( 'click', '.tools .export-all', function() {
        
        // jq('.select-item.select-yes input[type="checkbox"]', jq(this).closest('.container')).prop('checked', jq(this).is(':checked') );
        let f = false;        
        jq('.select-item input[type="checkbox"]', jq(this).closest('.container')).each( function() { if ( jq(this).is(':checked') ) f = true; });
        
        if ( f ) {
            
            console.log('@');
            
        } else {
            
            console.log('@@');
            jq( 'input[name="all"]', jq(this).closest('.container') ).focus();

        }
        
        return false;

    }); 


    // Remove

    jq( 'body' ).on( 'click', '.tools .remove-all', function() {
        
        let f = false;        
        jq('.select-item input[type="checkbox"]', jq(this).closest('.container')).each( function() { if ( jq(this).is(':checked') ) f = true; });
        
        if ( f ) {
            
            console.log('@1');
            
            jq('.select-item .remove', jq(this).closest('.container')).each( function() { 
                
                let elem = this;

                jq('input[type="checkbox"]', jq(this).closest('.select-item')).each( function() {
                    
                    if ( jq(this).is(':checked') ) jq(elem).trigger('click');
                
                });
                
            });

        } else {
            
            // console.log('@@1');
            jq( 'input[name="all"]', jq(this).closest('.container') ).focus();

        }

        return false;

    }); 



    // Info (analysis)

    jq( 'body' ).on( 'click', '.tools .info', function() {
        
        // console.log('@@@');
        let elem = jq( '.col', jq(this).closest('.row') ).parent();
        
        // console.log( elem.is(':last-child') );

        // if ( elem.next().hasClass('select-item') ) {
        // if ( jq('.analysis-box').length == 0 ) {
        
        // if ( elem.next().hasClass('select-item') || jq('.analysis-box').length == 0 ) {
        if ( elem.next().hasClass('select-item') || elem.is(':last-child') ) {

            jq.ajax( {
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tools',
                    do: 'info',
                    opop: jq( 'input[name=opop]' ).val(),
                    attid: jq( 'input[type="checkbox"]', jq(this).closest('.select-item') ).val(),
                    _wpnonce: jq( 'input[name=_wpnonce]' ).val(),
                },
                success: function( response ) {
                    // console.log('@');
                    if ( response ) {
    
                        jq('.analysis-box').remove();
                        elem.after( response );
                        elem.next("div").slideDown();
                        // console.log( response );
                        
                    } else {
                        
                        console.log( 'error 13' );
                        
                    }
                    
                },
                error: function( response ) {
                    
                    console.log( 'error 14' );
                    
                },
                
            } );

        } else {

            jq('.analysis-box').slideUp( function() { jq('.analysis-box').remove() });
            console.log('@@');
        };    

        return false;

    }); 



    // Info уточнения (analysis)

    jq( 'body' ).on( 'click', '.tools .info-clarifications', function() {
        
        // console.log('@@@');
        // let elem = jq( '.col', jq(this).closest('.row') ).parent();
        
        // console.log( jq(this).closest('.analysis-box').prev('div') );
        // console.log(jq( 'input[type="checkbox"]', jq(this).closest('.analysis-box').prev('div') ).val());

        jq.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'tools',
                do: 'info',
                courseid: jq(this).attr( 'data-id' ),
                opop: jq( 'input[name=opop]' ).val(),
                attid: jq( 'input[type="checkbox"]', jq(this).closest('.analysis-box').prev('div') ).val(),
                _wpnonce: jq( 'input[name=_wpnonce]' ).val(),
            },
            success: function( response ) {
                
                if ( response ) {

                    // jq('.analysis-box').remove();
                    // elem.after( response );
                    // elem.next("div").slideDown();
                    // console.log( response );
                    jq('.analysis-box').html( response );
                    
                } else {
                    
                    console.log( 'error 15' );
                    
                }
                
            },
            error: function( response ) {
                
                console.log( 'error 16' );
                
            },
            
        } );

 
        return false;

    }); 

    

    
    // analysis-box

    // cancel-panel

    jq( 'body' ).on( 'click', '.analysis-box .cancel-panel', function() {
        jq('.analysis-box').slideUp( function() { jq('.analysis-box').remove() } );
        return false;
    });  



    // remove-panel

    jq( 'body' ).on( 'click', '.analysis-box .remove-panel', function() {
    
        // console.log( jq( '.remove', jq(this).closest('.row').prev() ).html() );
        jq( '.remove', jq(this).closest('.row').prev() ).trigger('click') ;
    
    
        return false;
    });  

    
});