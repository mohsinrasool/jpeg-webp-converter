<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://meticulousolutions.com
 * @since      1.0.0
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/admin/partials
 */
?>
<style type="text/css">
	#jwc_wrap .source,
	#jwc_wrap .destination {
		width: 300px;
	}

	#jwc_wrap .media_botton {
		/*width:;*/
	}

</style>
<div class="wrap" id="jwc_wrap">
    <h1>URL Mappings</h1>


    <table class="form-table">
    	<thead>
    		<tr>
    			<th scope="column">Source</th>
    			<th scope="column">Destination</th>
    			<th scope="column">Action</th>
    		</tr>
    		<tr id="newRow">
    			<td><input type="text" class="source" name="source_url" id="source_url"><button class="media_button">Choose</button></td>
    			<td><input type="text" class="destination" name="destination_url" id="destination_url"><button class="media_button">Choose</button></td>
    			<td><button name="Add" onclick="addRow(this);">Add</button></td>
    		</tr>
    		<tr id="tmpRow" style="display: none;">
    			<td><input type="text" class="source" readonly="readonly" name="source_url" id="source_url"><button class="media_button">Choose</button></td>
    			<td><input type="text" class="destination" name="destination_url" id="destination_url"><button class="media_button">Choose</button></td>
    			<td><button name="Update" onclick="updateRow(this);">Update</button> <button name="Delete" onclick="deleteRow(this);">Delete</button></td>
    		</tr>
    	</thead>
    	<tbody id="records">
		<?php
    		$mappings = get_option( 'jwc_mappings', array() );

    		foreach ($mappings as $mapping) {
		?>
    		<tr>
    			<td><input type="text" class="source" name="source_url" readonly="readonly" value="<?=$mapping['source_url']?>" id="source_url"><button class="media_button">Choose</button></td>
    			<td><input type="text" class="destination" name="destination_url"  value="<?=$mapping['destination_url']?>" id="destination_url"><button class="media_button">Choose</button></td>
    			<td><input type="button" name="Update" value="Update" onclick="updateRow(this);"><input type="button" name="Delete" value="Delete" onclick="deleteRow(this);"></td>
    		</tr>    		
    	<?php
	    }
	    ?>
    	</tbody>
    </table>
    <form method="post" action="options.php">
   

    </form>
    
</div>


<script type="text/javascript">
	jQuery(document).on('click', '.media_button', function(e) {
        e.preventDefault();
        var button = jQuery(this);
        var id = button.prev();
        wp.media.editor.send.attachment = function(props, attachment) {
            id.val(attachment.url);
        };
        wp.media.editor.open(button);
        return false;
    });


	function addRow(e) {
		var newRow = jQuery(e).parents('tr').clone();

		var data = {
			'action': 'jwc_add_mapping',
			'source_url': jQuery(e).parents('tr').find('[name="source_url"]').val(),
			'destination_url': jQuery(e).parents('tr').find('[name="destination_url"]').val(),
		};

		if(data.source_url == '' || data.destination_url == '') {
			alert('Please enter non-empty source and destination URLs.');
			return false;
		}

		lockRow(jQuery('#newRow'));
		jQuery.post( 'admin-ajax.php', data, function( response ) {

			response = JSON.parse(response);
			if( response.status == 'success' ) {

				jQuery('#tmpRow').find('input[name="source_url"]').val(response.record.source_url);
				jQuery('#tmpRow').find('input[name="destination_url"]').val(response.record.destination_url);

				jQuery('#records').prepend(jQuery('#tmpRow').clone());
				jQuery('#records tr').each(function() {
					jQuery(this).removeAttr('id').css('display','');
				})

				jQuery('#newRow').find('input[type="text"]').each(function() {
					jQuery(this).val('');
				})

				// alert('Mapping successfully added.');
			} else {
				// An error occurred, alert an error message
				alert( response.message );
			}

			unlockRow(jQuery('#newRow'));
		});

	}


	function updateRow(e) {
		var newRow = jQuery(e).parents('tr').clone();

		var data = {
			'action': 'jwc_add_mapping',
			'source_url': jQuery(e).parents('tr').find('[name="source_url"]').val(),
			'destination_url': jQuery(e).parents('tr').find('[name="destination_url"]').val(),
		};

		if(data.source_url == '' || data.destination_url == '') {
			alert('Please enter non-empty source and destination URLs.');
			return false;
		}

		lockRow(jQuery(e).parents('tr'));

		jQuery.post( 'admin-ajax.php', data, function( response ) {

			response = JSON.parse(response);
			if( response.status == 'success' ) {

				// alert('Mapping successfully updated.');

			} else {
				// An error occurred, alert an error message
				alert( response.message );
			}

			unlockRow( getRowBySource(response.record.source_url) );
		});

	}


	function deleteRow(e) {

		if(!confirm('Are you sure?'))
			return false;

		var data = {
			'action': 'jwc_delete_mapping',
			'source_url': jQuery(e).parents('tr').find('[name="source_url"]').val(),
		};

		lockRow(jQuery(e).parents('tr'));

		jQuery.post( 'admin-ajax.php', data, function( response ) {
			response = JSON.parse(response);
			if( response.status == 'success' ) {

				jQuery( getRowBySource(response.record.source_url) ).remove();

				alert('Mapping successfully deleted.');
			} else {
				// An error occurred, alert an error message
				alert( response.message );

				unlockRow( getRowBySource(response.record.source_url) );
			}

		});
	}

	function lockRow(e) {
		jQuery(e).find('[name="source_url"]').attr('disabled','disabled');
		jQuery(e).find('[name="destination_url"]').attr('disabled','disabled');
		jQuery(e).find('button').attr('disabled','disabled');
	}

	function unlockRow(e) {
		jQuery(e).find('[name="source_url"]').removeAttr('disabled');
		jQuery(e).find('[name="destination_url"]').removeAttr('disabled');
		jQuery(e).find('button').removeAttr('disabled');
	}

	function getRowBySource(source_url) {
		var row = null;
		jQuery('#records tr input[name="source_url"]').each(function () {
			if( jQuery(this).val() == source_url) 
				row = jQuery(this).parents('tr');
		});

		return row;
	}
</script>
