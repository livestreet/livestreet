var ls = ls || {};

ls.photoset =( function ($) {
    this.addPhoto = function(response)
    { 
        if (!response.bStateError) {
            template = '<li id="photo_'+response.id+'"><a href="#"><img src="'+response.file+'" alt="image" /></a>'
                                +'<textarea onBlur="ls.photoset.setPreviewDescription('+response.id+', this.value)"></textarea><br />'
                                +'<a href="javascript:ls.photoset.deletePhoto('+response.id+')" class="image-delete">'+lsLang['delete']+'</a>'
                                +'<span class="photo-preview-state"><a href="javascript:ls.photoset.setPreview('+response.id+')" class="mark-as-preview">'+lsLang['mark_as_preview']+'</a></span></li>';
            $('#swfu_images').append(template);
            ls.msg.notice(response.sMsgTitle,response.sMsg);
        } else {
            ls.msg.error(response.sMsgTitle,response.sMsg);
        }
        photosetCloseForm();
    }

    this.deletePhoto = function(id)
    {
        ls.ajax(aRouter['photoset']+'deleteimage', {'id':id}, function(response){
                        if (!response.bStateError) {
                            $('#photo_'+id).remove();
                           ls.msg.notice(response.sMsgTitle,response.sMsg);
                        } else {
                           ls.msg.error('Error','Please try again later');
                        }
                });
    }

    this.setPreview =function(id)
    {
        $('#topic_main_photo').val(id);

        $('.marked-as-preview').each(function (index, el) {
            $(el).removeClass('marked-as-preview');
            $(el).children('span').html('<a href="javascript:ls.photoset.setDescription('+$(el).attr('id').slice($(el).attr('id').lastIndexOf('_')+1)+')" class="mark-as-preview">'+lsLang['mark_as_preview']+'</a>');
        });
        $('#photo_'+id).addClass('marked-as-preview');
        $('#photo_'+id).children('span').html(lsLang['preview']);
    }

    this.setPreviewDescription = function(id, text)
    {
        ls.ajax(aRouter['photoset']+'setimagedescription', {'id':id, 'text':text},  function(result){
                        if (!bStateError) {

                        } else {
                            ls.msg.error('Error','Please try again later');
                        }
                   }
            )
    }

    this. getMore = function(topic_id)
    {
        var last_id = $('#last_photo_id').val();
        ls.ajax(aRouter['photoset']+'getmore', {'topic_id':topic_id, 'last_id':last_id}, function(result){
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
                            ls.msg.error('Error','Please try again later');
                        }
        });
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