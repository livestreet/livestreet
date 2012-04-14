<div class="modal modal-image-upload" id="window_upload_img">
	<header class="modal-header">
		<h3>{$aLang.uploadimg}</h3>
		<a href="#" class="close jqmClose"></a>
	</header>
	
	<script>
		jQuery(function($){
			$('div.tab-content:not(:first)').hide();
		
			$('ul.nav-pills-tabs a').click(function(){
				if (this.hash) {
					$('.tab-content').hide();
					$('ul.nav-pills-tabs li').removeClass('active');
					$('.tab-content' + this.hash).show();
					$(this).parent('li').addClass('active');
				}
			});
		});
	</script>
	
	<div class="modal-content">
		<ul class="nav nav-pills nav-pills-tabs">
			<li class="active"><a href="#tab_content_pc">Upload</a></li>
			<li><a href="#tab_content_link">Link</a></li>
		</ul>
	
		<form method="POST" action="" enctype="multipart/form-data" id="tab_content_pc" onsubmit="return false;" class="tab-content">
			<p><label for="img_file">{$aLang.uploadimg_file}:</label>
			<input type="file" name="img_file" id="img_file" value="" class="input-text input-width-full" /></p>
			
			{hook run="uploadimg_source"}
			
			<p>
				<label for="align">{$aLang.uploadimg_align}:</label>
				<select name="align" class="input-width-full">
					<option value="">{$aLang.uploadimg_align_no}</option>
					<option value="left">{$aLang.uploadimg_align_left}</option>
					<option value="right">{$aLang.uploadimg_align_right}</option>
					<option value="center">{$aLang.uploadimg_align_center}</option>
				</select>
			</p>
			
			<p><label for="title">{$aLang.uploadimg_title}:</label>
			<input type="text" name="title" id="title" value="" class="input-text input-width-full" /></p>
			
			{hook run="uploadimg_additional"}
			
			<button class="button button-primary" onclick="ls.ajaxUploadImg('tab_content_pc','{$sToLoad}');">{$aLang.uploadimg_submit}</button>
			<button class="button jqmClose">{$aLang.uploadimg_cancel}</button>
		</form>
		
		
		<form method="POST" action="" enctype="multipart/form-data" id="tab_content_link" onsubmit="return false;" style="display: none;" class="tab-content">
			<p><label for="img_file">Ссылка на картинку:</label>
			<input type="text" name="img_url" id="img_url" value="http://" class="input-text input-width-full" /></p>
			
			<button class="button button-primary" onclick="ls.ajaxUploadImg('tab_content_link','{$sToLoad}');">{$aLang.uploadimg_submit}</button>
			<button class="button jqmClose">{$aLang.uploadimg_cancel}</button>
		</form>
	</div>
</div>
	