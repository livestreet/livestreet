var ls = ls || {};

ls.stream =( function ($) {
    this.isBusy = false;
    this.subscribe = function (iTargetUserId) {
        ls.ajax(aRouter['stream']+'subscribe/', {'id':iTargetUserId}, function(data) { 
                if (data.bStateError) {
                    ls.msg.error(data.sMsgTitle,data.sMsg);
                } else {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
                
            });
    }
    this.unsubscribe = function (iId) {
         ls.ajax(aRouter['stream']+'unsubscribe/', {'id':iId}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.switchEventType = function (iType) {
         ls.ajax(aRouter['stream']+'switchEventType/', { 'type':iType}, function(data) { 
                if (!data.bStateError) {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            });
    }
    this.appendUser = function() {
        var sLogin = $('#stream_users_complete').val();
        if (!sLogin) return;
        ls.ajax(aRouter['stream']+'subscribeByLogin/', {'login':sLogin}, function(data) {
            if (!data.bStateError) {
                $('#stream_no_subscribed_users').remove();
                var checkbox = $('#strm_u_'+data.uid);
                if (checkbox.length) {
                    if (checkbox.attr('checked')) {
                        ls.msg.error(ls.lang.get('error'),ls.lang.get('stream_subscribes_already_subscribed'));
                    } else {
                        checkbox.attr('checked', 'on');
                        ls.msg.notice(data.sMsgTitle,data.sMsg);
                    }
                } else {
                    var liElement='<li><input type="checkbox" class="streamUserCheckbox input-checkbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if ($(this).get(\'checked\')) {ls.stream.subscribe(\'users\','+data.uid+')} else {ls.stream.unsubscribe(\'users\','+data.uid+')}" /> <a href="'+data.user_web_path+'">'+data.user_login+'</a></li>';
                    $('#stream_block_users_list').append(liElement);
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                }
            } else {
                ls.msg.error(data.sMsgTitle,data.sMsg);
            }
        });
    }
    this.getMore = function () {
        if (this.isBusy) {
            return;
        }
        var lastId = $('#stream_last_id').val();
        if (!lastId) return;
        $('#stream_get_more').addClass('stream_loading');
        this.isBusy = true;
        ls.ajax(aRouter['stream']+'get_more/', {'last_id':lastId}, function(data) {
            if (!data.bStateError && data.events_count) {
                $('#stream-list').append(data.result);
                $('#stream_last_id').attr('value', data.iStreamLastId);
            }
            if (!data.events_count) {
                $('#stream_get_more').css({'display':'none'});
            }
            $('#stream_get_more').removeClass('stream_loading');
            this.isBusy = false;
        }.bind(this));
    }
    return this;
}).call(ls.stream || {},jQuery);