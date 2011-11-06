<<<<<<< HEAD
{if $smarty.const._APP_USE_CHROME_FRAME && $request->browser.alias === 'ie'}
<!--[if IE]>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<style>
	.chromeFrameInstallDefaultStyle { width:100%; border: 5px solid blue; }
</style>
<div id="gglChrFrPlaceholer"></div>
<script>window.attachEvent("onload", function() { CFInstall.check({ mode: "overlay", node: "gglChrFrPlaceholer" }); });</script>
<![endif]-->
=======
{if $smarty.const._USE_CHROME_FRAME && $request->browser.alias === 'ie'}
<!--[if IE]>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<style>
	.chromeFrameInstallDefaultStyle { width:100%; border: 5px solid blue; }
</style>
<div id="gglChrFrPlaceholer"></div>
<script>window.attachEvent("onload", function() { CFInstall.check({ mode: "overlay", node: "gglChrFrPlaceholer" }); });</script>
<![endif]-->
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
{/if}