ls.userfeed =( function ($) {
    this.isBusy = false;
    this.subscribe = function (sType, iId) {
        jQuery.post(aRouter['feed']+'subscribe', {'type':sType, 'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.unsubscribe = function (sType, iId) {
         jQuery.post(aRouter['feed']+'unsubscribe', {'type':sType, 'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.appendUser = function() {
        var sLogin = jQuery('#userfeed_users_complete').val();
        if (!sLogin) return;
         jQuery.post(aRouter['feed']+'subscribeByLogin', {'login':sLogin,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) {
                if (!data.bStateError) {
                    var checkbox = jQuery('#usf_u_'+data.uid);
                    if (checkbox.length) {
                        if (checkbox.attr('checked')) {
                            ls.msg.error(data.lang_error_title,data.lang_error_msg);
                        } else {
                            checkbox.attr('checked', 'on');
                            ls.msg.notice(data.sMsgTitle,data.sMsg);
                        }
                    } else {
                        var liElement='<li><input type="checkbox" class="userfeedUserCheckbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if (jQuery(this).get(\'checked\')) {ls.userfeed.subscribe(\'users\','+data.uid+')} else {ls.userfeed.unsubscribe(\'users\','+data.uid+')}" /><a href="'+data.user_web_path+'">'+data.user_login+'</a></li>';
                        jQuery('#userfeed_block_users_list').append(liElement);
                        ls.msg.notice(data.sMsgTitle,data.sMsg);
                    }
                }
            });
    }
    this.getMore = function () {
        if (this.isBusy) {
            return;
        }
        var lastId = jQuery('#userfeed_last_id').val();
        if (!lastId) return;
        jQuery('#userfeed_get_more').addClass('userfeed_loading');
        this.isBusy = true;
        jQuery.post(aRouter['feed']+'get_more', {'last_id':lastId,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) {
            if (!data.bStateError && data.topics_count) {
                jQuery('#userfeed_loaded_topics').append(data.result);
                jQuery('#userfeed_last_id').attr('value', data.iUserfeedLastId);
            }
            if (!data.topics_count) {
                jQuery('#userfeed_get_more').css({'display':'none'});
            }
            jQuery('#userfeed_get_more').removeClass('userfeed_loading');
            ls.userfeed.isBusy = false;
        });
    }
    return this;
}).call(ls.userfeed || {},jQuery);