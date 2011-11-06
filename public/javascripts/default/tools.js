var Tools = 
{
    /*
     * This function dynamically loads a css file in the page
     * @author Guyllaume Doyer guyllaume@clicmobile.com
     * @return {Object} this object
     */
    loadCSS: function(cssFile)
    {
        // If the css already exists we do not continue
        if ( $('link[href*="' + cssFile + '"]', 'head').length ) { return this; }
        
        var src = cssFile,
        
            h = '<link href="' + src + '" media="all" rel="stylesheet" type="text/css" />';
        
        // For IE, we have to add the new css file Before the IE specifics css files
        //app.isIE ? $j('head link[href*="Site-ie"]').before(h) : $j('head link[type="text/css"]:last').after(h);
        $('link[type="text/css"]:last', 'head').after(h);
        
        return this;
    },
    
	/** 
	 * @projectDescription	This files defines the global app object of our application and a tools object for containing some misc helpers
	 *
	 * @author Guyllaume Doyer
	 * @version 	0.3
	 */
	// usage: Tools.loadJS('/path/to/your/file.js'})
	// usage: Tools.loadJS('/path/to/your/file.js', function(){ /* global calback function code here */ })
	// usage: Tools.loadJS({url:'/path/to/your/file.js'})
	// usage: Tools.loadJS({url:'/path/to/your/file.js'}, function(){ /* global calback function code here */ })
	// usage: Tools.loadJS({id:'anyIdHere', url:'/path/to/your/file.js', success:function(){ /* some local callback code here */}}, function(){ /* global calback function code here */ })
	// usage: Tools.loadJS([{url:'/path/to/your/file1.js'}, {url:'/path/to/your/file2.js'}], function(){ /* global calback function code here */ })
	// usage: Tools.loadJS([{id:'file1', url:'/path/to/your/file1.js', success: function(){ alert('hello world!'); }}, {url:'file2', '/path/to/your/file2.js'}], function(){ /* global calback function code here */ })
	loadJS: function(jsFiles)
	{
		// Force jsFiles to be an array 
		var jsFiles = typeof jsFiles === 'object' ? (jsFiles instanceof Array ? jsFiles : [jsFiles]) : ( typeof jsFiles === 'string' ? [{url:jsFiles}] : [] ),
		
		// Main callback (fired when all scripts have been loaded)
			mainCallback = arguments[1] || function(){},
			
		// Number of successfully loaded files
			nb = 0,
			
			fCallback = function(){ if (nb == jsFiles.length) { mainCallback.call(null); } },
			
		// Default params for js files
			fileDefault = { id:null, url:null, success:function(){} };
			
		// For each required js file
		$.each(jsFiles, function()
		{
			var file = $.extend(fileDefault, this);
			
			if (file.url === null) { return; }

			// If the script already exists in the page, breaks and launch the callback
			if ( $('script[src*="' + file.url + '"]').length > 0 )
			{
				nb++;
				
				file.success.call(null); 
				
				fCallback.call(null);
				
				return;
			}
			
            var script = document.createElement('script'),
				head = document.getElementsByTagName('head')[0];
            script.setAttribute('type', 'text/javascript');
            script.setAttribute('src', file.url);

			// Attach handlers for all browsers
			script.onload = script.onreadystatechange = function()
			{
				if ( (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") ) 
				{					
					nb++;
					
					file.success.call(null);
					
					fCallback();

					// Handle memory leak in IE
					script.onload = script.onreadystatechange = null;
					head.removeChild( script );
				}
			};

			head.appendChild(script);
		});
		
		return this;
	},

	
	ucfirst:function(str)
	{
		return (str + '').replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase(); });
	},
	
	lcfirst:function(str)
	{
		return (str + '').replace(/^(.)|\s(.)/g, function($1){ return $1.toLowerCase(); });
	},
	
	log: function(msg)
	{	
		typeof(console) != "undefined" && console.log ? console.log(msg) : (typeof(opera) != "undefined" && opera.postError ? opera.postError(msg) : false );
		//console && console.log ? console.log(msg) : (opera && opera.postError ? opera.postError(msg) : function(){} ) 

		return this;
	},
	
	trim: function(str)
	{
		return str.replace(/^\s+/g,'').replace(/\s+$/g,'');
	},	
	
	strtr: function (str, charsTable)
	{
		for (var i in charsTable)
		{
			str = str.replace(new RegExp(i, 'g'), charsTable[i]);
		}
		
	    return str;
	},
	
	
	deaccentize: function(str)
	{
		var charsTable = {
			'Š':'S', 'š':'s', 'Đ':'Dj', 'đ':'dj', 'Ž':'Z', 'ž':'z', 'Č':'C', 'č':'c', 'Ć':'C', 'ć':'c',
			'À':'A', 'Á':'A', 'Â':'A', 'Ã':'A', 'Ä':'A', 'Å':'A', 'Æ':'A', 'Ç':'C', 'È':'E', 'É':'E',
			'Ê':'E', 'Ë':'E', 'Ì':'I', 'Í':'I', 'Î':'I', 'Ï':'I', 'Ñ':'N', 'Ò':'O', 'Ó':'O', 'Ô':'O',
			'Õ':'O', 'Ö':'O', 'Ø':'O', 'Ù':'U', 'Ú':'U', 'Û':'U', 'Ü':'U', 'Ý':'Y', 'Þ':'B', 'ß':'Ss',
			'à':'a', 'á':'a', 'â':'a', 'ã':'a', 'ä':'a', 'å':'a', 'æ':'a', 'ç':'c', 'è':'e', 'é':'e',
			'ê':'e', 'ë':'e', 'ì':'i', 'í':'i', 'î':'i', 'ï':'i', 'ð':'o', 'ñ':'n', 'ò':'o', 'ó':'o',
			'ô':'o', 'õ':'o', 'ö':'o', 'ø':'o', 'ù':'u', 'ú':'u', 'û':'u', 'ý':'y', 'ý':'y', 'þ':'b',
			'ÿ':'y', 'Ŕ':'R', 'ŕ':'r'
		};
		return Tools.strtr(str,charsTable);
	},
	

	humanize: function(string){ return Tools.slug(string) },
    slugify: function(string){ return Tools.slug(string) },	
	slug: function(string)
	{
        var string = Tools.deaccentize(string);

        return string
        		.replace(/^[^A-Za-z0-9]+/g, '')
        		.replace(/[^A-Za-z0-9]+$/g, '')
        		.replace(/[^A-Za-z0-9]+/g, '-')
        ;
	},
	

	singular: function(plural)
	{
        var len 	= plural.length,
        	sing 	= plural;          // Default
        
        if      ( len >= 5 && plural.slice(-4) === 'uses' )	{ sing = plural.replace(/(.*)uses/,'$1us'); }
        else if ( len >= 4 && plural.slice(-3) === 'ses' )	{ sing = plural.replace(/(.*)ses/,'$1ss'); }
        else if ( len >= 4 && plural.slice(-3) === 'hes' )	{ sing = plural.replace(/(.*)hes/,'$1h'); }
        else if ( len >= 4 && plural.slice(-3) === 'ies' )	{ sing = plural.replace(/(.*)ies$/,'$1y'); }
        else if ( len >= 4 && plural.slice(-3) === 'oes' )	{ sing = plural.replace(/(.*)oes$/,'$1o'); }
        else if ( len >= 4 && plural.slice(-3) === 'ves' )	{ sing = plural.replace(/(.*)ves$/,'$1f'); }
        else if ( len >= 2 && plural.slice(-1) === 'a' ) 	{ sing = plural.replace(/(.*)a$/,'$1um'); }
        else if ( len >= 2 && plural.slice(-1) === 's' ) 	{ sing = plural.replace(/(.*)s$/,'$1'); }
        
        return sing;
	},
	
	
	consonants: function(string)
	{
		return string.replace(/[aeiouAEIOU]/g, '');
	},
	
	
	/* 
	 * This function gets, in an URL, the value of the param given in the function call
	 * @author Guyllaume Doyer guyllaume@clicmobile.com
	 * @return {String|Boolean} The value if found, otherwise false
	 */
	getURLParamValue: function (url, paramName)
	{
		url = url.substring(url.indexOf("?"), url.length);
		
		var pos = url.indexOf(paramName);
		
		if (pos != -1 && paramName != "")
		{
			// Truncate the string from this position to its end
			var tmp 	= url.substr(pos),
			
			// Gets the start position of the param value
				start 	= pos + paramName.length,
			
			// Get end position of the param value
				end_pos;
			
				if 		(tmp.indexOf("&amp;") != -1){ end_pos = tmp.indexOf("&amp;"); } // case where there are others params after, separated by a "&amp;"
				else if (tmp.indexOf("&") != -1 ) 	{ end_pos = tmp.indexOf("&"); } 	// case where there are others params after, separated by a "&"
				else if (tmp.indexOf("#") != -1 ) 	{ end_pos = tmp.indexOf("#"); } 	// case where there are others params after, separated by a "#"
				else 								{ end_pos = tmp.length; } 			// case where there are no others params after
			
			// Truncate the string from 0 to the end of the param value			
			return tmp.substring(paramName.length + 1,end_pos);
		}
		else { return false; }
	},
	
	removeQueryParam: function(url, paramName)
	{
		var reg = new RegExp('(.*)' + paramName + '=([^\&\#\?]*)(.*)', 'g');
		
		return url.replace(reg,'$1$3').replace(/(.*)\?&(.*)/,'$1?$2').replace(/(.*)[&|?]$/,'$1');
	}
};

