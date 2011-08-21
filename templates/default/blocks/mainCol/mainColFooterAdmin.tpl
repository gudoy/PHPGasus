<footer class="menu" id="mainColFooter">
	{block name='poweredBy'}
	<div class="block poweredByBlock" id="poweredByBlock">{t escape=no}powered by <a href="http://www.phpgasus.com">PHPGasus</a>{/t}</div>
	{/block}
	<ul>
		<li class="toggler" id="mainColColToggler">
			<a class="toggle plus" id="showMainCol"><span class="value">{t}show{/t}</span></a>
			<a class="toggle minus" id="hideMainCol"><span class="value">{t}hide{/t}</span></a>
		</li>
		<li class="more" id="mainColMoreOptions">
			<a id="mainColMoreOptionsLink"><span class="value">{t}more{/t}</span></a>
		</li>
		<li class="resize" id="mainColResizer">
			<a id="mainColResizeLink"><span class="value">{t}resize{/t}</span></a>
		</li>
	</ul>
</footer>