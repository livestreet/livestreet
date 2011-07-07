var ls = ls || {};

ls.userfield =( function ($) {
    this.showAddForm = function(){
        $('#user_fields_form_name').val( '');
        $('#user_fields_form_title').val( '');
        $('#user_fields_form_id').val( '');
        $('#user_fields_form_action').val('add');
        $('#userfield_form').jqmShow(); 
    }
    
    this.showEditForm = function(id) {
        $('#user_fields_form_action').val('update');
        var name = $('#field_'+id+' .userfield_admin_name').html();
        var title = $('#field_'+id+' .userfield_admin_title').html();
        var pattern = $('#field_'+id+' .userfield_admin_pattern').text();
        $('#user_fields_form_name').val(name);
        $('#user_fields_form_title').val(title);
        $('#user_fields_form_pattern').val(pattern);
        $('#user_fields_form_id').val(id);
        $('#userfield_form').jqmShow(); 
    }

    this.applyForm = function(){
        $('#userfield_form').jqmHide(); 
        if ($('#user_fields_form_action').val() == 'add') {
            this.addUserfield();
        } else if ($('#user_fields_form_action').val() == 'update')  {
            this.updateUserfield();
        }
    }

    this.addUserfield = function() {
        var name = $('#user_fields_form_name').val();
        var title = $('#user_fields_form_title').val();
        var pattern = $('#user_fields_form_pattern').val();
        ls.ajax(aRouter['admin']+'userfields', {'action':'add', 'name':name,  'title':title,  'pattern':pattern}, function(data) { 
            if (!data.bStateError) {
                liElement = '<li id="field_'+data.id+'"><span class="userfield_admin_name"></span > / <span class="userfield_admin_title"></span> / <span class="userfield_admin_title"></span>'+
				'<div class="uf-actions"><a href="javascript:ls.userfield.showEditForm('+data.id+')"><img src="'+DIR_STATIC_SKIN+'/images/edit.png"></a> '+
				'<a href="javascript:ls.userfield.deleteUserfield('+data.id+')"><img src="'+DIR_STATIC_SKIN+'/images/delete.png"></a></div>';
                $('#user_field_list').append(liElement);
                $('#field_'+id+' .userfield_admin_name').html(name);
                $('#field_'+id+' .userfield_admin_title').html(title);
                $('#field_'+id+' .userfield_admin_pattern').text(pattern);
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
        var pattern = $('#user_fields_form_pattern').val();
        ls.ajax(aRouter['admin']+'userfields', {'action':'update', 'id':id, 'name':name,  'title':title,  'pattern':pattern}, function(data) { 
            if (!data.bStateError) {
                $('#field_'+id+' .userfield_admin_name').html(name);
                $('#field_'+id+' .userfield_admin_title').html(title);
                $('#field_'+id+' .userfield_admin_pattern').text(pattern);
                ls.msg.notice(data.sMsgTitle,data.sMsg);
            } else {
                ls.msg.error(data.sMsgTitle,data.sMsg);
            }
         });
    }

    this.deleteUserfield = function(id) {
        ls.ajax(aRouter['admin']+'userfields', {'action':'delete', 'id':id,}, function(data) { 
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