/**
 * Фотосет
 * 
 * @module ls/media
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.media =( function ($) {

	var _defaultsFileupload = {
		url: aRouter['ajax']+"media/upload/",
		sequentialUploads: false,
		singleFileUploads: true,
		limitConcurrentUploads: 3
	};

	var _defaults = {
		target_type: '',
		target_id: '',
		target_tmp: '',
		fileupload: _defaultsFileupload
	};

	this.mode='insert';

	this.setMode = function(mode) {
		this.mode=mode;
		this.showSettingsMode();
	};

	this.showSettingsMode = function($item) {
		$('.js-media-settings-mode').hide();
		/**
		 * Показываем только если есть выделенные элементы
		 */
		if (this.getSelected().length) {
			$('#media-settings-mode-'+this.mode).show();
			$item=$item || this.getCurrent();
			/**
			 * Выставляем настройки по вставке медиа
			 */
			var $select=$('select[name=size]').html('');
			var sizes=$item.data('mediaImageSizes');
			$.each(sizes,function(k,v){
				/**
				 * Расчитываем пропорциональную высоту изображения
				 */
				var height=v.h;
				if (!v.h) {
					height=parseInt(v.w*$item.data('mediaHeight')/$item.data('mediaWidth'));
				}
				$select.append('<option value="'+ v.w +(v.crop ? 'crop' : '')+'">'+v.w+' × '+height+'</option>');
			});
		}
	};

	this.hideSettingsMode = function() {
		$('.js-media-settings-mode').hide();
	};

	this.initUpload = function(options) {
		this.options = $.extend(true, {}, _defaults, options);

		this.options.target_tmp=this.options.target_tmp ? this.options.target_tmp : $.cookie('media_target_tmp_'+this.options.target_type);
		if (!this.options.target_id && !this.options.target_tmp) {
			ls.media.generateTargetTmp(this.options.target_type);
		}

		var params=this.options.fileupload;
		params.formData=$.extend({}, params.formData || {}, {
			security_ls_key: LIVESTREET_SECURITY_KEY,
			target_type: this.options.target_type,
			target_id: this.options.target_id,
			target_tmp: this.options.target_tmp
		});
		$.each(params.formData,function(k,v){
			if (v==null) {
				params.formData[k]='';
			}
		});
		$('.js-media-upload-file').fileupload(params);


		$('.js-media-upload-file').bind('fileuploadadd',function(e,data) {
			ls.media.addPreload(data);
		});
		$('.js-media-upload-file').bind('fileuploaddone',function(e,data) {
			if (data.result.bStateError) {
				ls.media.addFileError(data.context,data.result);
			} else {
				ls.media.addFile(data.context,data.result);
			}
		});
		$('.js-media-upload-file').bind('fileuploadprogress',function(e,data) {
			data.context.html(parseInt(data.loaded / data.total * 100, 10)+'%');
		});

		this.loadGallery();
		this.bindFileEvents();
	};



	/**
	 * Инициализация
	 */
	this.init = function() {

	};

	this.removeCurrentFile = function() {
		var $item=this.getCurrent();
		if ($item.length) {
			return this.removeFile($item.data('mediaId'));
		}
		return false;
	};

	this.removeFile = function(id) {
		$this=this;
		ls.ajax.load(aRouter['ajax']+"media/remove-file/", { id: id }, function(result) {
			$('.js-media-upload-gallery-item[data-media-id='+id+']').fadeOut(500,function(){
				$(this).remove();

				if ($itemNext=$this.searchNextSelected()) {
					$this.setCurrent($itemNext,true);
				} else {
					$this.hideDetail();
				}
			});
		});
	};

	this.loadGallery = function() {
		$('.js-media-upload-gallery-list').addClass('loader');
		ls.ajax.load(aRouter['ajax']+"media/load-gallery/", { target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }, function(result) {
			$('.js-media-upload-gallery-list').removeClass('loader');
			$('.js-media-upload-gallery-list').html(result.sTemplate);
		});
	};

	this.generateTargetTmp = function(type) {
		ls.ajax.load(aRouter['ajax']+"media/generate-target-tmp/", { type: type }, function(result) {
			if (result.sTmpKey) {
				this.options.target_tmp=result.sTmpKey;
			}
		}.bind(this));
	};

	this.addPreload = function(data) {
		data.context=$('<li class="modal-upload-image-gallery-list-item">' +
						'loader...' +
					'</li>');
		$('.js-media-upload-gallery-list').prepend(data.context);
	};

	this.bindFileEvents = function() {
		$('.js-media-upload-gallery-list').on('click','.js-media-upload-gallery-item',function(e){
			var $item=$(e.currentTarget);
			var allowMany=(this.mode=='create-photoset' || e.ctrlKey || e.metaKey);
			if ($item.hasClass('active')) {
				/**
				 * Снимаем выделение с текущего
				 */
				$item.removeClass('active').removeClass('is-selected');
				/**
				 * Делаем активным другой выделенный элемент
				 */
				if ($itemNext=this.searchNextSelected($item)) {
					this.setCurrent($itemNext,true);
				} else {
					this.hideDetail();
				}
			} else {
				/**
				 * Делаем текущим и показываем детальную информацию
				 */
				this.setCurrent($item,allowMany);
			}
		}.bind(this));
	};

	this.addFile = function(context,response) {
		/**
		 * Завершение загрузки файла
		 */
		var $item=$(response.sTemplateFile);
		context.replaceWith($item);
		this.setSelected($item,true);
	};

	this.addFileError = function(context,response) {
		/**
		 * Помечаем файл как ошибочный (глюк при загрузке)
		 */
		ls.msg.error(response.sMsgTitle,response.sMsg);
		context.html('ERROR');
	};

	this.searchNextSelected = function($itemOld) {
		var $item=$('.js-media-upload-gallery-item.is-selected:first');
		if ($item.length==0) {
			return false;
		}
		return $item;
	};

	this.setCurrent = function($item,allowMany) {
		$('.js-media-upload-gallery-item.active').removeClass('active');
		$item.addClass('active');
		this.setSelected($item,allowMany);
		/**
		 * Показываем детальную информацию
		 */
		this.showDetail($item);
	};

	this.getCurrent = function() {
		return $('.js-media-upload-gallery-item.active');
	};

	this.setSelected = function($item,allowMany) {
		if (!allowMany) {
			this.getSelected().removeClass('is-selected');
		}
		$item.addClass('is-selected');
		/**
		 * Если нет еще нет активного элемента, то делаем активным текущий
		 */
		if (this.getCurrent().length==0) {
			this.setCurrent($item,allowMany);
		}
	};

	this.getSelected = function() {
		return $('.js-media-upload-gallery-item.is-selected');
	};

	this.showDetail = function($item) {
		$('.js-media-detail-preview').attr('src',$item.data('mediaPreview'));
		$('.js-media-detail-name').html($item.data('mediaFileName'));
		$('.js-media-detail-date').html($item.data('mediaDateAdd'));
		$('.js-media-detail-dimensions').html($item.data('mediaWidth')+' × '+$item.data('mediaHeight'));
		$('.js-media-detail-file-size').html(parseInt($item.data('mediaFileSize')/1024)+'kB');
		$('.js-media-detail-area').show();

		this.showSettingsMode($item);
	};

	this.hideDetail = function() {
		$('.js-media-detail-area').hide();
		this.hideSettingsMode();
	};

	this.submitInsert = function() {
		var $aItems=this.getSelected();
		if (!$aItems.length) {
			return false;
		}
		/**
		 * Формируем список ID элементов
		 */
		var aIds=[];
		$aItems.each(function(k,v){
			var $item=$(v);
			aIds.push($item.data('mediaId'));
		});
		var params={
			ids: aIds,
			align: $('#media-settings-mode-insert').find('select[name=align]').val(),
			size: $('#media-settings-mode-insert').find('select[name=size]').val()
		}

		ls.ajax.load(aRouter['ajax']+"media/submit-insert/", params, function(result) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				$.markItUp({
					replaceWith: result.sTextResult
				});
				$('#modal-image-upload').modal('hide');
			}
		});
	};

	this.submitCreatePhotoset = function() {
		var $aItems=this.getSelected();
		if (!$aItems.length) {
			return false;
		}
		/**
		 * Формируем список ID элементов
		 */
		var aIds=[];
		$aItems.each(function(k,v){
			var $item=$(v);
			aIds.push($item.data('mediaId'));
		});
		var params={
			ids: aIds
		}

		ls.ajax.load(aRouter['ajax']+"media/submit-create-photoset/", params, function(result) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				$.markItUp({
					replaceWith: result.sTextResult
				});
				$('#modal-image-upload').modal('hide');
			}
		});
	};

	return this;
}).call(ls.media || {},jQuery);