{if $smarty.const._FLUSH_BUFFER_EARLY}
{php}ob_flush(); flush();{/php}
{else}
{/if}