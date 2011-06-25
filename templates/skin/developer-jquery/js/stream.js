ls.stream =( function ($) {
    this.isBusy = false;
    this.subscribe = function (iTargetUserId) {
        jQuery.post(aRouter['stream']+'subscribe', {'id':iTargetUserId,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.unsubscribe = function (iId) {
         jQuery.post(aRouter['stream']+'unsubscribe', {'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.switchEventType = function (iType) {
         jQuery.post(aRouter['stream']+'switchEventType', { 'type':iType, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.appendUser = function() {
        var sLogin = jQuery('#stream_users_complete').val();
        if (!sLogin) return;
         jQuery.post(aRouter['stream']+'subscribeByLogin', {'login':sLogin,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) {
            if (!data.bStateError) {
                var checkbox = jQuery('#strm_u_'+data.uid);
                if (checkbox.length) {
                    if (checkbox.attr('checked')) {
                        ls.msg.error(data.lang_error_title,data.lang_error_msg);
                    } else {
                        checkbox.attr('checked', 'on');
                        ls.msg.notice(data.sMsgTitle,data.sMsg);
                    }
                } else {
                    var liElement='<li><input type="checkbox" class="streamUserCheckbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if (jQuery(this).get(\'checked\')) {ls.stream.subscribe(\'users\','+data.uid+')} else {ls.stream.unsubscribe(\'users\','+data.uid+')}" /><a href="'+data.user_web_path+'">'+data.user_login+'</a></li>';
                    jQuery('#stream_block_users_list').append(liElement);
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            }
        });
    }
    this.getMore = function () {
        if (this.isBusy) {
            return;
        }
        var lastId = jQuery('#stream_last_id').val();
        if (!lastId) return;
        jQuery('#stream_get_more').addClass('stream_loading');
        this.isBusy = true;
        jQuery.post(aRouter['stream']+'get_more', {'last_id':lastId,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) {
            if (!data.bStateError && data.topics_count) {
                jQuery('#stream_loaded_topics').append(data.result);
                jQuery('#stream_last_id').attr('value', data.iStreamLastId);
            }
            if (!data.topics_count) {
                jQuery('#stream_get_more').css({'display':'none'});
            }
            jQuery('#stream_get_more').removeClass('stream_loading');
            ls.stream.isBusy = false;
        });
    }
    return this;
}).call(ls.stream || {},jQuery);