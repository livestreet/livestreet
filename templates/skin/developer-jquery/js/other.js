function addTopicImage(response)
{ 
    if (!response.bStateError) {
        template = '<li id="photo_'+response.id+'"><a href="#"><img src="'+response.file+'" alt="image" /></a>'
                            +'<textarea onBlur="topicImageSetDescription('+response.id+', this.value)"></textarea><br />'
                            +'<a href="javascript:deleteTopicImage('+response.id+')" class="image-delete">'+lsLang['delete']+'</a>'
                            +'<span class="photo-preview-state"><a href="javascript:setTopicMainPhoto('+response.id+')" class="mark-as-preview">'+lsLang['mark_as_preview']+'</a></span></li>';
        $('#swfu_images').append(template);
        ls.msg.notice(response.sMsgTitle,response.sMsg);
    } else {
        ls.msg.error(response.sMsgTitle,response.sMsg);
    }
    photosetCloseForm();
}

function deleteTopicImage(id)
{
    ls.ajax(aRouter['photoset']+'deleteimage', {'id':id, 'security_ls_key': LIVESTREET_SECURITY_KEY }, function(response){
                    if (!response.bStateError) {
                        $('#photo_'+id).remove();
                       ls.msg.notice(response.sMsgTitle,response.sMsg);
                    } else {
                       ls.msg.error('Error','Please try again later');
                    }
            });
}

function setTopicMainPhoto(id)
{
    $('#topic_main_photo').val(id);
    
    $('.marked-as-preview').each(function (index, el) {
        $(el).removeClass('marked-as-preview');
        $(el).children('span').html('<a href="javascript:setTopicMainPhoto('+$(el).attr('id').slice($(el).attr('id').lastIndexOf('_')+1)+')" class="mark-as-preview">'+lsLang['mark_as_preview']+'</a>');
    });
    $('#photo_'+id).addClass('marked-as-preview');
    $('#photo_'+id).children('span').html(lsLang['preview']);
}

function topicImageSetDescription(id, text)
{
    ls.ajax(aRouter['photoset']+'setimagedescription', {'id':id, 'text':text, 'security_ls_key': LIVESTREET_SECURITY_KEY },  function(result){
                    if (!bStateError) {
                        
                    } else {
                        msgErrorBox.alert('Error','Please try again later');
                    }
               }
        )
}

function getMorePhotos(topic_id)
{
    var last_id = $('#last_photo_id').val();
    ls.ajax(aRouter['photoset']+'getmore', {'topic_id':topic_id, 'last_id':last_id, 'security_ls_key': LIVESTREET_SECURITY_KEY }, function(result){
                    if (!result.bStateError) {
                        if (result.photos) {
                            var photoNumber = $('#photo_number').val();
                            $.each(result.photos, function(index, photo) {
                                var image = '<li><div class="image-number">'+(photoNumber++)+'</div><a class="photoset-image" href="'+photo.path+'" rel="[photoset]" title="'+photo.description+'"><img src="'+photo.path_thumb+'" alt="'+photo.description+'" /></a></li>';
                                $('#topic-photo-images').append(image);
                                $('#photo_number').val(photoNumber);
                                $('#last_photo_id').val(photo.id);
                                $('.photoset-image').unbind('click');
                                $('.photoset-image').prettyPhoto({
                                   social_tools:'',
                                   show_title: false,
                                   slideshow:false,
                                   deeplinking: false
                                });
                            });
                        } else {
                            $('#topic-photo-more').remove();
                        }
                    } else {
                        msgErrorBox.alert('Error','Please try again later');
                    }
    });
}

function photosetUploadPhoto() 
{       
        ls.ajaxSubmit(aRouter['photoset']+'upload/',$('#photoset-upload-form'),function(data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				addTopicImage(data);
			}
		});
         photosetCloseForm();
}

function photosetCloseForm() {
    $('#photoset-upload-form').css('left', '-300px');
}

function photosetShowUploadForm()
{
     if ($('#photoset-upload-file').length) {
        $('#photoset-upload-file').val( '');
    }
    $('#photoset-upload-form').css('left', '50%');
}