{include file="documentHeader"}
<head>
	<title>{$contentObj->title} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}	
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}
	
	<div id="main">
		{*{include file='breadCrumb' lastElement=$contentObj->title}*}
	
		{@$content}
	</div>
	
{include file='footer' sandbox=false}
</body>
</html>