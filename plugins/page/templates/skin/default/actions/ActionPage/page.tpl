{assign var="noSidebar" value=true}
{include file='header.tpl'}

<script>
jQuery(document).ready(function($) {
	//ls.tooltip.add('.js-tooltip', { position: 'top'});
	//$.fn.poshytip.defaults.className = 'tooltip'

});
	 
</script>


<h2 class="page-header">Popover</h2>

<a href="#" data-type="popover-toggle" title="Popover header title" data-option-title="Popover header" data-option-content="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam ipsum beatae veritatis mollitia fugiat earum labore magnam a totam natus? Cumque non maxime doloremque atque rem ex quisquam. Excepturi pariatur.">Popover</a>

<br>
<br>
<br>


<h2 class="page-header">Tooltip</h2>

<a href="#" data-type="tooltip-toggle" class="js-tooltip" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam ipsum beatae veritatis mollitia fugiat earum labore magnam a totam natus? Cumque non maxime doloremque atque rem ex quisquam. Excepturi pariatur.">Top tooltip</a>

<br>
<br>
<br>



<h2 class="page-header">Dropdowns</h2>

<div class="dropdown dropdown-toggle" id="dd1"
	data-type="dropdown-toggle"
	data-option-target="js-dropdown-test" 
	data-option-activate-items="true" 
	data-option-change-text="true"><i class="icon-trash icon-white"></i> <span data-type="dropdown-text">Dropdown</span></div>

<ul class="dropdown-menu" id="js-dropdown-test" data-type="dropdown-target">
	<li><a href="#" onclick="return false;">{$aLang.blog_menu_top_period_24h}</a></li>
	<li><a href="#" onclick="return false;">{$aLang.blog_menu_top_period_7d}</a></li>
	<li class="divider"></li>
	<li><a href="#" onclick="return false;">За все время</a></li>
</ul>

<!-- Ajax dropdown -->

<div class="dropdown dropdown-toggle" 
	data-type="dropdown-toggle" 
	data-option-target="js-dropdown-ajax" 
	data-option-template="<div class='dropdown-menu' id='js-dropdown-ajax' data-type='dropdown-target'></div>" 
	data-param-i-blog-id="2"
	data-option-url="http://lshead/ajax/infobox/info/blog/"><span data-type="dropdown-text">Test ajax</span></div>

<div class="dropdown-menu" id="js-dropdown-ajax" data-type="dropdown-target"></div>





<br />
<br />

<h2 class="page-header">Modals</h2>

<button class="button button-primary" data-type="modal-toggle" data-option-target="my-modal">Show modal</button>
<button class="button button-primary" data-type="modal-toggle" data-option-target="modal-long">Show looong modal</button>
<button class="button" onclick="$('#modal-custom').modal('show');">Modal with custom content</button>
<button class="button button-primary" data-type="modal-toggle" data-option-url="{cfg name='path.root.web'}">Show ajax modal</button>

<div class="modal js-modal-default" id="modal-custom" data-type="modal">
	<div class="modal-header">
		<h3>Modal header</h3>
	</div>

	<div class="modal-content">
		asdfasdfasdf
		
		<button class="button button-primary" data-type="modal-toggle" data-option-target="modal-long">Show ajax modal</button>
	</div>

	<div class="modal-footer">
		<button class="button button-primary" data-type="modal-close">Close</button>
	</div>
</div>

<div class="modal js-modal-default" id="modal-inner" data-type="modal">
	<div class="modal-header">
		<h3>Modal header</h3>
	</div>

	<div class="modal-content">
		asdfasdfasdf
	</div>

	<div class="modal-footer">
		<button class="button button-primary" data-type="modal-close">Close</button>
	</div>
</div>


