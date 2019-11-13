(function( $ ) {
	'use strict';

	$ = jQuery.noConflict();

	// window.send_to_editor(html) is how WP would normally handle the received data. It will deliver image data in HTML format, so you can put them wherever you want.

	window.send_to_editor = function(html) {

		console.log(window.current_upload_button);

		var input_box = $(window.current_upload_button).siblings('input')[0];

		console.log(html);

		var image_url = $j('img', html).attr('src');
		$j('#image_path').val(image_url);
		tb_remove(); // calls the tb_remove() of the Thickbox plugin
		$j('#submit_button').trigger('click');
	}


	// jQuery(document).ready(function() {

	//     var frame = wp.media({
	//          multiple: false
	//     });
	//     var current_row = null;

	//     $("#jwc_wrap .media").on("click", function(e) {
	//         frame.open();

	//         current_row = jQuery(this).siblings('input')[0];
	//         e.preventDefault();
	//     });

	//     frame.on('select', function() {
	//         console.log("Select");

	//         var selection = frame.state().get('selection');

	//         selection.each(function(attachment) {

	//             console.log(attachment);
	//         	jQuery(current_row).val(attachment.url);
	//         });


	//     });
	// });



})( jQuery );
