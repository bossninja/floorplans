jQuery(function($){

	// Bind an event to window.onhashchange that, when the hash changes, gets the
	// hash and adds reload contents
	$(window).hashchange(function(){
		var hash = location.hash.substr(2),
			uri,
			path = '';

		if (hash.match('path='))
		{
			uri = (hash == '' || ( ! (path = hash.match(/path=(.+?)(&.*|)$/)) && ! (hash = ''))) ? 'index' : 'contents/';
		}
		else
		{
			uri		= 'index';
			hash	= 'path=';
			path	= null;
		}

		if(FOLDER_ID != '') {
			//get images from folder
			$.get(SITE_URL + 'admin/floorplans/folders/contents/' + FOLDER_ID, '', function(data){
				alert(data.content);
				if (data.status == 'success')
				{
					data.navigation && $('#files-browser-nav').html(data.navigation);
					data.content && $('#files-browser-contents').html(data.content);

					//add sortable feature for images
					$('#gallery_images_list').sortable({
						handle: 'img',
						start: function(event, ui) {						
							ui.helper.find('a').unbind('click').die('click');
						},
						update: function() {
							order = new Array();
							$('li', this).each(function(){
								order.push( $(this).find('input[name="action_to[]"]').val() );
							});
							order = order.join(',');

							$.post(SITE_URL + 'admin/floorplans/ajax_update_order', { order: order });
						}

					}).disableSelection();

				}
				else if (data.status == 'error')
				{
					parent.location.hash = null;
					pyro.add_notification(data.message);
				}
				
				// Update Chosen
				pyro.chosen();

			}, 'json');
		}
		
		
	});

	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	$(window).hashchange();

	// Files -------------------------------------------------------

	$(".delete-file").livequery(function(){
		$(this).click(function(e){
			var url = $(this).attr('href');

			$.get(url,'' , function(data){

				if (data.status == 'success')
				{					
					pyro.add_notification(data.message, {
						clear: false
					});		
					$(window).hashchange();					
				}
				else if (data.status == 'error')
				{
					parent.location.hash = null;
					pyro.add_notification(data.message, {
						clear: false
					});
				}
				
				// Update Chosen
				pyro.chosen();

			}, 'json');
			return false;
		});
	});

	$(".edit_file").livequery(function(){
		$(this).colorbox({
			scrolling	: false,
			width		: '750',
			height		: '480',
			onComplete: function(){
				var form = $('form#files_crud'),
					$loading = $('#cboxLoadingOverlay, #cboxLoadingGraphic');

				$.colorbox.resize();
				
				// Update Chosen
				pyro.chosen();

				form.find(':input:last').keypress(function(e){
					if (e.keyCode == 9 && ! e.shiftKey)
					{
						e.preventDefault();
						form.find(':input:first').focus();
					}
				});

				form.find(':input:first').keypress(function(e){
					if (e.keyCode == 9 && e.shiftKey)
					{
						e.preventDefault();
						form.find(':input:last').focus();
					}
				});
			},
			onClosed: function(){}
		});
	});

	$('.open-files-uploader').livequery(function(){

		$(this).colorbox({
			scrolling	: false,
			inline		: true,
			href		: '#files-uploader',
			width		: '800',
			height		: '80%',
			onComplete	: function(){				
				$('#files-uploader-queue').empty();
				$.colorbox.resize();
			},
			onCleanup : function(){
				$(window).hashchange();
			}
		});
	});

	var upload_form = $('#files-uploader form'),
		upload_vars	= upload_form.data('fileUpload');

	upload_form.fileUploadUI({
		fieldName       : 'userfile',
		uploadTable     : $('#files-uploader-queue'),
		downloadTable   : $('#files-uploader-queue'),
		previewSelector : '.file_upload_preview div',
        cancelSelector  : '.file_upload_cancel button.cancel',
        
		buildUploadRow	: function(files, index, handler){	
			return $('<li><div class="file_upload_preview ui-corner-all"><div class="ui-corner-all"></div></div>' +
					'<div class="filename"><label for="file-name">' + files[index].name + '</label>' +
					'<input class="file-name" type="hidden" name="name" value="'+files[index].name+'" />' +
					'</div>' +
					'<div class="file_upload_progress"><div></div></div>' +
					'<div class="file_upload_cancel buttons buttons-small">' +
					'<button class="button start ui-helper-hidden-accessible"><span>' + upload_vars.lang.start + '</span></button>'+
					'<button class="button cancel"><span>' + upload_vars.lang.cancel + '</span></button>' +
					'</div>' +
					'</li>');
		},
		buildDownloadRow: function(response){
			if (response.message)
			{				
				pyro.add_notification(response.message, {
					clear: false
				});
			}
			if (response.status == 'success')
			{				
				return $('<li><div>' + response.file.name + '</div></li>');
			}
			return;
		},
		beforeSend: function(event, files, index, xhr, handler, callBack){
			handler.uploadRow.find('button.start').click(function(){
				handler.formData = {
					name: handler.uploadRow.find('input.file-name').val(),
					folder_id: $('input[name=folder_id]', '#files-toolbar').val()
				};
				callBack();
			});
		},
		onComplete: function (event, files, index, xhr, handler){
			handler.onCompleteAll(files);
		},
		onCompleteAll: function (files){
			if ( ! files.uploadCounter)
			{
				files.uploadCounter = 1;  
			}
			else
			{
				files.uploadCounter = files.uploadCounter + 1;
			}

			if (files.uploadCounter === files.length)
			{
				$('#files-uploader a.cancel-upload').click();
			}
		}
	});

	$('#files-uploader a.start-upload').click(function(e){
		e.preventDefault();
		$('#files-uploader-queue button.start').click();
	});

	$('#files-uploader a.cancel-upload').click(function(e){
		e.preventDefault();
		$('#files-uploader-queue button.cancel').click();
		$.colorbox.close();
	});
	
	$('#grid').livequery(function(){
		$('#grid').fadeIn();		
	});

	$('a.toggle-view').livequery('click', function(e){
		e.preventDefault();

		var view = $(this).attr('title');

		// remember the user's preference
		$.cookie('file_view', view);

		$('a.active-view').removeClass('active-view');
		$(this).addClass('active-view');
		
		view = 'grid';

		$('#'+hide_view).fadeOut(50, function() {
			$('#'+view).fadeIn(500);   
		});            
	});

});