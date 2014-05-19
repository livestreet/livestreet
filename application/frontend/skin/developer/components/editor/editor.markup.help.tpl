{**
 * Справка по разметке редактора
 *}

{function editor_help_item}
{strip}
	{foreach $aItems as $aItem}
		<dl class="editor-help-item">
			{foreach $aItem['tags'] as $aTag}
				<dt>
					<a href="#" class="link-dotted js-tags-help-link" {if $aTag['insert']}data-insert="{$aTag['insert']}"{/if}>
						{$aTag['text']}
					</a>
				</dt>
			{/foreach}

			<dd>{$aItem['def']}</dd>
		</dl>
	{/foreach}
{/strip}
{/function}


<div class="editor-help js-editor-help" data-form-id="{$smarty.local.sTargetId}">
	<header class="editor-help-header clearfix">
		<a href="#" class="link-dotted help-link js-editor-help-toggle">{$aLang.tags_help_link_show}</a>
	</header>

	<div class="editor-help-body">
		<h3 class="h3">{$aLang.tags_help_special}</h3>

		<div class="mb-30">
			{editor_help_item aItems=[
				[ 'tags' => [ [ 'text' => '&lt;cut&gt;' ] ], 'def' => $aLang.tags_help_special_cut ],
				[ 'tags' => [ [ 'text' => "&lt;cut name=\"{$aLang.tags_help_special_cut_name_example_name}\"&gt;" ] ], 'def' => $aLang.tags_help_special_cut_name ],
				[ 'tags' => [ [ 'text' => "&lt;video&gt;http://...&lt;/video&gt;", 'insert' => '&lt;video&gt;&lt;/video&gt;' ] ], 'def' => $aLang.tags_help_special_video ],
				[ 'tags' => [ [ 'text' => "&lt;ls user=\"{$aLang.tags_help_special_ls_user_example_user}\" /&gt;", 'insert' => '&lt;ls user=&quot;&quot; /&gt;' ] ], 'def' => $aLang.tags_help_special_ls_user ]
			]}
		</div>

		<h3 class="h3">{$aLang.tags_help_standart}</h3>

		<div class="clearfix">
			<ul class="editor-help-col">
				{editor_help_item aItems=[
					[ 'tags' => [
						[ 'text' => '&lt;h4&gt;&lt;/h4&gt;' ],
						[ 'text' => '&lt;h5&gt;&lt;/h5&gt;' ],
						[ 'text' => '&lt;h6&gt;&lt;/h6&gt;' ]
					], 'def' => $aLang.tags_help_standart_h ],
					[ 'tags' => [ [ 'text' => "&lt;img src=\"\" /&gt;" ] ], 'def' => $aLang.tags_help_standart_img ],
					[ 'tags' => [
						[ 'text' => "&lt;a href=\"http://...\"&gt;{$aLang.tags_help_standart_a_example_href}&lt;/a&gt;", 'insert' => '&lt;a href=&quot;&quot;&gt;&lt;/a&gt;"' ]
					], 'def' => $aLang.tags_help_standart_a ],
					[ 'tags' => [ [ 'text' => "&lt;b&gt;&lt;/b&gt;" ] ], 'def' => $aLang.tags_help_standart_b ],
					[ 'tags' => [ [ 'text' => "&lt;i&gt;&lt;/i&gt;" ] ], 'def' => $aLang.tags_help_standart_i ],
					[ 'tags' => [ [ 'text' => "&lt;s&gt;&lt;/s&gt;" ] ], 'def' => $aLang.tags_help_standart_s ],
					[ 'tags' => [ [ 'text' => "&lt;u&gt;&lt;/u&gt;" ] ], 'def' => $aLang.tags_help_standart_u ]
				]}
			</ul>

			<ul class="editor-help-col">
				{editor_help_item aItems=[
					[ 'tags' => [ [ 'text' => "&lt;hr /&gt;" ] ], 'def' => $aLang.tags_help_standart_hr ],
					[ 'tags' => [ [ 'text' => "&lt;blockquote&gt;&lt;/blockquote&gt;" ] ], 'def' => $aLang.tags_help_standart_blockquote ],
					[ 'tags' => [
						[ 'text' => '&lt;table>&lt;/table&gt;' ],
						[ 'text' => '&lt;th>&lt;/th&gt;' ],
						[ 'text' => '&lt;td>&lt;/td&gt;' ],
						[ 'text' => '&lt;tr>&lt;/tr&gt;' ]
					], 'def' => $aLang.tags_help_standart_table ],
					[ 'tags' => [
						[ 'text' => '&lt;ul&gt;&lt;/ul&gt;' ],
						[ 'text' => '&lt;li&gt;&lt;/li&gt;' ]
					], 'def' => $aLang.tags_help_standart_ul ],
					[ 'tags' => [
						[ 'text' => '&lt;ol&gt;&lt;/ol&gt;' ],
						[ 'text' => '&lt;li&gt;&lt;/li&gt;' ]
					], 'def' => $aLang.tags_help_standart_ol ]
				]}
			</ul>
		</div>
	</div>
</div>