<div class="modal js-modal-default" id="my-modal" data-type="modal">
	<div class="modal-header">
		<h3>Modal header</h3>
	</div>

	<div class="modal-content">
		<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt beatae saepe veritatis iusto obcaecati neque? Odio modi tenetur corporis voluptas sed nesciunt quas dolorem cum! Officiis amet dicta dolorum cumque!</div>
		<div>Dolore laboriosam sequi voluptatem sint labore tempore magni architecto consequuntur quibusdam adipisci itaque minus ad aspernatur rem repellat debitis nobis in totam cupiditate blanditiis commodi non illo quaerat obcaecati vitae.</div>
		<div>Nobis fugit rem molestiae est corporis repudiandae laboriosam temporibus iste pariatur omnis itaque explicabo dolore mollitia possimus totam at illum tempora natus ipsam voluptatibus et vitae beatae architecto hic sint.</div>

		<br>

		<button class="button button-primary" data-type="modal-close">Close</button>
	</div>
</div>

<div class="modal js-modal-default" id="modal-long" data-type="modal">
	<div class="modal-header">
		<h3>Modal header</h3>
	</div>

	<div class="modal-content">
		<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt beatae saepe veritatis iusto obcaecati neque? Odio modi tenetur corporis voluptas sed nesciunt quas dolorem cum! Officiis amet dicta dolorum cumque!</div>
		<div>Dolore laboriosam sequi voluptatem sint labore tempore magni architecto consequuntur quibusdam adipisci itaque minus ad aspernatur rem repellat debitis nobis in totam cupiditate blanditiis commodi non illo quaerat obcaecati vitae.</div>
		<div>Nobis fugit rem molestiae est corporis repudiandae laboriosam temporibus iste pariatur omnis itaque explicabo dolore mollitia possimus totam at illum tempora natus ipsam voluptatibus et vitae beatae architecto hic sint.</div>
		<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque minima odit quibusdam eveniet repudiandae unde voluptatum tempore beatae sed! Veritatis nihil est quibusdam quasi quis animi optio magni nostrum consectetur.</div>
		<div>Libero eos adipisci tempora itaque nam cum doloribus nostrum quisquam commodi neque! Tempore incidunt nesciunt id ipsam quibusdam sunt optio nostrum facere voluptatibus commodi atque ratione aperiam officiis facilis amet.</div>
		<div>Eius tempora temporibus necessitatibus itaque animi reiciendis sint facilis quod mollitia quaerat voluptatum nostrum sunt cupiditate totam quo saepe quisquam velit perferendis minus neque. Ex aliquam tempore non vero quisquam!</div>
		<div>Fuga at assumenda modi nobis iste quaerat quisquam culpa cum unde eaque voluptates recusandae maiores quam alias deleniti sed possimus nisi rem animi reprehenderit dolorem voluptatibus accusantium error praesentium vel!</div>
		<div>Magni atque dolores vitae dolorum asperiores illo ipsa obcaecati accusantium nihil cupiditate qui eum tempora natus voluptatibus sed at eligendi? Nam debitis atque voluptate culpa a odit provident sit quod.</div>
		<div>Magnam odit beatae reprehenderit voluptates libero in ut quos ratione veritatis explicabo eum earum corporis sapiente id molestias repellat nostrum quae cupiditate quam quidem maxime nisi pariatur dolorum accusamus animi.</div>
		<div>Quia unde illo itaque quam numquam amet similique corporis. Excepturi corporis repellendus eaque beatae expedita in. Fugiat eos enim sunt accusantium laudantium nulla repudiandae eaque ex doloremque sint adipisci reiciendis!</div>
		<div>Consectetur accusantium animi ab laudantium commodi consequuntur ducimus quas. Molestias sunt aperiam similique accusamus nobis quasi ut quia nostrum impedit in temporibus deleniti maiores consequuntur ratione neque sit quibusdam necessitatibus.</div>
		<div>Repellendus eaque error nisi temporibus est repudiandae hic ex quaerat quis rem molestiae tenetur reiciendis quo praesentium saepe voluptas similique illum modi asperiores qui laudantium fugit rerum eum impedit deserunt!</div>
		<div>Modi minima atque in quos porro repellat tempora doloremque optio iste at totam nulla sapiente rem ipsa mollitia ratione numquam? Saepe fugit eveniet officiis doloremque ducimus numquam nemo quos ab.</div>

		<div>Quia unde illo itaque quam numquam amet similique corporis. Excepturi corporis repellendus eaque beatae expedita in. Fugiat eos enim sunt accusantium laudantium nulla repudiandae eaque ex doloremque sint adipisci reiciendis!</div>
		<div>Consectetur accusantium animi ab laudantium commodi consequuntur ducimus quas. Molestias sunt aperiam similique accusamus nobis quasi ut quia nostrum impedit in temporibus deleniti maiores consequuntur ratione neque sit quibusdam necessitatibus.</div>
		<div>Repellendus eaque error nisi temporibus est repudiandae hic ex quaerat quis rem molestiae tenetur reiciendis quo praesentium saepe voluptas similique illum modi asperiores qui laudantium fugit rerum eum impedit deserunt!</div>
		<div>Modi minima atque in quos porro repellat tempora doloremque optio iste at totam nulla sapiente rem ipsa mollitia ratione numquam? Saepe fugit eveniet officiis doloremque ducimus numquam nemo quos ab.</div>

		<br>

		<button class="button button-primary" data-type="modal-close">Close</button>
	</div>
