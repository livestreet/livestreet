var ls = ls || {};

ls.photoset =( function ($) {
	
	this.idLast=0;
	this.isLoading=false;
	
	this.addPhoto = function(response)
	{
		if (!response.bStateError) {
			template = '<li id="photo_'+response.id+'"><a href="#"><img src="'+response.file+'" alt="image" /></a>'
						+'<textarea onBlur="ls.photoset.setPreviewDescription('+response.id+', this.value)"></textarea><br />'
						+'<a href="javascript:ls.photoset.deletePhoto('+response.id+')" class="image-delete">'+ls.lang.get('topic_photoset_photo_delete')+'</a>'
						+'<span id="photo_preview_state_'+response.id+'" class="photo-preview-state"><a href="javascript:ls.photoset.setPreview('+response.id+')" class="mark-as-preview">'+ls.lang.get('topic_photoset_mark_as_preview')+'</a></span></li>';
			$('#swfu_images').append(template);
			ls.msg.notice(response.sMsgTitle,response.sMsg);
		} else {
			ls.msg.error(response.sMsgTitle,response.sMsg);
		}
		photosetCloseForm();
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
			$('#photo_preview_state_'+tmpId).html('<a href="javascript:ls.photoset.setDescription('+tmpId+')" class="mark-as-preview">'+ls.lang.get('topic_photoset_mark_as_preview')+'</a>');
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

	this.upload = function()
	{
		ls.ajaxSubmit(aRouter['photoset']+'upload/',$('#photoset-upload-form'),function(data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				ls.photoset.addPhoto(data);
			}
		});
		ls.photoset.closeForm();
	}

	this.closeForm = function()
	{
		$('#photoset-upload-form').css('left', '-300px');
	}

	this.showForm = function()
	{
		if ($('#photoset-upload-file').length) {
			$('#photoset-upload-file').val( '');
		}
		$('#photoset-upload-form').css('left', '50%');
	}
	return this;
}).call(ls.photoset || {},jQuery);