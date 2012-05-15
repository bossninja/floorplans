jQuery(function($) {
	
	// generate a slug when the user types a title in
	pyro.generate_slug('input[name="title"]', 'input[name="slug"]');
	
	// edit images with ajax
	$(document).bind('cbox_complete',function(){
		$.colorbox.resize();
		$('#cboxLoadedContent form').bind('submit',function(){
			var action = $(this).attr('action');
			if(action.search(/admin\/files\/edit/) > -1){
				$.ajax({
					url: action,
					type:'POST',
					data:$(this).serialize(),
					success: function(data){
						if(data.status){
							$(window).bind('notification-closed.editfile',function(e){
								console.log('winning!');
								$.colorbox.resize();
								$(window).unbind('notification-closed.editfile');
							});
							$('#cboxLoadedContent h2').after(data.message)
							window.delayint = window.setInterval(function(){
								$.colorbox.resize();
								console.log('delay');
								clearInterval(window.delayint);
							},120);
						}
					}
				});
			}
			return false;
		})
	})
	
	
	// update the folder images preview when folder selection changes
	$('select#folder_id').change(function(){

		$.get(SITE_URL + 'admin/floorplans/ajax_select_folder/' + $(this).val(), function(data) {

			if (data) {
				$('input[name=title]').val(data.name);
				$('input[name=slug]').val(data.slug);
				
				// remove images from last selection
				$('#gallery_images_list').empty();
				$('#gallery_thumbnail optgroup, .images-manage').remove();
				
				if (data.images) {
					
					$('#gallery_thumbnail').append(
						'<option value="">No Thumbnail</option>'
					);
					
					$.each(data.images, function(i, image){
						$('#gallery_images_list').append(
						'<li>' +
							'<img src="' + SITE_URL + 'files/thumb/' + image.id + '" alt="' + image.name + '" title="Title: ' + image.name + ' -- Caption: ' + image.description + '"' +
						'</li>'
						);
						
						$('#gallery_thumbnail').append(
							'<option value="' + image.id + '">' + image.name + '</option>'
						);
					});
					$('.images-placeholder').slideDown();

					// update chosen after adding the thumbnails in
					$('#gallery_thumbnail').trigger('liszt:updated');
				}
			}
			else {
				$('input[name=title]').val('');
				$('input[name=slug]').val('');
				$('.images-placeholder').hide();
			}

		}, 'json');
	});

	// Add Features
	$('tfoot.features .add').click(function(e) {
		e.preventDefault();
		
		var index = $('tr.feature.new').length + 1;
		
		$('<tr id="newfeature_'+index+'" class="feature new">\
			<td class="name"><div class="input type-text"><input type="text" name="newfeatures['+index+'][name]" value=""/></div></td>\
			<td class="actions">\
				<input type="hidden" name="newfeatures['+index+'][delete]" value="0"/>\
				<button class="btn red delete"><span>Delete</span></button>\
			</td>\
		</tr>').appendTo($('tbody.features'));		
	});

	// Delete Features
	$('tbody.features .delete').live('click',function(e) {

		e.preventDefault();

		var item = $(this).parents('tr');

		if(!item.hasClass('deleted')) {		
			item.addClass('deleted');
			$(this).removeClass('red');
			$(this).addClass('gray');
			$(this).find('span').text('Undelete');
			item.find('input[name*="[delete]"]').val(1);
		} else {
			item.removeClass('deleted');
			$(this).removeClass('gray');
			$(this).addClass('red');
			$(this).find('span').text('Delete');
			item.find('input[name*="[delete]"]').val(0);
		}
	});

});