</div>

<br />
<br />
<br />

<h2 class="page-header">Tabs</h2>

<ul class="nav nav-tabs" data-type="tabs">
	<li data-type="tab" data-option-target="tabs-pages-1" class="active"><a href="#">Tab One</a></li>
	<li data-type="tab" data-option-target="tabs-pages-2"><a href="#">Tab Two</a></li>
	<li data-type="tab" data-option-target="tabs-pages-3"><a href="#">Tab Three</a></li>
	<li>
		<a href="#" class="dropdown-toggle" 
			data-type="dropdown-toggle" 
			data-option-target="js-dropdown-date2" 
			data-option-append-to-body="false"
			data-option-align-x="right"
			data-option-activate-items="true"
			data-option-change-text="true">
			<i class="icon-trash"></i>  More tabs</a>

		<ul class="dropdown-menu"  id="js-dropdown-date2" data-type="dropdown-target">
			<li data-type="tab" data-option-target="tabs-pages-4"><a href="#">{$aLang.blog_menu_top_period_24h}</a></li>
			<li data-type="tab" data-option-target="tabs-pages-5"><a href="#">{$aLang.blog_menu_top_period_7d}</a></li>
		</ul>
	</li>
</ul>

<div data-type="tab-content">
	<div id="tabs-pages-1" class="tab-pane" data-type="tab-pane" style="display: block">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic magnam error eligendi odio nemo itaque ea vero adipisci fugit exercitationem totam quasi asperiores dolores harum saepe laudantium provident. Voluptates tenetur.</div>
	<div id="tabs-pages-2" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque iste vel sit necessitatibus voluptatum cumque quos ipsam ab eligendi blanditiis accusamus nostrum consectetur magnam harum provident dolorem minima iure ex.</div>
	<div id="tabs-pages-3" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur esse odit sequi dolorum ab perspiciatis sint eveniet tenetur praesentium cumque deserunt quidem ipsa perferendis reprehenderit corporis vero explicabo ratione suscipit!</div>
	<div id="tabs-pages-4" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quam magnam quaerat molestiae provident error blanditiis sequi recusandae nisi adipisci. Voluptatibus optio assumenda in quaerat ab eaque placeat nesciunt animi.</div>
	<div id="tabs-pages-5" class="tab-pane" data-type="tab-pane"><div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni similique dolores ab exercitationem sint tempora eius magnam est dignissimos cumque ad id excepturi voluptatibus esse molestiae ut sit voluptates quod!</div>
	<div>Iure quibusdam eum eveniet autem veritatis aliquid neque repellat labore obcaecati modi facilis eaque in officia magni officiis tempora itaque eos natus quod quidem quam dolorum nobis distinctio possimus quo.</div>
	<div>Error iure ut nihil voluptate perspiciatis ipsam ex officia quis eveniet reprehenderit hic quod voluptas? Iusto dolore ad fugit ullam quos quis iure vitae quas vero nam repudiandae modi eaque.</div>
	<div>Sed eos dolorem dolore pariatur perspiciatis atque aspernatur autem cumque perferendis quae ab quisquam quasi hic magnam animi tenetur incidunt impedit nesciunt consequuntur sequi minima ad rerum aperiam dolores veritatis.</div></div>
