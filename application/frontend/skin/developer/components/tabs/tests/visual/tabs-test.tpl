{**
 * Тестирование
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Component <span>tabs</span>
{/block}

{block 'layout_content'}
    {function test_heading}
        <br><h3>{$sText}</h3>
    {/function}

    {* Text *}
    {test_heading sText='Tabs'}

    <script type="text/javascript">
        $(function ($) {
            $( '.js-tabs-default' ).lsTabs();
        });
    </script>

    {component 'tabs' classes='js-tabs-default' tabs=[
        [ 'text' => 'Tab 1', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores, temporibus?' ],
        [ 'text' => 'Tab 2', 'content' => 'Lorem dolor sit amet, consectetur asit amet, consectetur adipisicing elit. Maiores, temporibus?' ],
        [ 'text' => 'Tab 3', 'content' => 'Lorem ipsum amet, ipsum dolor sit amet, consectetur adipisicing elit. Maiores, temporibus?' ]
    ]}
{/block}