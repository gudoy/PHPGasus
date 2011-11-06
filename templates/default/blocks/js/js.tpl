<<<<<<< HEAD
{$version 		= 'v='|cat:$smarty.const._JS_VERSION|default:''}

{foreach $view.js as $js}
	<script src="{$js}{if strpos($css, '?') !== false}&{else}?{/if}{$version}">
{/foreach}
{if $request->_magic['jsCalls']}
	<script>
		$(document).ready(function(){ {$request->_magic['jsCalls']}
		});
	</script>
=======
{$version 		= 'v='|cat:$smarty.const._JS_VERSION|default:''}

{foreach $view.js as $js}
	<script src="{$js}{if strpos($css, '?') !== false}&{else}?{/if}{$version}"></script>
{/foreach}
{if $request->_magic['jsCalls']}
	<script>
		$(document).ready(function(){ {$request->_magic['jsCalls']}
		});
	</script>
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
{/if}