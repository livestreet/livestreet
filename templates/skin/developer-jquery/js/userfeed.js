var ls = ls || {};

ls.userfeed =( function ($) {
    this.isBusy = false;
    this.subscribe = function (sType, iId) {
       ls.ajax(aRouter['feed']+'subscribe/', {'type':sType, 'id':iId}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.unsubscribe = function (sType, iId) {
         ls.ajax(aRouter['feed']+'unsubscribe/', {'type':sType, 'id':iId}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.appendUser = function() {
        var sLogin = $('#userfeed_users_complete').val();
        if (!sLogin) return;
         ls.ajax(aRouter['feed']+'subscribeByLogin/', {'login':sLogin}, function(data) {
                if (data.bStateError) {
                	ls.msg.error(data.sMsgTitle,data.sMsg);
                } else {
                    $('#userfeed_no_subscribed_users').remove();
                    var checkbox = $('#usf_u_'+data.uid);
                    if (checkbox.length) {
                        if (checkbox.attr('checked')) {
                            ls.msg.error(data.lang_error_title,data.lang_error_msg);
                        } else {
                            checkbox.attr('checked', 'on');
                            ls.msg.notice(data.sMsgTitle,data.sMsg);
                        }
                    } else {
                        var liElement='<li><input type="checkbox" class="userfeedUserCheckbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if ($(this).get(\'checked\')) {ls.userfeed.subscribe(\'users\','+data.uid+')} else {ls.userfeed.unsubscribe(\'users\','+data.uid+')}" /><a href="'+data.user_web_path+'">'+data.user_login+'</a></li>';
                        $('#userfeed_block_users_list').append(liElement);
                        ls.msg.notice(data.sMsgTitle,data.sMsg);
                    }
                }
            });
    }
    this.getMore = function () {
        if (this.isBusy) {
            return;
        }
        var lastId = $('#userfeed_last_id').val();
        if (!lastId) return;
        $('#userfeed_get_more').addClass('userfeed_loading');
        this.isBusy = true;
        ls.ajax(aRouter['feed']+'get_more/', {'last_id':lastId}, function(data) {
            if (!data.bStateError && data.topics_count) {
                $('#userfeed_loaded_topics').append(data.result);
                $('#userfeed_last_id').attr('value', data.iUserfeedLastId);
            }
            if (!data.topics_count) {
                $('#userfeed_get_more').css({'display':'none'});
            }
            $('#userfeed_get_more').removeClass('userfeed_loading');
            this.isBusy = false;
        }.bind(this));
    }
    return this;
}).call(ls.userfeed || {},jQuery);