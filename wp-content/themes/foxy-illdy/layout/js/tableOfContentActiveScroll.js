$ = jQuery;
$( document ).ready( function(){
	if( $( "div.widget_lpwtoc_widget" ).length ){
		// Function to set the headingObject easily by index
		var getHeadingObj = function( index ){
			return ( ( index < 0 )
					? { offset: 0 }
					: { index: index, offset: headerArray[ index ].offset } );
		}

		// To avoid incorrect behaviour, some offset was defined to work nicely with a sticky header on top of page
		var PREVIOUS_SCROLL_OFFSET = 150;

		// Construct the header Array
		var headerArray = new Array();
		$(":header span").each( function(){ headerArray.push( { offset: $( this).offset().top - PREVIOUS_SCROLL_OFFSET, ref: this.id } ); } );

		// Initiate the headings
		var prevHeading;
		var currHeading;
		var nextHeading = getHeadingObj( 0 );

		var lastScrollPos = 0;

		$(window).scroll(function(){
			let scrollPos = $(window).scrollTop();
			
			// Scrolling down, check next heading - as long as this is not the last
			if( scrollPos > lastScrollPos && nextHeading ){
				while( scrollPos >= nextHeading.offset ){
					// shift all objects
					prevHeading = currHeading;
					currHeading = nextHeading;

					// set next heading, when current is not the last one
					let nextHeadingIndex = ( !currHeading || currHeading.index == headerArray.length - 1 ) ? undefined : currHeading.index + 1;
					nextHeading = ( nextHeadingIndex ) ? getHeadingObj( nextHeadingIndex ) : undefined;
				}
			} else if( scrollPos < lastScrollPos && currHeading ){
				while( scrollPos <= currHeading.offset ){
					// Scrolling up, check previous heading - as long as the current is not the first;
					nextHeading = currHeading;
					currHeading = prevHeading;
					
					// set next heading, when current is not the last one
					let prevHeadingIndex = ( !currHeading || currHeading.index == 0 ) ? undefined : currHeading.index - 1;
					prevHeading = ( prevHeadingIndex ) ? getHeadingObj( prevHeadingIndex ) : undefined;
				}
			}
			
			// update select class
			$( ".lwptoc_item a" ).removeClass( 'activeScroll' );
			if( currHeading != undefined && currHeading.index >= 0 ){
				$(".lwptoc_item a[href='#"+ headerArray[ currHeading.index ].ref +"']").addClass( 'activeScroll' );
			}
			
			// update lastScrollPos to allow a bit better performance (not fetching next and prev heading)
			lastScrollPos = scrollPos;
		});
	}
});