( function( $ ) {
	var current_url = window.location.href;
	var title = $( 'h1' ).text();

	var share_with_facebook = function() {
		window.open( 'https://www.facebook.com/sharer/sharer.php?u=' + current_url,  'facebook-share-dialog' );
	};
	var share_with_twitter = function() {
		window.open( 'https://twitter.com/intent/tweet?text=' + title + '&url=' + current_url );
	};
	var share_with_linkedin = function() {
		window.open( 'https://www.linkedin.com/shareArticle?mini=true&url=' + current_url + '&title=' + title );
	};
	var share_with_email = function() {
		window.location = 'mailto:?&subject=' + title + '&body=' + current_url;
	};

	$( '.social-icons-to-share-in-facebook' ).bind( 'click', share_with_facebook );
	$( '.social-icons-to-share-in-twitter' ).bind( 'click', share_with_twitter );
	$( '.social-icons-to-share-in-linkedin' ).bind( 'click', share_with_linkedin );
	$( '.social-icons-to-share-in-email' ).bind( 'click', share_with_email );
} )( jQuery );
