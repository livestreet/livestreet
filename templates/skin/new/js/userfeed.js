function lsUserfeedClass() {
    this.isBusy = false;
    this.subscribe = function (sType, iId) {
        new Request.JSON({
            url: aRouter['feed']+'subscribe/',
            data: {'type':sType, 'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
    }
    this.unsubscribe = function (sType, iId) {
        new Request.JSON({
            url: aRouter['feed']+'unsubscribe/',
            data: {'type':sType, 'id':iId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
    }
    this.appendUser = function() {
        sLogin = $('userfeed_users_complete').get('value');
        if (!sLogin) return;
        new Request.JSON({
            url: aRouter['feed']+'subscribeByLogin/',
            data: {'login':sLogin, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    if ($('userfeed_no_subscribed_users')) $('userfeed_no_subscribed_users').destroy();
                    checkbox = $('usf_u_'+data.uid);
                    if (checkbox) {
                        if ($(checkbox).get('checked')) {
                            msgErrorBox.alert(data.lang_error_title,data.lang_error_msg);
                        } else {
                            $(checkbox).set('checked', 'on');
                            msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                        }
                    } else {
                        var liElement = new Element('li');
                        var checkboxElement = new Element('input', {
                            'type':'checkbox',
                            'class':'userfeedUserCheckbox',
                            'id':'usf_u_'+data.uid,
                            'checked':'checked',
                            'onClick':'if ($(this).get(\'checked\')) {lsUserfeed.subscribe(\'users\','+data.uid+')} else {lsUserfeed.unsubscribe(\'users\','+data.uid+')}'
                        });
                        checkboxElement.inject(liElement);
                        var linkElement = new Element('a', {
                           'href':data.user_web_path,
                           'html':data.user_login
                        });
                        linkElement.inject(liElement);
                        liElement.inject($('userfeed_block_users_list'));
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
        lastId = $('userfeed_last_id').get('value');
        if (!lastId) return;
        $('userfeed_get_more').addClass('userfeed_loading');
        this.isBusy = true;
        new Request.JSON({
            url: aRouter['feed']+'get_more/',
            data: {'last_id':lastId, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError && data.topics_count) {
                    $('userfeed_loaded_topics').set('html', $('userfeed_loaded_topics').get('html')+data.result);
                    $('userfeed_last_id').set('value', data.iUserfeedLastId);
                }
                if (!data.topics_count) {
                    $('userfeed_get_more').setStyles({'display':'none'});
                }
                $('userfeed_get_more').removeClass('userfeed_loading');
                lsUserfeed.isBusy = false;
            }
        }).send();
    }
}
var lsUserfeed  = new lsUserfeedClass;