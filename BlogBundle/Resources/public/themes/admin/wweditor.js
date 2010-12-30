( function (jQuery, $) {

    jQuery.fn.wweditor = function ( options ) {
        options = jQuery.extend ({} , jQuery.fn.wweditor.defaults, options);

        return $(this).each(function() {
            var editor = $(this);
            $(this).find('#writepost_post_text').markItUp(options.markitupSettings.rst).tabby({
                tabString:"    "
            });

            var split = function ( val ) {
                return val.split( /,\s*/ );
            }
            var extractLast = function( term ) {
                return split( term ).pop();
            }
            editor.find('#writepost_tags').autocomplete({
                source: function( request, response ) {
                    $.getJSON( options.tagRoute, {
                        term: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
            
            // Theme Buttons
            editor.find('input[type="submit"]').button();
        });
    }
    jQuery.fn.wweditor.defaults = {
        markitupSettings: {
            rst: {},
            html: {}
        },
        tagRoute: ""
    };
}) (jQuery, jQuery) ;
