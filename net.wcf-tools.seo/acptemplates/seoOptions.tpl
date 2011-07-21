{include file='header'}

<form method="post" action="index.php?page=SEOOptions&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">

	<div class="border content">
			<div class="container-1">   

				<fieldset>
					<legend>{lang}wcf.acp.contentsystem.contentType{/lang}</legend>
					
					<div class="formElement">
						<div class="formFieldLabel">
							<label for="dir">Ordner:</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="dir" value="{WCF_DIR}" />
						</div>
					</div>
				</fieldset>
                
			</div>
		</div>
		

	<div class="formSubmit">
		<input type="submit" accesskey="s" name="write" value="SEO schreiben" />
		<input type="submit" accesskey="s" name="delete" value="SEO löschen" />
		
		{@SID_INPUT_TAG}
	</div>
	
</form>

{include file='footer'}