<ul class="breadCrumbs">
	<li><a href="index.php?page=Index{@SID_ARG_2ND}"><span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	
	{foreach from=$contentObj->getParentContent() item=parentContent}
		<li><a href="index.php?page=Content&amp;contentID={@$parentContent->contentID}{@SID_ARG_2ND}"><span>{lang}{$parentContent->title}{/lang}</span></a> &raquo;</li>
	{/foreach}
	
	{if $lastElement|isset}
		<li><span>{@$lastElement}</span></li>
	{/if}
</ul>