{if $smarty.const._USE_CHROME_FRAME && $request->browser.alias === 'ie'}
<!--[if IE]>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<style>
	.chromeFrameInstallDefaultStyle { width:100%; border: 5px solid blue; }
</style>
<div id="gglChrFrPlaceholer"></div>
<script>window.attachEvent("onload", function() { CFInstall.check({ mode: "overlay", node: "gglChrFrPlaceholer" }); });</script>
<![endif]-->
{/if}