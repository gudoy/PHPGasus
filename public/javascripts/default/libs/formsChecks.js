var FIELDS = [],
	FORMS = [];

var CHECKS =
{
	name:			function(jqObject){ return CHECKS.test(jqObject, /^[^&~#"\{\(\[\|`_\\\^@\)\]°=\}\+¤\$£\^¨€%\*µ!§:\/;\.,\?<>0123456789£¤¨µ§]{2,32}$/i); },
	email_1:		function(jqObject){ return CHECKS.test(jqObject, /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/); },
	login:			function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{3,32}$/i); },
	email_2:		function(jqObject){ return $('input.check-email_1').val() == jqObject.val(); },
	login:			function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{3,32}$/i); },
	password:		function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{1,32}$/i); },
	password_1:		function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z0-9_\.\-]{1,32}$/i); },
	password_2:		function(jqObject){ return $('input.check-password_1').val() == jqObject.val(); },
	captcha:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{2}$/i); },
	year:			function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{4}$/i); },
	ccownername:	function(jqObject){ return CHECKS.test(jqObject, /^[a-zA-Z\s]{3,64}$/i); },
	ccgroup:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{4}$/i); },
	ccnumber:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{16}$/i); },
	cccrypto:		function(jqObject){ return CHECKS.test(jqObject, /^[0-9]{3}$/i); },
	checked:		function(jqObject){ return jqObject.attr('checked') },
	
	//phone_1:		function(jqObject){	return jqObject.val() !== '06') && CHECKS.test(jqObject, /^((06)\d{8})$/); },
	notEmpty:		function(jqObject){ return jqObject.val() !== '' },
	selectOne:		function(jqObject){ return $('option:selected', jqObject).not('.notCorrectValue').length > 0 && $('option:selected', jqObject).val() !== ''; },
	
	test: function(jqObject, rule) { return rule.test(jqObject.val()); }
};


var FORM =
{
	checks: [],
	context: null,
	
	// formContexts = jquery collection of forms or array of form ids
	init:function(formsSelector)
	{
		// Do not continue if there's an #errmsg block in the page
		//if ( $('#errorsBlock').length > 0) { return this; }
		
		var self = this;		
		
		// Loop over the forms
		$(formsSelector).each(function()
		{
			// Get the context (#formId)
			var context = '#' + $(this).attr('id'),
			
			// Get the id (formId)
			formId = context.replace(/\#/, '');
			
			// Store those data for perf issues
			FORMS[formId] = { context: context, id: formId, fields: [] };
			
			// Launch initialisation of all the fields of this form 
			self.initAll(formId);			
		});
		
		// Focus the first form element
		if ( app && !app.isTabbee ){ $(':input:first', formsSelector).focus(); }
		
		return this;
	},
	
	initAll:function(formId)
	{
		var self = this,
			fields = 'input, textarea, select';
		
		// Case where the form has already been poste, but contains errors
		if ( $(FORMS[formId].context).hasClass('noHints') ) { $(fields, FORMS[formId].context).each(function(){ self.check($(this)); });  }
		
		// Find every field in this form
		$(fields, FORMS[formId].context).
			
			// Remove some fields that we are sure we do not want to check
			not('.actionBtn, .noCheck, input[type="submit"], input[type="hidden"]').
			
			// Loop the remaining fields
			each(function() { self.initOne($(this), formId); });
	},
	
	initOne: function(jqObject, formId)
	{
		var self 		= this,
			formId 		= formId || jqObject.closest('form').attr('id'),
			p 			= jqObject.closest('.fieldBlock'); 					// Get the parent field block of this field

			// Get the id of the field or create one
			fieldId 	= jqObject.attr('id') || jqObject.attr('id', (new Date()).getTime())

		FORMS[formId].fields 	= {};
		FIELDS[fieldId] 		= { isInited:false };
		
		// Add event listners
		jqObject
			.focus(function(e) 	{ p.addClass('focused'); self.check(jqObject); })
			.blur(function(e) 	{ p.removeClass('focused'); self.check(jqObject); })
			.click(function(e) 	{ if( jqObject.is(':checkbox, :radio') ) { self.check(jqObject); } })
			.keyup(function(e) 	{ self.check(jqObject); })				
		
		return this;
	},
	
	check:function(jqObject)
	{	
		// Do not continue if the field has no class (meaning that no check task to be performed
		//if ( jqObject.attr('class') == '' ){ return this; }
		
		// Do not continue if the field val is empty
		if ( jqObject.val() == '' ){ return this; }

		var fieldIsOk		= true, 								// Set field as false by default
			tmpClasses		= jqObject.attr('class').split(' '), 	// Get the classes of this element
			notValidCheck	= '',
			checkClasses 	= [];									// checkName(s) to perform
		
		// Loop over the classes and add the ones that starts by 'check-' to a proper new array
		$.each(tmpClasses, function()
		{
			if( this.indexOf('check-') >= 0 ) { checkClasses.push(this.replace(/check-/, '')); }
		});
		
		// Loop over the check classes that have been found
		$.each(checkClasses, function()
		{
			// Do not continue if the field status is not ok (meaning that another check has already returned false
			if ( !fieldIsOk ) { return; }

			var currentCheck 	= this, 																	// Current classname = current check
				checkResult 	= CHECKS[currentCheck].call(null, jqObject);								// Perform the proper check
			
			//  If the current check is not valid, break and set the field status as false
			if ( !checkResult ) { notValidCheck = currentCheck; fieldIsOk = false; return; }
		});
		
		return this.handleTooltip(jqObject, fieldIsOk, notValidCheck);
		
	},
	
	handleTooltip: function(jqObject, fieldIsOk, notValidCheck)
	{
		var tooltipId 	= '#help_' + jqObject.attr('id');
			p 			= jqObject.closest('.fieldBlock'); 					// Get the parent field block of this field
		
		// If the field status is false
		if ( $('> .errorDetail', tooltipId).length > 0)
		{
			if (fieldIsOk)
			{
				// Add the valid status class (for 'valid' icon to be displayed instead of the 'error' icon)
				$(tooltipId).removeClass('error').addClass('valid');
			}
			else
			{
				// Add the error status class (for 'error' icon to be displayed instead of the 'valid' icon)
				$(tooltipId).removeClass('valid').addClass('error');
			}
		}
		// Otherwise we have to create it
		else { this.createTooltip(jqObject, fieldIsOk, notValidCheck); }
		
		return this;
	},
	
	createTooltip: function(jqObject, fieldIsOk, notValidCheck)
	{
		var fieldId		= jqObject.attr('id'),
			id 			= 'help_' + fieldId, 												// id of the tootip container
			status		= fieldIsOk ? 'valid' : 'error',									// status of the field valid/error
			mess		= notValidCheck !== null && notValidCheck != '' 					// Message to display
							? eval('CONFIG.' + notValidCheck + '_err')
							: '',
			p 			= jqObject.closest('.fieldBlock'); 									// Get the parent field block of this field
			htmlTooltip	= '<span class="detail ' + status + 'Detail">' + mess + '</span>', 	// Tooltip html code
			f 			= FIELDS[fieldId] || {}
			
		$('#' + id).addClass(status).append(htmlTooltip);
		
		f.isInited = true;
		p.addClass('checkInited');
			
		return this;
	},
	
	disablePaste: function(fieldId)
	{
		$('#' + fieldId).keydown(function(e)
		{
			if ((e.ctrlKey && e.keyCode == 86) || (e.metaKey && e.charCode == 118))
			{
				if (e.stopPropagation) { e.preventDefault(); e.stopPropagation(); }
				return false;
			}
			else return true;
		});
	}
};