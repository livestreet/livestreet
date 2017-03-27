{**
 * Основной лэйаут, который наследуют все остальные лэйауты
 *
 * @param boolean $layoutShowSidebar        Показывать сайдбар или нет, сайдбар не будет выводится если он не содержит блоков
 * @param string  $layoutNavContent         Название навигации
 * @param string  $layoutNavContentPath     Кастомный путь до навигации контента
 * @param string  $layoutShowSystemMessages Показывать системные уведомления или нет
 *}

{extends 'component@layout.layout'}

{block 'layout_options' append}
    {$layoutShowSidebar = $layoutShowSidebar|default:true}
    {$layoutShowSystemMessages = $layoutShowSystemMessages|default:true}
{/block}

{block 'layout_head_styles' append}
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
    <link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{Config::Get('view.name')}" />
{/block}

{block 'layout_head' append}
    {* Получаем блоки для вывода в сайдбаре *}
    {if $layoutShowSidebar}
        {show_blocks group='right' assign=layoutSidebarBlocks}

        {$layoutSidebarBlocks = trim( $layoutSidebarBlocks )}
        {$layoutShowSidebar = !!$layoutSidebarBlocks}
    {/if}

    {**
     * Тип сетки сайта
     *}
    {if {Config::Get('view.grid.type')} == 'fluid'}
        <style>
            .layout-userbar,
            .layout-container {
                min-width: {Config::Get('view.grid.fluid_min_width')};
                max-width: {Config::Get('view.grid.fluid_max_width')};
            }
        </style>
    {else}
        <style>
            .layout-userbar,
            .layout-container { width: {Config::Get('view.grid.fixed_width')}; }
        </style>
    {/if}

    <meta name="viewport" content="">
{/block}

