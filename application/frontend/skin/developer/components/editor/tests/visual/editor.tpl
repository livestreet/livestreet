{**
 * Тестирование компонента editor
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	Component <span>editor</span>
{/block}

{block 'layout_content'}
	{function test_heading}
		<br><h3>{$sText}</h3>
	{/function}

	<script>
		jQuery(document).ready(function($) {
			ls.editor.init('.js-editor-test');
		});
	</script>

	{* Полная версия *}
	{test_heading sText='Default'}

	{component 'editor' sBindClass='js-editor-test' name='text'}

	{* Облегченная версия *}
	{test_heading sText='Light'}

	{component 'editor' sBindClass='js-editor-test' sSet='light' name='text'}
{/block}