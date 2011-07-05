var ls = ls || {};

ls.userfield =( function ($) {
    this.showAddForm = function(){
        $('#user_fields_form_name').val( '');
        $('#user_fields_form_title').val( '');
        $('#user_fields_form_id').val( '');
        $('#user_fields_form_action').val('add');
        $('#userfield_form').css('display','block');
    }
    
    this.showEditForm = function(id) {
        $('#user_fields_form_action').val('update');
        var name = $('#field_'+id+' .userfield_admin_name').html();
        var title = $('#field_'+id+' .userfield_admin_title').html();
        $('#user_fields_form_name').val(name);
        $('#user_fields_form_title').val(title);
        $('#user_fields_form_id').val(id);
        $('#userfield_form').css('display','block');
    }

    this.applyForm = function(){
        $('#userfield_form').css('display','none');
        if ($('#user_fields_form_action').val() == 'add') {
            this.addUserfield();
        } else if ($('#user_fields_form_action').val() == 'update')  {
            this.updateUserfield();
        }
    }

    this.addUserfield = function() {
        var name = $('#user_fields_form_name').val();
        var title = $('#user_fields_form_title').val();
         ls.ajax(aRouter['admin']+'userfields', {'action':'add', 'name':name,  'title':title,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
            if (!data.bStateError) {
                liElement = '<li id="filed_'+data.id+'">'+'<span class="userfield_admin_name">'+name+'</span >("<span class="userfield_admin_title">'+title+'</span>")'+' <a href="javascript:ls.userfield.showEditForm('+data.id+')">'+data.lang_delete+'</a> <a href="javascript:ls.userfield.deleteUserfield('+data.id+')">'+data.lang_delete+'</a>';
                $('#user_field_list').append(liElement);
                ls.msg.notice(data.sMsgTitle,data.sMsg);
            } else {
                ls.msg.error(data.sMsgTitle,data.sMsg);
            }
         });
    }

    this.updateUserfield = function() {
        var id = $('#user_fields_form_id').val();
        var name = $('#user_fields_form_name').val();
        var title = $('#user_fields_form_title').val();
         ls.ajax(aRouter['admin']+'userfields', {'action':'update', 'id':id, 'name':name,  'title':title, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
            if (!data.bStateError) {
                $('#field_'+id+' .userfield_admin_name').html(name);
                $('#field_'+id+' .userfield_admin_title').html(title);
                ls.msg.notice(data.sMsgTitle,data.sMsg);
            } else {
                ls.msg.error(data.sMsgTitle,data.sMsg);
            }
         });
    }

    this.deleteUserfield = function(id) {
        ls.ajax(aRouter['admin']+'userfields', {'action':'delete', 'id':id,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
            if (!data.bStateError) {
                $('#field_'+id).remove();
                ls.msg.notice(data.sMsgTitle,data.sMsg);
            } else {
                ls.msg.error(data.sMsgTitle,data.sMsg);
            }
        });
    }
    return this;
}).call(ls.userfield || {},jQuery);