<div class="debugBar">
	<ul>
		<li class="app">
			<div class="key">app</span>
			<div class="value">
				<ul>
					<li>
						<span class="key">name</span>
						<span class="value">{$smarty.const._APP_NAME}</span>	
					</li>
					<li>
						<span class="key">env</span>
						<span class="value">{$smarty.const._APP_CONTEXT}</span>	
					</li>
					<li>
						<span class="key">version</span>
						<span class="value">{$smarty.const._APP_VERSION}</span>	
					</li>
					<li>
						<span class="key">PHPGasus version</span>
						<span class="value">{$smarty.const._PHPGASUS_VERSION}</span>	
					</li>
				</ul>
			</div>
		</li>
		<li class="queries">
			<div class="key">queries</span>
			<div class="value">
				<ul>
					<li>
						<span class="key">count</span>
						<span class="value">{$data.queries.count}</span>	
					</li>
				</ul>
			</div>
		</li>
		<li class="time">
			<div class="key">time</span>
			<div class="value">
				<ul>
					<li>
						<span class="key">total</span>
						<span class="value">{$data.time.count}</span>	
					</li>
				</ul>
			</div>
		</li>
	</ul>
</div>
