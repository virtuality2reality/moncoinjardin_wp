jQuery(document).ready(function($) {
	
	var navs = [$("#genesis-theme-settings-nav"), $("#genesis-theme-settings-subnav")];

	$.each(navs, function(k,v) {
		v.find('input:radio:checked').live('load change', function() {
			var nav_opts = v.find('div.nav-opts');
			if ( $(this).val() == 'nav-menu' ) {
				nav_opts.hide('fast');
			} else {
				nav_opts.show('fast');
			}
		});
	});
	
	/** controls character input/counter **/
	$('#genesis_title').keyup(function() {
		var charLength = $(this).val().length;
		// Displays count
		$('#genesis_title_chars').html(charLength);
	});
	$('#genesis_description').keyup(function() {
		var charLength = $(this).val().length;
		// Displays count
		$('#genesis_description_chars').html(charLength);
	});
	
	
});

function genesis_confirm( text ) {
	var answer = confirm( text );
	
	if( answer ) { return true; }
	else { return false; }
}