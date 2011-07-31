function lsStreamClass() {
    this.isBusy = false;
    this.subscribe = function (iTargetUserId) {
        new Request.JSON({
            url: aRouter['stream']+'subscribe/',
            data: {'id':iTargetUserId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                }else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            } 
        }).send();
    }
    this.unsubscribe = function (iId) {
        new Request.JSON({
            url: aRouter['stream']+'unsubscribe/',
            data: { 'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
    }
    this.switchEventType = function (iType) {
        new Request.JSON({
            url: aRouter['stream']+'switchEventType/',
            data: { 'type':iType, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
    }
    this.appendUser = function() {
        sLogin = $('stream_users_complete').get('value');
        if (!sLogin) return;
        new Request.JSON({
            url: aRouter['stream']+'subscribeByLogin/',
            data: {'login':sLogin, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                	if ($('stream_no_subscribed_users')) {
                    	$('stream_no_subscribed_users').dispose();
                	}
                    checkbox = $('strm_u_'+data.uid);
                    if (checkbox) {
                        if ($(checkbox).get('checked')) {
                            msgErrorBox.alert(lsLang.get('error'),lsLang.get('stream_subscribes_already_subscribed'));
                        } else {
                            $(checkbox).set('checked', 'on');
                            msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                        }
                    } else {
                        var liElement = new Element('li');
                        var checkboxElement = new Element('input', {
                            'type':'checkbox',
                            'class':'streamUserCheckbox',
                            'id':'strm_u_'+data.uid,
                            'checked':'checked',
                            'onClick':'if ($(this).get(\'checked\')) {lsStream.subscribe('+data.uid+')} else {lsStream.unsubscribe('+data.uid+')}'
                        });
                        checkboxElement.inject(liElement);
                        var linkElement = new Element('a', {
                           'href':data.user_web_path,
                           'html':data.user_login
                        });
                        linkElement.inject(liElement);
                        liElement.inject($('stream_block_users_list'));
                        msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                    }
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
    }
    this.getMore = function () {
        if (this.isBusy) {
            return;
        }
        lastId = $('stream_last_id').get('value');
        if (!lastId) return;
        $('stream_get_more').addClass('stream_loading');
        this.isBusy = true;
        new Request.JSON({
            url: aRouter['stream']+'get_more/',
            data: {'last_id':lastId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateErro && data.events_count) {
                    $('stream-list').set('html', $('stream-list').get('html')+data.result);
                    $('stream_last_id').set('value', data.iStreamLastId);
                }
                if (!data.events_count) {
                    $('stream_get_more').setStyles({'display':'none'});
                }
                $('stream_get_more').removeClass('stream_loading');
                lsStream.isBusy = false;
            }
        }).send();
    }
}
var lsStream  = new lsStreamClass;