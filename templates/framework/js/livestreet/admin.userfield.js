var ls = ls || {};

ls.userfield =( function ($) {

	this.iCountMax = 2;

	this.showAddForm = function(){
		$('#user_fields_form_name').val('');
		$('#user_fields_form_title').val('');
		$('#user_fields_form_id').val('');
		$('#user_fields_form_pattern').val('');
		$('#user_fields_form_type').val('');
		$('#user_fields_form_action').val('add');
		$('#userfield_form').jqmShow(); 
	};
	
	this.showEditForm = function(id) {
		$('#user_fields_form_action').val('update');
		var name = $('#field_'+id+' .userfield_admin_name').text();
		var title = $('#field_'+id+' .userfield_admin_title').text();
		var pattern = $('#field_'+id+' .userfield_admin_pattern').text();
		var type = $('#field_'+id+' .userfield_admin_type').text();
		$('#user_fields_form_name').val(name);
		$('#user_fields_form_title').val(title);
		$('#user_fields_form_pattern').val(pattern);
		$('#user_fields_form_type').val(type);
		$('#user_fields_form_id').val(id);
		$('#userfield_form').jqmShow(); 
	};

	this.applyForm = function(){
		$('#userfield_form').jqmHide(); 
		if ($('#user_fields_form_action').val() == 'add') {
			this.addUserfield();
		} else if ($('#user_fields_form_action').val() == 'update')  {
			this.updateUserfield();
		}
	};

	this.addUserfield = function() {
		var name = $('#user_fields_form_name').val();
		var title = $('#user_fields_form_title').val();
		var pattern = $('#user_fields_form_pattern').val();
		var type = $('#user_fields_form_type').val();

		var url = aRouter['admin']+'userfields';
		var params = {'action':'add', 'name':name,  'title':title,  'pattern':pattern,  'type':type};
		
		ls.hook.marker('addUserfieldBefore');
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				liElement = $('<li id="field_'+data.id+'"><span class="userfield_admin_name"></span > / <span class="userfield_admin_title"></span> / <span class="userfield_admin_pattern"></span> / <span class="userfield_admin_type"></span>'
					+'<div class="userfield-actions"><a class="icon-edit" href="javascript:ls.userfield.showEditForm('+data.id+')"></a> '
					+'<a class="icon-remove" href="javascript:ls.userfield.deleteUserfield('+data.id+')"></a></div></li>')
				;
				$('#user_field_list').append(liElement);
				$('#field_'+data.id+' .userfield_admin_name').text(name);
				$('#field_'+data.id+' .userfield_admin_title').text(title);
				$('#field_'+data.id+' .userfield_admin_pattern').text(pattern);
				$('#field_'+data.id+' .userfield_admin_type').text(type);
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfield_add_userfield_after',[params, data],liElement);
			} else {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			}
		});
	};

	this.updateUserfield = function() {
		var id = $('#user_fields_form_id').val();
		var name = $('#user_fields_form_name').val();
		var title = $('#user_fields_form_title').val();
		var pattern = $('#user_fields_form_pattern').val();
		var type = $('#user_fields_form_type').val();

		var url = aRouter['admin']+'userfields';
		var params = {'action':'update', 'id':id, 'name':name,  'title':title,  'pattern':pattern, 'type':type};
		
		ls.hook.marker('updateUserfieldBefore');
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				$('#field_'+id+' .userfield_admin_name').text(name);
				$('#field_'+id+' .userfield_admin_title').text(title);
				$('#field_'+id+' .userfield_admin_pattern').text(pattern);
				$('#field_'+id+' .userfield_admin_type').text(type);
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfield_update_userfield_after',[params, data]);
			} else {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			}
		});
	};

	this.deleteUserfield = function(id) {
		if (!confirm(ls.lang.get('user_field_delete_confirm'))) {return;}
		
		var url = aRouter['admin']+'userfields';
		var params = {'action':'delete', 'id':id};
		
		ls.hook.marker('deleteUserfieldBefore');
		ls.ajax(url, params, function(data) { 
			if (!data.bStateError) {
				$('#field_'+id).remove();
				ls.msg.notice(data.sMsgTitle,data.sMsg);
				ls.hook.run('ls_userfield_update_userfield_after',[params, data]);
			} else {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			}
		});
	};

	this.addFormField = function() {
		var tpl=$('#profile_user_field_template').clone();
		/**
		 * Находим доступный тип контакта
		 */
		var value;
		tpl.find('select').find('option').each(function(k,v){
			if (this.getCountFormField($(v).val())<this.iCountMax) {
				value=$(v).val();
				return false;
			}
		}.bind(this));

		if (value) {
			tpl.find('select').val(value);
			$('#user-field-contact-contener').append(tpl.show());
		} else {
			ls.msg.error('',ls.lang.get('settings_profile_field_error_max',{count: this.iCountMax}));
		}
		return false;
	};

	this.changeFormField = function(obj) {
		var iCount=this.getCountFormField($(obj).val());
		if (iCount>this.iCountMax) {
			ls.msg.error('',ls.lang.get('settings_profile_field_error_max',{count: this.iCountMax}));
		}
	};

	this.getCountFormField = function(value) {
		var iCount=0;
		$('#user-field-contact-contener').find('select').each(function(k,v){
			if (value==$(v).val()) {
				iCount++;
			}
		});
		return iCount;
	};

	this.removeFormField = function(obj) {
		$(obj).parent('.js-user-field-item').detach();
		return false;
	};
	return this;
}).call(ls.userfield || {},jQuery);