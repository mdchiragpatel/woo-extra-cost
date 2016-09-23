jQuery( function() {
	jQuery('.wc_extra_cost .remove_tax_rates').click(function() {
		
		var $tbody = jQuery('.wc_extra_cost').find('tbody');
		if ( $tbody.find('tr.current').size() > 0 ) {
			$current = $tbody.find('tr.current');
			$current.find('input').val('');
			$current.find('input.remove_cost_rate').val('1');

			$current.each(function(){
				if ( jQuery(this).is('.new') )
					jQuery(this).remove();
				else
					jQuery(this).hide();
			});
		} else {
			alert('No row(s) selected');
		}
		return false;
	});



	jQuery('.wc_extra_cost .insert').click(function() {
		
		var $tbody = jQuery('.wc_extra_cost').find('tbody');
		var size = $tbody.find('tr').size();
		
		var code = '<tr class="new">\
				<td width="4%" class="sort"></td>\
				<td class="name" width="8%">\
					<input type="text" name="extra_cost_country_code[new-' + size + ']" />\
				</td>\
				<td class="name" width="40%">\
					<input type="text" name="extra_cost_name[new-' + size + ']" />\
				</td>\
				<td class="rate" width="48%">\
					<input type="number" step="any" min="0" placeholder="0" name="extra_cost_value[new-' + size + ']" />\
				</td>\
			</tr>';
		 
		if ( $tbody.find('tr.current').size() > 0 ) {
			$tbody.find('tr.current').after( code );
		} else {
			$tbody.append( code );
		}

		return false;
	});
	
		jQuery('body').on( 'click', '.woo-extra-cost-notice .notice-dismiss', function() {
		
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'my_dismiss_extra_cost_notice'
			}
		})

	});
});