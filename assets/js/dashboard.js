/**
 * Created by Oleg Malii
 * Date: 21.03.17 12:32
 */
;(function ($) {
    $(function() {
        /**
         * Save access key handler
         */
        $( '#save-access-key' ).on( 'click', function() {
            var data = {
                action: 'seosfwm_save_access_key',
                wp_access_key: $( 'input[name="wp_access_key"]' ).val(),
                security_field: $( 'input#wp-access-key_nonce' ).val()
            };

            $.post(ajaxurl, data, function (response) {
                show_message( 'save-access-key-response', response.message, response.done );
            }, 'json' );

        });

        function get_news_xml() {
            $.get( ajaxurl, { action: 'seosfwm_get_news' }, function ( response ) {
                if ( response ) {
                    build_news_list( response )
                }
            }, 'xml');
        }

        /**
         * Parse news XML
         * @param {Text} xml - response from seosamba.com sitemap-news
         * @return undefined
         */
        function build_news_list( xml ) {
            var urls = xml.documentElement.getElementsByTagName( 'url' );

            for ( var i = urls.length; i--; ) {
                var news = $( urls[i] ).children()[1];
                news = $( news ).children();
                var item = $( '<li>' ).addClass( 'panel' );
                item.append( $( '<div>' ).addClass( 'panel-content' )
                    .append( $( '<a>' ).addClass( 'fs14' ).css( {'color': '#83b421'} )
                        .attr( { 'href': $( 'loc', urls[i] ).text(), 'target': '_blank' } )
                        .text( $( news[2] ).text() ) ) );
                item.append( $( '<div>' ).addClass( 'panel-footer fs14' )
                    .append( $( '<i>' ) ).addClass( 'icon-calendar fs16' )
                    .append( $( '<span>' ).addClass( 'fs14' ).css( 'margin-left', '5px' )
                        .text( $( news[1] ).text().replace( /(\d{4}-\d{2}-\d{2})(.*)/, '$1' ) )
                    )
                );
                $( '#news-list' ).append( item );

                if((urls.length - 3) === i) {
                    break;
                }
            }
            $( '.news-block' ).addClass( 'scroll' ).removeClass( 'loading' );
        }

        /**
         *
         * @param {Text} elementId - id of message box element
         * @param {Text} message - message to display
         * @param {Boolean} status - success or error flag
         */
        function show_message( elementId, message, status ) {
            var message_element = $( '#' + elementId );
            message_element.removeClass( 'success error' );
            $( 'i', message_element ).removeClass( 'success error icon-checkmark icon-cancel' );

            if ( status ) {
                $( message_element ).addClass( 'success' );
                $( 'i', message_element ).addClass( 'success icon-checkmark' );
            } else {
                message_element.addClass( 'error' );
                $( 'i', message_element ).addClass( 'error icon-cancel' );
            }

            $( message_element ).removeClass( 'hidden' );
            $( 'b', message_element ).text( message );
        }

        /**
         * Switch between tabs
         */
        $( '.mojo-nav-tabs' ).on( 'click', '.mojo-tabs > li', function (e) {
            $( '.mojo-tabs > li' ).removeClass( 'ui-tabs-active' );
            $( this ).addClass( 'ui-tabs-active' );
            $( '[id^="tabs"]' ).addClass( 'hidden' );
            $( '#' + $( this ).data( 'tabid' ) ).removeClass( 'hidden' );
            return false;
        });

        get_news_xml();

    });
})(jQuery);