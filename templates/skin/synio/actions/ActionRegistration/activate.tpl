{**
 * Уведомление об успешной регистрации
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_page_title'}{$aLang.registration_activate_ok}{/block}

{block name='layout_content'}
	<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a>
{/block}