(function ($) {
	// encapsulate variables that need only be defined once
	var pl = /\+/g,  // Regex for replacing addition symbol with a space
		searchStrict = /([^&=]+)=+([^&]*)/g,
		searchTolerant = /([^&=]+)=?([^&]*)/g,
		decode = function (s) {
			return decodeURIComponent(s.replace(pl, " "));
		};
	
	// parses a query string. by default, will only match good k/v pairs.
	// if the tolerant option is truthy, then it will also set keys without values to ''
	$.parseQuery = function(query, options) {
		var match,
			o = {},
			opts = options || {},
			search = opts.tolerant ? searchTolerant : searchStrict;
		
		if ('?' === query.substring(0, 1)) {
			query  = query.substring(1);
		}
		
		// each match is a query parameter, add them to the object
		while (match = search.exec(query)) {
			o[decode(match[1])] = decode(match[2]);
		}
		
		return o;
	}
	
	// parse this URLs query string
	$.getQuery = function(options) {
		return $.parseQuery(window.location.search, options);
	}

    $.fn.parseQuery = function (options) {
        return $.parseQuery($(this).serialize(), options);
    };
}(jQuery));