{$version 		= 'v='|cat:$smarty.const._JS_VERSION|default:''}

{foreach $view.js as $js}
	<script src="{$js}?{$version}">
{/foreach}
{if $request->_magic['jsCalls']}
	<script>
		$(document).ready(function(){ {$request->_magic['jsCalls']}
		});
	</script>
{/if}