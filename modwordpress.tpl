<!-- MODULE modwordpress -->
<div id="cms_content" class="{$wpbcmsclass}">
{$content}
</div>	
<div id="wordpress_content" class="{$wpbwpclass}">
		<hr>
		<h1>{l s={$wpbtitle} mod='modwordpress'}</h1>
		
		
		
		{$i=0}
		{foreach from=$count item=product name=product}
				<h4 class="greydark">{$pdate[$i]} - {$news[$i]}</h4>
				{$ndata[$i++]}</P>
			<BR>
			
		{/foreach}
</div>
<div id="cms_footcontent" class="{$wpbcmsfootclass}">
{$content2}
</div>	
<!-- /MODULE modwordpress -->
