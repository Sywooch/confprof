(function( $ ){
    var settings = {},
        obLength = null;

    var methods = {
        init : function( options ) {
            settings = $.extend( {
                'countselector': ''
            }, options);

            obLength = jQuery(settings.countselector);

            methods.show(this);

            return this.on('keyup.charcounter',function(){
                methods.show(jQuery(this));
            });

            //return this.each(function(index){
            //    jQuery(this).on('keyup.charcounter',function(){
            //        console.log(jQuery(this).val().length);
            //    });
            //});
        },

        show: function(oText) {
            obLength.text(oText.val().length);
//            console.log(oText.val().length);
        },

        destroy : function( ) {
            return this.each(function(){
                jQuery(this).off('.charcounter');
            })
        }
    };

    $.fn.charcounter = function( method ) {
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.charcounter' );
        }
    };
})( jQuery );
