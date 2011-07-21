<div id="mainMenu" class="mainMenu">
	<div class="mainMenuInner">
		<ul>
			{foreach from=$this->getMenu()->getContentArray(0,1) item=child}
				{assign var="item" value=$child.content}
				{if $child.active}
					{assign var="activeMenuItem" value=$item}
				{/if}
				
				<li{if $child.active} class="active"{/if}>
					<a href="{$item->getURL()}"{if $child.active} class="active"{/if}><span>{$item->title}</span></a>
				{if $child.hasChildren}<ul>{else}</li>{/if}
				{if $child.openParents > 0}{@"</ul></li>"|str_repeat:$child.openParents}{/if}
			{/foreach}
		</ul>
	</div>
</div>