var ls = ls || {};

ls.photoset =( function ($) {

	this.idLast=0;
	this.isLoading=false;
	this.swfu;

	/**
	 * Инициализация
	 */
	this.init = function() {
		var self = this;

		$('#js-photoset-image-upload').on('change', function (e) {
			self.upload();
		});
	};

	this.initSwfUpload = function(opt) {
		opt=opt || {};
		opt.button_placeholder_id = 'js-photoset-image-upload-flash';
		opt.post_params.ls_photoset_target_tmp = $.cookie('ls_photoset_target_tmp') ? $.cookie('ls_photoset_target_tmp') : 0;

		$(ls.swfupload).unbind('load').bind('load',function() {
			this.swfu = ls.swfupload.init(opt);

			$(this.swfu).bind('eUploadProgress',this.swfHandlerUploadProgress);
			$(this.swfu).bind('eFileDialogComplete',this.swfHandlerFileDialogComplete);
			$(this.swfu).bind('eUploadSuccess',this.swfHandlerUploadSuccess);
			$(this.swfu).bind('eUploadComplete',this.swfHandlerUploadComplete);
		}.bind(this));

		ls.swfupload.loadSwf();
	}

	this.swfHandlerUploadProgress = function(e, file, bytesLoaded, percent) {
		$('#photoset_photo_empty_filename').text(file.name);
		$('#photoset_photo_empty_progress').find('.js-upload-label').text(percent >= 100  ? 'resize..' : percent +'%');
		$('#photoset_photo_empty_progress').find('.js-upload-percents').css({ 'width' : percent + '%' });
	}

	this.swfHandlerFileDialogComplete = function(e, numFilesSelected, numFilesQueued) {
		if (numFilesQueued>0) {
			ls.photoset.addPhotoEmpty();
		}
	}

	this.swfHandlerUploadSuccess = function(e, file, serverData) {
		ls.photoset.addPhoto(jQuery.parseJSON(serverData));
	}

	this.swfHandlerUploadComplete = function(e, file, next) {
		if (next>0) {
			ls.photoset.addPhotoEmpty();
		}
	}

	this.addPhotoEmpty = function() {
		template = '<li id="photoset_photo_empty" class="photoset-upload-progress">' +
		               '<div id="photoset_photo_empty_filename" class="photoset-upload-progress-filename"></div>' +
		               '<div id="photoset_photo_empty_progress" class="progress-bar">' +
		                   '<div class="progress-bar-value js-upload-percents"></div>' +
		                   '<div class="progress-bar-label js-upload-label">Uploading...</div>' +
		               '</div>' +
		           '</li>';
		$('#swfu_images').append(template);
	}

	this.addPhoto = function(response) {
		$('#photoset_photo_empty').remove();
		if (!response.bStateError) {
			template = '<li id="photo_'+response.id+'" class="photoset-upload-images-item"><img src="'+response.file+'" alt="image" />'
						+'<textarea onBlur="ls.photoset.setPreviewDescription('+response.id+', this.value)" class="width-full"></textarea><br />'
						+'<a href="javascript:ls.photoset.deletePhoto('+response.id+')" class="link-dotted">'+ls.lang.get('topic_photoset_photo_delete')+'</a>'
						+'<span id="photo_preview_state_'+response.id+'" class="photo-preview-state"><a href="javascript:ls.photoset.setPreview('+response.id+')" class="link-dotted mark-as-preview">'+ls.lang.get('topic_photoset_mark_as_preview')+'</a></span></li>';
			$('#swfu_images').append(template);
		} else {
			ls.msg.error(response.sMsgTitle,response.sMsg);
		}
		$('#modal-photoset-upload').modal('hide');
	}

	this.deletePhoto = function(id)
	{
		if (!confirm(ls.lang.get('topic_photoset_photo_delete_confirm'))) {return;}
		ls.ajax(aRouter['photoset']+'deleteimage', {'id':id}, function(response){
			if (!response.bStateError) {
				$('#photo_'+id).remove();
				ls.msg.notice(response.sMsgTitle,response.sMsg);
			} else {
				ls.msg.error(response.sMsgTitle,response.sMsg);
			}
		});
	}

	this.setPreview =function(id)
	{
		$('#topic_main_photo').val(id);

		$('.marked-as-preview').each(function (index, el) {
			$(el).removeClass('marked-as-preview');
			tmpId = $(el).attr('id').slice($(el).attr('id').lastIndexOf('_')+1);
			$('#photo_preview_state_'+tmpId).html('<a href="javascript:ls.photoset.setPreview('+tmpId+')" class="mark-as-preview link-dotted">'+ls.lang.get('topic_photoset_mark_as_preview')+'</a>');
		});
		$('#photo_'+id).addClass('marked-as-preview');
		$('#photo_preview_state_'+id).html(ls.lang.get('topic_photoset_is_preview'));
	}

	this.setPreviewDescription = function(id, text)
	{
		ls.ajax(aRouter['photoset']+'setimagedescription', {'id':id, 'text':text},  function(result){
			if (!result.bStateError) {

			} else {
				ls.msg.error('Error','Please try again later');
			}
		}
		)
	}

	this.getMore = function(topic_id)
	{
		if (this.isLoading) return;
		this.isLoading=true;

		ls.ajax(aRouter['photoset']+'getmore', {'topic_id':topic_id, 'last_id':this.idLast}, function(result){
			this.isLoading=false;
			if (!result.bStateError) {
				if (result.photos) {
					$.each(result.photos, function(index, photo) {
						var image = '<li><a class="photoset-image" href="'+photo.path+'" rel="[photoset]" title="'+photo.description+'"><img src="'+photo.path_thumb+'" alt="'+photo.description+'" /></a></li>';
						$('#topic-photo-images').append(image);
						this.idLast=photo.id;
						$('.photoset-image').unbind('click');
						$('.photoset-image').prettyPhoto({
							social_tools:'',
							show_title: false,
							slideshow:false,
							deeplinking: false
						});
					}.bind(this));
				}
				if (!result.bHaveNext || !result.photos) {
					$('#topic-photo-more').remove();
				}
			} else {
				ls.msg.error('Error','Please try again later');
			}
		}.bind(this));
	}

	this.upload = function() {
		ls.photoset.addPhotoEmpty();

		var input = $('#js-photoset-image-upload');
		var form = $('<form method="post" enctype="multipart/form-data">' +
					     '<input type="hidden" name="is_iframe" value="true" />' +
					     '<input type="hidden" name="topic_id" value="' + input.data('topic-id') + '" />' +
					 '</form>').hide().appendTo('body');

		input.clone(true).insertAfter(input);
		input.appendTo(form);

		ls.ajaxSubmit(aRouter['photoset'] + 'upload/', form, function (data) {
			if (data.bStateError) {
				$('#photoset_photo_empty').remove();
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				ls.photoset.addPhoto(data);
			}
			form.remove();
		});
	}

	return this;
}).call(ls.photoset || {},jQuery);