{block 'layout_body'}
    {hook run='layout_body_begin'}

    {**
     * Юзербар
     *}
    {component 'userbar'}


    {**
     * Основная навигация
     *}
    <nav class="layout-nav ls-clearfix" style="min-width: {Config::Get('view.grid.fluid_min_width')}; max-width: {Config::Get('view.grid.fluid_max_width')};">
        <div class="layout-nav-inner ls-clearfix">
            <div class="layout-nav-right">
                {* Поиск *}
                {component 'search.hideable'}

                {if $oUserCurrent}
                    {component 'button' classes='layout-nav-create js-modal-toggle-default' mods='primary round small' text={lang 'modal_create.title'} attributes=[ 'data-lsmodaltoggle-modal' => 'syn-create-modal' ]}
                    {component 'syn-create'}
                {/if}
            </div>

            {* Первый уровень навигации *}
            {if count($layoutNav)}
                {component 'nav' classes='layout-nav-top' params=$layoutNav[0]}
            {/if}
        </div>
    </nav>


    {**
     * Основной контэйнер
     *}
    <div id="container" class="layout-container {hook run='layout_container_class' action=$sAction} {if $layoutShowSidebar}layout-has-sidebar{else}layout-no-sidebar{/if}">
        {* Вспомогательный контейнер-обертка *}
        <div class="layout-wrapper ls-clearfix {hook run='layout_wrapper_class' action=$sAction}">
            {**
             * Контент
             *}
            <div class="layout-content"
                 role="main"
                 {if $sMenuItemSelect == 'profile'}itemscope itemtype="http://data-vocabulary.org/Person"{/if}>

                {hook run='layout_content_header_begin' action=$sAction}

                {* Основной заголовок страницы *}
                {block 'layout_page_title' hide}
                    <h2 class="page-header">
                        {$smarty.block.child}
                    </h2>
                {/block}

                {block 'layout_content_header'}
                    {* Навигация *}
                    {if $layoutNav}
                        {$_layoutNavContent = ""}

                        {if is_array($layoutNav)}
                            {foreach $layoutNav as $layoutNavItem}
                                {* Пропускаем первый уровень навигации который отображается выше *}
                                {if $layoutNavItem@index === 0}{continue}{/if}

                                {if is_array($layoutNavItem)}
                                    {component 'nav' mods='pills' params=$layoutNavItem assign=_layoutNavItemContent}
                                    {$_layoutNavContent = "$_layoutNavContent $_layoutNavItemContent"}
                                {else}
                                    {$_layoutNavContent = "$_layoutNavContent $layoutNavItem"}
                                {/if}
                            {/foreach}
                        {else}
                            {$_layoutNavContent = $layoutNav}
                        {/if}

                        {* Проверяем наличие вывода на случай если меню с одним пунктом автоматом скрывается *}
                        {if $_layoutNavContent|strip:''}
                            <div class="ls-nav-group">
                                {$_layoutNavContent}
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

                {hook run='layout_content_begin' action=$sAction}

                {block 'layout_content'}{/block}

                {hook run='layout_content_end' action=$sAction}
            </div>

            {**
             * Сайдбар
             * Показываем сайдбар
             *}
            {if $layoutShowSidebar}
                <aside class="layout-sidebar" role="complementary">
                    {$layoutSidebarBlocks}
                </aside>
            {/if}
        </div> {* /wrapper *}


        {* Подвал *}
        <footer class="layout-footer ls-clearfix">
            {block 'layout_footer'}
                {hook run='layout_footer_begin'}

                {function layout_footer_links title='' hook='' items=[]}
                    <div class="layout-footer-links">
                        <h4 class="layout-footer-links-title">{$title}</h4>

                        {component 'nav' classes='layout-footer-links-nav' mods='stacked' hook=$hook items=$items}
                    </div>
                {/function}

                {if $oUserCurrent}
                    {layout_footer_links title=$oUserCurrent->getLogin() hook='layout_footer_links_user' items=[
                        [ text => {lang 'user.profile.nav.info'}, url => $oUserCurrent->getUserWebPath() ],
                        [ text => {lang 'user.profile.nav.settings'}, url => {router page='settings'} ],
                        [ text => {lang 'modal_create.title'}, url => '#', classes => 'js-modal-toggle-default', attributes => [ 'data-lsmodaltoggle-modal' => 'syn-create-modal' ] ],
                        [ text => {lang 'auth.logout'}, url => "{router page='auth'}logout/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]
                    ]}
                {else}
                    {layout_footer_links title={lang 'synio.guest'} hook='layout_footer_links_auth' items=[
                        [ 'text' => {lang 'auth.login.title'},        'classes' => 'js-modal-toggle-login',        'url' => {router page='auth/login'} ],
                        [ 'text' => {lang 'auth.registration.title'}, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='auth/register'} ]
                    ]}
                {/if}

                {layout_footer_links title={lang 'synio.site_pages'} hook='layout_footer_links_pages' items=[
                    [ 'text' => $aLang.topic.topics,   'url' => {router page='/'},      'name' => 'blog' ],
                    [ 'text' => $aLang.blog.blogs,     'url' => {router page='blogs'},  'name' => 'blogs' ],
                    [ 'text' => $aLang.user.users,     'url' => {router page='people'}, 'name' => 'people' ],
                    [ 'text' => $aLang.activity.title, 'url' => {router page='stream'}, 'name' => 'stream' ]
                ]}

                {hook run='synio_layout_footer_after_links'}

                <div class="layout-footer-copyright">
                    {hook run='copyright'}

                    <div class="layout-footer-design-by">
                        <img src="{cfg name='path.skin.assets.web'}/images/xeoart.png" alt="xeoart" />
                        Design by <a href="http://xeoart.com">xeoart</a>
                        <div>2012</div>
                    </div>
                </div>

                {hook run='layout_footer_end'}
            {/block}
        </footer>
    </div> {* /container *}


    {* Подключение модальных окон *}
    {if $oUserCurrent}
        {component 'tags-personal' template='modal'}
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
    {component 'toolbar' classes='js-toolbar-default' items={show_blocks group='toolbar'}}

    {hook run='layout_body_end'}
{/block}
