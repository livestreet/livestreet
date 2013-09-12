{**
 * Справка по разметке редактора
 *}

<div class="editor-help js-editor-help" data-form-id="{$sTagsTargetId}">
	<header class="editor-help-header clearfix">
		<a href="#" class="link-dotted help-link" onclick="jQuery('#js-editor-help').toggle(); return false;">{$aLang.tags_help_link_show}</a>
	</header>

	<div class="editor-help-body" id="js-editor-help">
		<h3>{$aLang.tags_help_special}</h3>

		<ul class="mb-30">
			<li class="editor-help-item">
				<h4><a href="#" class="link-dotted js-tags-help-link">&lt;cut&gt;</a></h4>
			   {$aLang.tags_help_special_cut}
			</li>

			<li class="editor-help-item">
				<h4><a href="#" class="link-dotted js-tags-help-link">&lt;cut name="{$aLang.tags_help_special_cut_name_example_name}"&gt;</a></h4>
			   {$aLang.tags_help_special_cut_name}
			</li>

			<li class="editor-help-item">
				<h4><a href="#" class="link-dotted js-tags-help-link" data-insert="&lt;video&gt;&lt;/video&gt;">&lt;video&gt;http://...&lt;/video&gt;</a></h4>
			   {$aLang.tags_help_special_video}
			</li>
			
			<li class="editor-help-item">
				<h4><a href="#" class="link-dotted js-tags-help-link" data-insert="&lt;ls user=&quot;&quot; /&gt;">&lt;ls user="{$aLang.tags_help_special_ls_user_example_user}" /&gt;</a></h4>
			   {$aLang.tags_help_special_ls_user}
			</li>
		</ul>

		
		<h3>{$aLang.tags_help_standart}</h3>

		<div class="clearfix">
			<ul class="editor-help-col">
				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;h4&gt;&lt;/h4&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;h5&gt;&lt;/h5&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;h6&gt;&lt;/h6&gt;</a></h4>
					{$aLang.tags_help_standart_h}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;img src="" /&gt;</a></h4>
					{$aLang.tags_help_standart_img}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link" data-insert="&lt;a href=&quot;&quot;&gt;&lt;/a&gt;">&lt;a href="http://..."&gt;{$aLang.tags_help_standart_a_example_href}&lt;/a&gt;</a></h4>
					{$aLang.tags_help_standart_a}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;b&gt;&lt;/b&gt;</a></h4>
					{$aLang.tags_help_standart_b}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;i&gt;&lt;/i&gt;</a></h4>
					{$aLang.tags_help_standart_i}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;s>&lt;/s&gt;</a></h4>
					{$aLang.tags_help_standart_s}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;u&gt;&lt;/u&gt;</a></h4>
					{$aLang.tags_help_standart_u}
				</li>
			</ul>

			<ul class="editor-help-col">
				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;hr /&gt;</a></h4>
				  {$aLang.tags_help_standart_hr}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;blockquote&gt;&lt;/blockquote&gt;</a></h4>
				  	{$aLang.tags_help_standart_blockquote}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;table>&lt;/table&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;th>&lt;/th&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;td>&lt;/td&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;tr>&lt;/tr&gt;</a></h4>
					{$aLang.tags_help_standart_table}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;ul&gt;&lt;/ul&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;li&gt;&lt;/li&gt;</a></h4>
					{$aLang.tags_help_standart_ul}
				</li>

				<li class="editor-help-item">
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;ol&gt;&lt;/ol&gt;</a></h4>
					<h4><a href="#" class="link-dotted js-tags-help-link">&lt;li&gt;&lt;/li&gt;</a></h4>
				  {$aLang.tags_help_standart_ol}
				</li>
			</ul>
		</div>
	</div>
</div>