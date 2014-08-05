{**
 * 
 *}

{extends 'components/layout/layout.tpl'}

{block 'layout_head_styles' append}
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
{/block}

{block 'layout_head' append}
	<script>
		ls.lang.load({json var = $aLangJs});
		ls.lang.load({lang_load name="comments.comments_declension, comments.unsubscribe, comments.subscribe, comments.folding.unfold, comments.folding.fold, comments.folding.unfold_all, comments.folding.fold_all, poll.notices.error_answers_max, blog.blog, favourite.add, favourite.remove, geo_select_city, geo_select_region, blog.add.fields.type.note_open, blog.add.fields.type.note_close, common.success.add, common.success.remove, pagination.notices.first, pagination.notices.last, user.actions.unfollow, user.actions.follow"});

		ls.registry.set({json var = $aVarsJs});
		ls.registry.set('comment_max_tree', {json var=Config::Get('module.comment.max_tree')});
		ls.registry.set('block_stream_show_tip', {json var=Config::Get('block.stream.show_tip')});
	</script>

	{**
	 * Тип сетки сайта
	 *}
	{if {cfg name='view.grid.type'} == 'fluid'}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav--main,
			.grid-role-header .site-info,
			.grid-role-container {
				min-width: {cfg name='view.grid.fluid_min_width'};
				max-width: {cfg name='view.grid.fluid_max_width'};
			}
		</style>
	{else}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav--main,
			.grid-role-header .site-info,
			.grid-role-container { width: {cfg name='view.grid.fixed_width'}; }
		</style>
	{/if}
{/block}

{block 'layout_body'}
	{**
	 * Юзербар
	 *}
	<div class="grid-role-userbar-wrapper">
		<nav class="grid-row grid-role-userbar">
			{include 'navs/nav.userbar.tpl'}
			{include 'forms/search_forms/search_form.main.tpl' sMods='light'}
		</nav>
	</div>


	{**
	 * Шапка
	 *}
	<header class="grid-row grid-role-header" role="banner">
		{hook run='header_banner_begin'}

		<div class="site-info">
			<h1 class="site-name"><a href="{router page='/'}">{cfg name='view.name'}</a></h1>
			<h2 class="site-description">{cfg name='view.description'}</h2>
		</div>

		{hook run='header_banner_end'}
	</header>


	{**
	 * Основная навигация
	 *}
	<nav class="grid-row grid-role-nav">
		{include 'navs/nav.main.tpl'}
	</nav>


	{**
	 * Основной контэйнер
	 *}
	<div id="container" class="grid-row grid-role-container {hook run='container_class'} {if $bNoSidebar}no-sidebar{/if}">
		{* Вспомогательный контейнер-обертка *}
		<div class="grid-row grid-role-wrapper" class="{hook run='wrapper_class'}">
			{**
			 * Контент
			 *}
			<div class="grid-col grid-col-8 grid-role-content"
				 role="main"
				 {if $sMenuItemSelect == 'profile'}itemscope itemtype="http://data-vocabulary.org/Person"{/if}>

				{hook run='content_begin'}

				{* Основной заголовок страницы *}
				{block 'layout_page_title' hide}
					<h2 class="page-header">{$smarty.block.child}</h2>
				{/block}

				{block 'layout_content_header'}
					{* Навигация *}
					{if $sNav}
						<div class="nav-group">
							{if $sNav}
								{if in_array($sNav, $aMenuContainers)}
									{$aMenuFetch.$sNav}
								{else}
									{include "{$sNavPath}navs/nav.$sNav.tpl"}
								{/if}
							{/if}
						</div>
					{/if}

					{* Системные сообщения *}
					{if ! $bNoSystemMessages}
						{if $aMsgError}
							{include 'components/alert/alert.tpl' sMods='error' mAlerts=$aMsgError bClose=true}
						{/if}

						{if $aMsgNotice}
							{include 'components/alert/alert.tpl' mAlerts=$aMsgNotice bClose=true}
						{/if}
					{/if}
				{/block}

				{block 'layout_content'}{/block}

				{hook run='content_end'}
			</div>

			{**
			 * Сайдбар
			 *}
			{if ! $bNoSidebar}
				<aside class="grid-col grid-col-4 grid-role-sidebar" role="complementary">
					{include 'blocks.tpl' group='right'}
				</aside>
			{/if}
		</div> {* /wrapper *}


		{* Подвал *}
		<footer class="grid-row grid-role-footer">
			{block 'layout_footer'}
				{hook run='footer_begin'}
				{hook run='copyright'}
				{hook run='footer_end'}
			{/block}
		</footer>
	</div> {* /container *}


	{* Подключение модальных окон *}
	{if $oUserCurrent}
		{include 'modals/modal.create.tpl'}
		{include 'modals/modal.favourite_tags.tpl'}
	{else}
		{include 'modals/modal.auth.tpl'}
	{/if}


	{**
	 * Тулбар
	 * Добавление кнопок в тулбар
	 *}
	{add_block group='toolbar' name='toolbar/toolbar.admin.tpl' priority=100}
	{add_block group='toolbar' name='toolbar/toolbar.scrollup.tpl' priority=-100}

	{* Подключение тулбара *}
	{include 'components/toolbar/toolbar.tpl'}
{/block}