</div>

<br />
<br />



<h2 class="page-header">Tabs pills</h2>

<ul class="nav nav-pills" data-type="tabs">
	<li data-type="tab" data-option-target="tab-pages-1"><a href="#">Tab One</a></li>
	<li data-type="tab" data-option-target="tab-pages-2"><a href="#">Tab Two</a></li>
	<li data-type="tab" data-option-target="tab-pages-3"><a href="#">Tab Three</a></li>
	<li>
		<a href="#" class="dropdown-toggle" data-type="dropdown-toggle" data-option-target="js-dropdown-date" data-option-append-to-body="false" data-option-change-text="true">
			<span data-type="dropdown-text">Dropdown</span></a>

		<ul class="dropdown-menu" id="js-dropdown-date">
			<li data-type="tab" data-option-target="tab-pages-4"><a href="#">{$aLang.blog_menu_top_period_24h}</a></li>
			<li data-type="tab" data-option-target="tab-pages-5"><a href="#">{$aLang.blog_menu_top_period_7d}</a></li>
		</ul>
	</li>
</ul>

<div data-type="tab-content">
	<div id="tab-pages-1" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic magnam error eligendi odio nemo itaque ea vero adipisci fugit exercitationem totam quasi asperiores dolores harum saepe laudantium provident. Voluptates tenetur.</div>
	<div id="tab-pages-2" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque iste vel sit necessitatibus voluptatum cumque quos ipsam ab eligendi blanditiis accusamus nostrum consectetur magnam harum provident dolorem minima iure ex.</div>
	<div id="tab-pages-3" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur esse odit sequi dolorum ab perspiciatis sint eveniet tenetur praesentium cumque deserunt quidem ipsa perferendis reprehenderit corporis vero explicabo ratione suscipit!</div>
	<div id="tab-pages-4" class="tab-pane" data-type="tab-pane">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quam magnam quaerat molestiae provident error blanditiis sequi recusandae nisi adipisci. Voluptatibus optio assumenda in quaerat ab eaque placeat nesciunt animi.</div>
	<div id="tab-pages-5" class="tab-pane" data-type="tab-pane"><div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni similique dolores ab exercitationem sint tempora eius magnam est dignissimos cumque ad id excepturi voluptatibus esse molestiae ut sit voluptates quod!</div>
	<div>Iure quibusdam eum eveniet autem veritatis aliquid neque repellat labore obcaecati modi facilis eaque in officia magni officiis tempora itaque eos natus quod quidem quam dolorum nobis distinctio possimus quo.</div>
	<div>Error iure ut nihil voluptate perspiciatis ipsam ex officia quis eveniet reprehenderit hic quod voluptas? Iusto dolore ad fugit ullam quos quis iure vitae quas vero nam repudiandae modi eaque.</div>
	<div>Sed eos dolorem dolore pariatur perspiciatis atque aspernatur autem cumque perferendis quae ab quisquam quasi hic magnam animi tenetur incidunt impedit nesciunt consequuntur sequi minima ad rerum aperiam dolores veritatis.</div></div>
</div>

<br />
<br />



<div class="topic">
	<div class="topic-content text">
		{if $oConfig->GetValue('view.tinymce')}
			{$oPage->getText()}
		{else}
			{if $oPage->getAutoBr()}
				{$oPage->getText()|nl2br}
			{else}
				{$oPage->getText()}
			{/if}
		{/if}
	</div>
</div>

{include file='footer.tpl'}