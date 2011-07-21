{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Calendar.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
		var calendar = new Calendar('{$monthList}', '{$weekdayList}', {@$startOfWeek});
	//]]>
</script>
<script type="text/javascript">
	function setContentType(newType) {
		switch (newType) {
			case 0:
				showOptions('contentDiv');
				hideOptions('urlDiv');
				break;
			case 1:
				showOptions('urlDiv');
				hideOptions('contentDiv');
				break;
		}
	}
	onloadEvents.push(function() { setContentType({@$contentType}); });
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contentL.png" alt="" />
	<div class="headlineContainer">
		<h2>{if $action == 'add'}{lang}wcf.acp.menu.link.content.contentsystem.add{/lang}{else}{lang}wcf.acp.menu.link.content.contentsystem.edit{/lang}{/if}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.contentsystem.{@$action}.success{/lang}</p>
{/if}

{if $this->user->getPermission('admin.content.contentsystem.canEdit')}
	<div class="contentHeader">
		<div class="largeButtons">
			<ul><li><a href="index.php?page=ContentList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contentM.png" alt="" /> <span>{lang}wcf.acp.menu.link.content.contentsystem.view{/lang}</span></a></li></ul>
		</div>
	</div>
{/if}

<form method="post" action="index.php?form=Content{@$action|ucfirst}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" enctype="multipart/form-data">

	<div class="border content">
			<div class="container-1">   

				<fieldset>
					<legend>{lang}wcf.acp.contentsystem.contentType{/lang}</legend>
					<div class="formElement{if $errorField == 'contentType'} formError{/if}">
						<ul class="formOptions">
							<li><label><input onclick="if (IS_SAFARI) setContentType(0)" onfocus="setContentType(0)" type="radio" name="contentType" value="0" {if $contentType == 0}checked="checked" {/if}/> {lang}wcf.acp.contentsystem.contentType.0{/lang}</label></li>
							<li><label><input onclick="if (IS_SAFARI) setContentType(1)" onfocus="setContentType(1)" type="radio" name="contentType" value="1" {if $contentType == 1}checked="checked" {/if}/> {lang}wcf.acp.contentsystem.contentType.1{/lang}</label></li>
						</ul>
						{if $errorField == 'contentType'}
							<p class="innerError">
								{if $errorType == 'invalid'}{lang}wcf.acp.contentsystem.error.contentType.invalid{/lang}{/if}
							</p>
						{/if}
					</div>
				</fieldset>
                
				<fieldset>
					<legend>{lang}wcf.acp.contentsystem.general{/lang}</legend>

					<div class="formElement{if $errorField == 'title'} formError{/if}" id="titleDiv">
						<div class="formFieldLabel">
							<label for="title">{lang}wcf.acp.contentsystem.title{/lang}:</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="title" value="{$title}" />
							{if $errorField == 'title'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="titleHelpMessage">
							{lang}wcf.acp.contentsystem.title.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('title');
					//]]>
					</script>
									
					<div class="formElement{if $errorField == 'content'} formError{/if}" id="contentDiv">
						<div class="formFieldLabel">
							<label for="content">{lang}wcf.acp.contentsystem.content{/lang}:</label>
						</div>
						<div class="formField">
							<textarea rows="10" cols="40" name="content" id="pagecontent">{$content}</textarea>
							{if $errorField == 'content'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="contentHelpMessage">
							{lang}wcf.acp.contentsystem.content.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('content');
					//]]>
					</script>
					
					
					
					<div class="formElement{if $errorField == 'releaseDate'} formError{/if}" id="releaseDateDiv">
						<div class="formFieldLabel">
							<label for="releaseDate">{lang}wcf.acp.contentsystem.releaseDate{/lang}:</label>
						</div>
						<div class="formField">
							
							<div class="floatedElement">
								<label for="day">{lang}wcf.global.date.day{/lang}</label>
								{htmlOptions options=$dayOptions selected=$day id=releaseDateDay name=day}
							</div>

							<div class="floatedElement">
								<label for="Month">{lang}wcf.global.date.month{/lang}</label>
								{htmlOptions options=$monthOptions selected=$month id=releaseDateMonth name=month}
							</div>

							<div class="floatedElement">
								<label for="Year">{lang}wcf.global.date.year{/lang}</label>
								<input id="releaseDateYear" class="inputText fourDigitInput" type="text" name="year" value="{@$year}" maxlength="4" />
							</div>
							
							<div class="floatedElement">
								<label for="Hour">{lang}wcf.global.date.hour{/lang}</label>
								{htmlOptions options=$hourOptions selected=$hour id=hour name=hour}
							</div>
                            
							<div class="floatedElement">
								<label for="Minute">{lang}wcf.global.date.minutes{/lang}</label>
								{htmlOptions options=$minuteOptions selected=$minute id=minute name=minute}
							</div>
                            
							<div class="floatedElement">
								<a id="releaseDateButton"><img src="{@RELATIVE_WCF_DIR}icon/datePickerOptionsM.png" alt="" /></a>
								<div id="releaseDateCalendar" class="inlineCalendar"></div>
								<script type="text/javascript">
									//<![CDATA[
									calendar.init('releaseDate');
									//]]>
								</script>
							</div>
							
						</div>
						<div class="formFieldDesc hidden" id="releaseDateHelpMessage">
							{lang}wcf.acp.contentsystem.releaseDate.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('releaseDate');
					//]]>
					</script>
									
					<div class="formElement{if $errorField == 'invisible'} formError{/if}" id="invisibleDiv">
						<div class="formField">
							<label id="invisible">
								<input type="checkbox" name="invisible" value="1" {if $invisible}checked="checked" {/if}/> {lang}wcf.acp.contentsystem.invisible{/lang}
							</label>
						</div>
						<div class="formFieldDesc hidden" id="invisibleHelpMessage">
							{lang}wcf.acp.contentsystem.invisible.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('invisible');
					//]]>
					</script>
					
									
					<div class="formElement{if $errorField == 'url'} formError{/if}" id="urlDiv">
						<div class="formFieldLabel">
							<label for="url">{lang}wcf.acp.contentsystem.url{/lang}:</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="url" value="{$url}" />
							{if $errorField == 'url'}
								<p class="innerError">
									{lang}wcf.global.error.empty{/lang}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="urlHelpMessage">
							{lang}wcf.acp.contentsystem.url.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('url');
					//]]>
					</script>
					
				</fieldset>				
				
				<fieldset>
					<legend>{lang}wcf.acp.contentsystem.position{/lang}</legend>
					
					<div class="formElement{if $errorField == 'position'} formError{/if}" id="positionDiv">
						<div class="formFieldLabel">
							<label for="position">{lang}wcf.acp.contentsystem.position{/lang}:</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="position" value="{$position}" />
						</div>
						<div class="formFieldDesc hidden" id="positionHelpMessage">
							{lang}wcf.acp.contentsystem.position.description{/lang}
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('position');
					//]]>
					</script>
					
					{if $contentOptions|count > 0}
						<div class="formElement{if $errorField == 'parentID'} formError{/if}" id="parentIDDiv">
							<div class="formFieldLabel">
								<label for="parentID">{lang}wcf.acp.contentsystem.parentID{/lang}:</label>
							</div>
							<div class="formField">
								<select name="parentID" id="parentID">
									<option value="0"></option>
									{htmlOptions options=$contentOptions disableEncoding=true selected=$parentID}
								</select>
								{if $errorField == 'parentID'}
									<p class="innerError">
										{if $errorType == 'invalid'}{lang}wcf.acp.contentsystem.parentID.error.invalid{/lang}{/if}
									</p>
								{/if}
							</div>
							<div class="formFieldDesc hidden" id="parentIDHelpMessage">
								{lang}wcf.acp.contentsystem.parentID.description{/lang}
							</div>
						</div>
						<script type="text/javascript">
						//<![CDATA[
							inlineHelp.register('parentID');
						//]]>
						</script>
					{/if}
					
				</fieldset>
				
			</div>
		</div>
		

	<div class="formSubmit">
		<input type="submit" accesskey="s" name="send" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		
		{@SID_INPUT_TAG}
 		{if $contentID|isset}<input type="hidden" name="contentID" value="{$contentID}" />{/if}
	</div>
	
</form>

{include file='footer'}