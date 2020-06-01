$ = jQuery;
$( document ).ready( function(){
	// Construct the header Array
	var headers = $( ":header span" );

	// For mobile or when someone resizes screen, have Table of Content inside Responsive menu bar
	var headerArray = new Array();
	if( headers.length < 2 ){
		$( ".responsive-menu" ).empty().append( "<ul id='menu-main-menu' class='clearfix'><li class='menu-item'><strong>Responsive menu only defined for articles</strong></li></ul>" );
	} else{
		$( ".responsive-menu" ).empty().append( "<ul id='menu-main-menu' class='clearfix'><li class='menu-item'><strong>Article Content</strong></li></ul>" );
	}
	headers.each( function(){
		// Only for headers processed by the Lucky WP Table of Contents Plugin (those spans will have an id for references)
		if( this.id ){
			// Fetch heading-type and prepend that number of spaces in front - starting from h3, h4, ...
			let tag = this.parentElement.localName;
			let tagNum = ( tag && tag.length == 2 ) ? tag.replace( 'h', '' ) - 2 : undefined;
			let title = ( ( tagNum ) ? '&nbsp;&nbsp;'.repeat(  tagNum ) : '' ) + this.innerHTML;
			$( "ul#menu-main-menu" ).append( "<li class='menu-item'><a href='#"+ this.id + "'>"+ title +"</a></li>" );
		}
	} );
	$( "ul#menu-main-menu li.menu-item a" ).click( function(){
		$( ".open-responsive-menu" ).click();
	});

	// When Table of Content is rendered on this page, process headings and activate visible scroll
	if( $( "div.widget_lpwtoc_widget" ).length ){
		// Function to set the headingObject easily by index
		var getHeadingObj = function( index ){
			return ( ( index < 0 )
					? { offset: 0 }
					: { index: index, offset: headerArray[ index ].offset } );
		}

		// To avoid incorrect behaviour, exclude some deviation to smoothly work with sticky header on top of page
		var PREVIOUS_SCROLL_OFFSET = 150;
		headers.each( function(){
			headerArray.push( { offset: ( $( this ).offset().top - PREVIOUS_SCROLL_OFFSET ), ref: this.id } );
		} );

		// Initiate the headings
		var prevHeading;
		var currHeading;
		var nextHeading = getHeadingObj( 0 );

		var lastScrollPos = 0;

		$( window ).scroll( function(){
			let scrollPos = $( window ).scrollTop();
			
			// Scrolling down, check next heading - as long as this is not the last
			if( scrollPos > lastScrollPos ){
				while( nextHeading && scrollPos >= nextHeading.offset ){
					// shift all objects
					prevHeading = currHeading;
					currHeading = nextHeading;

					// set next heading, when current is not the last one
					let nextHeadingIndex = ( !currHeading || currHeading.index == headerArray.length - 1 ) ? undefined : currHeading.index + 1;
					nextHeading = ( nextHeadingIndex ) ? getHeadingObj( nextHeadingIndex ) : undefined;
				}
			} else if( scrollPos < lastScrollPos ){
				while( currHeading && scrollPos <= currHeading.offset ){
					// Scrolling up, check previous heading - as long as the current is not the first;
					nextHeading = currHeading;
					currHeading = prevHeading;
					
					// set next heading, when current is not the last one
					let prevHeadingIndex = ( !currHeading || currHeading.index == 0 ) ? undefined : currHeading.index - 1;
					prevHeading = ( prevHeadingIndex ) ? getHeadingObj( prevHeadingIndex ) : undefined;
				}
			}
			
			// update select class
			$( ".lwptoc_item a,.responsive-menu a" ).removeClass( 'activeScroll' );
			if( currHeading != undefined && currHeading.index >= 0 ){
				$(".lwptoc_item a[href='#"+ headerArray[ currHeading.index ].ref +"']").addClass( 'activeScroll' );
				$(".responsive-menu a[href='#"+ headerArray[ currHeading.index ].ref +"']").addClass( 'activeScroll' );
			}
			
			// update lastScrollPos to allow a bit better performance (not fetching next and prev heading)
			lastScrollPos = scrollPos;
		});
	}
});