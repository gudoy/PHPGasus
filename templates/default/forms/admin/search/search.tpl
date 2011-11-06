<<<<<<< HEAD
<form id="adminSearchForm" class="search adminSearch" action="{$smarty.const._URL_ADMIN}search/" method="post">
		<div class="field" id="searchInField">
			<div class="label">
				<label for="searchIn">{t}in{/t}</label>
			</div>
			<div class="input">
				<select id="searchIn" name="searchIn" multiple="multiple">
					<option value="all">{t}all{/t}</option>
					{foreach $_resources.searchable as $rName}
					<option value="{$rName}">{$_resources[$rName].displayName|default:$rName}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="field" id="searchQueryField">
			<div class="label">
				<label for="searchQuery">{t}search{/t}</label>
			</div>
			<div class="input">
				<input type="search" id="searchQuery" name="searchQuery" class="seach" autofocus="on" autocomplete="on" autocapitalize="off" placeholder="{'search'|gettext|capitalize}" />
			</div>
		</div>
		<div class="field actions" id="searchActionsField">
			<input type="submit" id="validateSearch" name="validateSearch" value="{t}go{/t}" />
		</div>
=======
<form id="adminSearchForm" class="search adminSearch" action="{$smarty.const._URL_ADMIN}search/" method="post">
		<div class="field" id="searchInField">
			<div class="label">
				<label for="searchIn">{t}in{/t}</label>
			</div>
			<div class="input">
				<select id="searchIn" name="searchIn" multiple="multiple">
					<option value="all">{t}all{/t}</option>
					{foreach $_resources._searchable as $rName}
					<option value="{$rName}">{$_resources[$rName].displayName|default:$rName}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="field" id="searchQueryField">
			<div class="label">
				<label for="searchQuery">{t}search{/t}</label>
			</div>
			<div class="input">
				<input type="search" id="searchQuery" name="searchQuery" class="seach" autofocus="on" autocomplete="on" autocapitalize="off" placeholder="{'search'|gettext|capitalize}" />
			</div>
		</div>
		<div class="field actions" id="searchActionsField">
			<input type="submit" id="validateSearch" name="validateSearch" value="{t}go{/t}" />
		</div>
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
</form>