{**
 * Основной лэйаут
 *
 * @param string  $layoutNavContent         Название навигации
 * @param string  $layoutNavContentPath     Кастомный путь до навигации контента
 * @param string  $layoutShowSystemMessages Кастомный путь до навигации контента
 *}

{extends 'Component@layout.layout'}

{block 'layout_options' append}
    {$layoutShowSystemMessages = $layoutShowSystemMessages|default:true}

    {* Получаем блоки для вывода в сайдбаре *}
    {include 'blocks.tpl' group='right' assign=layoutSidebarBlocks}
    {$layoutSidebarBlocks = trim( $layoutSidebarBlocks )}
    {$layoutSidebarBlocksShow = !!$layoutSidebarBlocks and !$layoutNoSidebar}
{/block}

{block 'layout_head_styles' append}
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
{/block}

{block 'layout_head' append}
    <script>
        ls.lang.load({json var = $aLangJs});
        ls.lang.load({lang_load name="comments.comments_declension, comments.unsubscribe, comments.subscribe, comments.folding.unfold, comments.folding.fold, comments.folding.unfold_all, comments.folding.fold_all, poll.notices.error_answers_max, blog.blog, favourite.add, favourite.remove, field.geo.select_city, field.geo.select_region, blog.add.fields.type.note_open, blog.add.fields.type.note_close, common.success.add, common.success.remove, pagination.notices.first, pagination.notices.last, user.actions.unfollow, user.actions.follow, user.friends.status.added, user.friends.status.notfriends, user.friends.status.pending, user.friends.status.rejected, user.friends.status.sent, user.friends.status.linked, blog.blocks.navigator.blog, user.settings.profile.notices.error_max_userfields, common.remove_confirm, more.empty, validate.tags.count"});

        ls.registry.set({json var = $aVarsJs});
        ls.registry.set('comment_max_tree', {json var=Config::Get('module.comment.max_tree')});
        ls.registry.set('comment_show_form', {json var=Config::Get('module.comment.show_form')});
        ls.registry.set('topic_max_blog_count', {json var=Config::Get('module.topic.max_blog_count')});
        ls.registry.set('block_stream_show_tip', {json var=Config::Get('block.stream.show_tip')});
        ls.registry.set('poll_max_answers', {json var=Config::Get('module.poll.max_answers')});
    </script>

    {**
     * Тип сетки сайта
     *}
    {if {Config::Get('view.grid.type')} == 'fluid'}
        <style>
            .grid-role-userbar,
            .grid-role-nav .nav--main,
            .grid-role-header .jumbotron-inner,
            .grid-role-container {
                min-width: {Config::Get('view.grid.fluid_min_width')};
                max-width: {Config::Get('view.grid.fluid_max_width')};
            }
        </style>
    {else}
        <style>
            .grid-role-userbar,
            .grid-role-nav .nav--main,
            .grid-role-header .jumbotron-inner,
            .grid-role-container { width: {Config::Get('view.grid.fixed_width')}; }
        </style>
    {/if}
{/block}

{block 'layout_body'}
    {**
     * Юзербар
     *}
    {component 'userbar'}


    {**
     * Шапка
     *}
    {if Config::Get( 'view.layout_show_banner' )}
        {component 'jumbotron'
            title    = Config::Get('view.name')
            subtitle = Config::Get('view.description')
            titleUrl = {router page='/'}
            classes  = 'grid-role-header'}
    {/if}


    {**
     * Основная навигация
     *}
    <nav class="grid-row grid-role-nav">
        {include 'navs/nav.main.tpl'}
    </nav>


    {**
     * Основной контэйнер
     *}
    <div id="container" class="grid-row grid-role-container {hook run='container_class'} {if ! $layoutSidebarBlocksShow}no-sidebar{/if}">
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
                    <h2 class="page-header">
                        {$smarty.block.child}
                    </h2>
                {/block}

                {block 'layout_content_header'}
                    {* Навигация *}
                    {if $sNav}
                        {if in_array($sNav, $aMenuContainers)}
                            {$_navContent = $aMenuFetch.$sNav}
                        {else}
                            {include "{$sNavPath}navs/nav.$sNav.tpl" assign=_navContent}
                        {/if}

                        {* Проверяем наличие вывода на случай если меню с одним пунктом автоматом скрывается *}
                        {if $_navContent|strip:''}
                            <div class="nav-group">
                                {$_navContent}
                            </div>
                        {/if}
                    {/if}

                    {* Системные сообщения *}
                    {if $layoutShowSystemMessages}
                        {if $aMsgError}
                            {component 'alert' text=$aMsgError mods='error' close=true}
                        {/if}

                        {if $aMsgNotice}
                            {component 'alert' text=$aMsgNotice close=true}
                        {/if}
                    {/if}
                {/block}

                {block 'layout_content'}{/block}

                {hook run='content_end'}
            </div>

            {**
             * Сайдбар
             * Показываем сайдбар
             *}
            {if $layoutSidebarBlocksShow}
                <aside class="grid-col grid-col-4 grid-role-sidebar" role="complementary">
                    {$layoutSidebarBlocks}
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
        {component 'tags-favourite' template='modal'}
    {else}
        {component 'auth' template='modal'}
    {/if}


    {**
     * Тулбар
     * Добавление кнопок в тулбар
     *}
    {add_block group='toolbar' name='component@admin.toolbar.admin' priority=100}
    {add_block group='toolbar' name='component@toolbar-scrollup.toolbar.scrollup' priority=-100}

    {* Подключение тулбара *}
    {component 'toolbar' classes='js-toolbar-default' items={include 'blocks.tpl' group='toolbar'}}
{/block}