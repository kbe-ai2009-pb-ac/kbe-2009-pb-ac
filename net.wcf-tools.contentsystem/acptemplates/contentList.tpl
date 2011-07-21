{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/jquery.ui.nestedSortable.js"></script>

<script type="text/javascript">
	jQuery.noConflict();
	jQuery(document).ready(function(){
		jQuery('ol.sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: 'div',
			helper: 'clone',
			items: 'li',
			maxLevels: 0,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		}); 
		
		
		jQuery('#save').click(function(){
			serialized = jQuery('ol.sortable').nestedSortable('serialize');
			
			jQuery.ajax({
  				url: 'index.php?action=ContentSort{@SID_ARG_2ND_NOT_ENCODED}',
  				type: 'post',
				data: serialized,	
				
				beforeSend: function() {
					jQuery('#save').attr('disabled', 'disabled');
					jQuery('#success').text("{lang}wcf.acp.contentsystem.sort.start{/lang}").removeClass('success').addClass('info').fadeIn();				
				},
  				success: function() {
					jQuery('#success').text("{lang}wcf.acp.contentsystem.sort.success{/lang}").removeClass('info').addClass('success');		
					
					setTimeout(function(){ 
						jQuery('#success').fadeOut();
						jQuery('#save').removeAttr('disabled');
					}, 5000);
  				}
			});
		})
	});
</script>

<style type="text/css">

	.placeholder {
		background-color: #cfcfcf;
	}

	.ui-nestedSortable-error {
		background:#fbe3e4;
		color:#8a1f11;
	}

	ol {
		margin: 0;
		padding: 0;
		padding-left: 30px;
	}

	ol.sortable, ol.sortable ol {
		margin: 0 0 0 25px;
		padding: 0;
		list-style-type: none;
	}

	ol.sortable {
		margin: 0 0;
	}

	.sortable li {
		margin: 7px 0 0 0;
		padding: 0;
	}

	.sortable li div.inner  {
		border-bottom: 1px solid #aaa;
		padding: 8px 5px;
		margin: 0;
		cursor: move;
	}
	
	.buttons {
		float:right;
	}
	
	.largeButtons a span {
		padding: 9px 3px 0 12px;
	}
	
	.largeButtons img {
		width:1px;
	}
	
	#success {
		margin-top:10px;
	}
</style>


<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contentL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.menu.link.content.contentsystem.view{/lang}</h2>
	</div>
</div>


<div class="contentHeader">
	{if $this->user->getPermission('admin.content.contentsystem.canAdd')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ContentAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contentM.png" alt="" /> <span>{lang}wcf.acp.menu.link.content.contentsystem.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>

{if $content|count}		
	
	<div class="border content">
		<div class="container-1">
			<ol id="contentList" class="sortable">
				{foreach from=$content item=child}
					{assign var="contentItem" value=$child.content}
					<li id="list_{@$contentItem->contentID}">
						<div class="inner">	
							ID-{@$contentItem->contentID} <a class="title" href="index.php?form=ContentEdit&amp;contentID={@$contentItem->contentID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}{$contentItem->title}{/lang}</a>
							
							<div class="buttons">
								<a href="index.php?form=ContentEdit&amp;contentID={@$contentItem->contentID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.contentsystem.edit{/lang}" /></a>
								<a onclick="return confirm('{lang}wcf.acp.contentsystem.delete.sure{/lang}')" href="index.php?action=ContentDelete&amp;contentID={@$contentItem->contentID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.contentsystem.delete{/lang}" /></a>
							</div>
						</div>
					{if $child.hasChildren}<ol>{else}</li>{/if}
					{if $child.openParents > 0}{@"</ol></li>"|str_repeat:$child.openParents}{/if}
				{/foreach}
			</ol>
		</div>
	</div>

	<div class="formSubmit">
		<input type="button" id="save" accesskey="s" name="send" value="{lang}wcf.global.button.submit{/lang}" />
		<p class="success" id="success" style="display:none;">{lang}wcf.acp.contentsystem.sort.success{/lang}</p>
	</div>	
	
	<div class="contentFooter">
		{if $this->user->getPermission('admin.content.contentsystem.canAdd')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=ContentAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contentM.png" alt="" /> <span>{lang}wcf.acp.menu.link.content.contentsystem.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>
{/if}



{include file='footer'}