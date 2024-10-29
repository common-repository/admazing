var tgm_media_frame='';

jQuery('document').ready(function () {

	jQuery('input.admazing_activate_login').change(function() {
		actualitza_css('', '');
	});
	
	//Icons
	jQuery('select[name="admazing_size"]').change(function() {
		jQuery('div.admazing-icons-preview ul').removeClass('admazing-size-100').removeClass('admazing-size-75').removeClass('admazing-size-50').addClass(jQuery(this).val());
	});
	jQuery('select[name="admazing_skin"]').change(function() {
		jQuery('div.admazing-icons-preview ul').removeClass('admazing-clear-gray').removeClass('admazing-dark').addClass(jQuery(this).val());
	});
	jQuery('a.admazing-pos-preview').click(function() {
		if (jQuery('input', this).val()=='off') {
			jQuery('div.admazing-icons-preview ul').fadeOut();
		} else {
			jQuery('div.admazing-icons-preview ul').fadeIn();
		}
		jQuery('a.admazing-pos-preview').removeClass('admazing-pos-preview-on');
		jQuery('a.admazing-pos-preview').removeClass('admazing-pos-preview-on');
		jQuery(this).addClass('admazing-pos-preview-on');
		jQuery('input', this).prop("checked", true);
		return false;
	});
	
	jQuery("select.custom").msDropdown({
		on:{change:function(data, ui){
			jQuery('input.admazing_activate_login').prop('checked', true);
			actualitza_css('', '');
		}}
	});
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: false,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui) {
			jQuery('input.admazing_activate_login').prop('checked', true);
			actualitza_css(jQuery(this).attr('name'), ui.color); 
		},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
 
	jQuery('.my-color-field').wpColorPicker(myOptions);
	jQuery( "#accordion" ).accordion({heightStyle: 'content'});


	jQuery(document).on('click', 'a.upload_image', function () {
		
		tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media({
			multiple:false,
			title:'Set the login logo',
			button: {
				text: 'Set logo'
			}
		});
	
		tgm_media_frame.on('select', function(){
			var selection = tgm_media_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				console.log(attachment);

				sel_img='';
				if (typeof attachment.sizes.loginLogoSize != "undefined") {
					console.log('***1');
					sel_img = attachment.sizes.loginLogoSize.url;
				} else {
					console.log('***2');
					sel_img = attachment.url;
				}
				jQuery('input[name=logo]').val(sel_img);

				jQuery('input.admazing_activate_login').prop('checked', true);
				actualitza_css('', '');
			});
		});
		
		tgm_media_frame.open();

		return false;
		
	});

	jQuery(document).on('click', 'a.remove_image', function () {

		jQuery('input.admazing_activate_login').prop('checked', true);
		jQuery('input[name=logo]').val('x');
		actualitza_css('', '');
		return false;
	});
	
	actualitza_css('', '');

});

function actualitza_css(what, color) {
	
	//2nd color
	jQuery('div.color_degradee').each(function() {
		if (jQuery('select', this).val()=='solid') {
			jQuery('p.color2', this).fadeOut();
		} else {
			jQuery('p.color2', this).fadeIn();
		}
	});

	css_preview='';
	
	if (jQuery('input[name="admazingActivateLogin"]').is(':checked')) {
	
		//Outer background
		obmode=jQuery('select[name=obmode]').val();
		obc1=jQuery('input[name=obc1]').val();
		obc2=jQuery('input[name=obc2]').val();
		if (what=='obc1') obc1=color;
		if (what=='obc2') obc2=color;
		css_preview = '#body_preview {'+ bg_css(obmode,obc1,obc2) + '}';
	
		//Dialog background
		dbmode=jQuery('select[name=dbmode]').val();
		dbc1=jQuery('input[name=dbc1]').val();
		dbc2=jQuery('input[name=dbc2]').val();
		if (what=='dbc1') dbc1=color;
		if (what=='dbc2') dbc2=color;
		css_preview += '#dialog_preview {'+ bg_css(dbmode,dbc1,dbc2) + '}';
		
		// Label color
		lc = jQuery('input[name=lc]').val();
		if (what=='lc') lc=color;
		css_preview += '#dialog_preview label {color:'+ lc + '}';
	
		// Input fields
		itc = jQuery('input[name=itc]').val();
		if (what=='itc') itc=color;
		itbg = jQuery('input[name=itbg]').val();
		if (what=='itbg') itbg=color;
		itbc = jQuery('input[name=itbc]').val();
		if (what=='itbc') itbc=color;
		css_preview += '#dialog_preview input {color:'+ itc + '; background:' + itbg + '; border-color:' + itbc + ';}';
	
		//Button
		btc = jQuery('input[name=btc]').val();
		if (what=='btc') btc=color;
		btbc = jQuery('input[name=btbc]').val();
		if (what=='btbc') btbc=color;
		
		btmode=jQuery('select[name=btmode]').val();
		btc1=jQuery('input[name=btc1]').val();
		btc2=jQuery('input[name=btc2]').val();
		if (what=='btc1') btc1=color;
		if (what=='btc2') btc2=color;
		css_preview += '#dialog_preview p.submit input {'+ bg_css(btmode,btc1,btc2) + 'color:'+ btc + ';border-color:' + btbc + '}';
	
		//Button hover
		btch = jQuery('input[name=btch]').val();
		if (what=='btch') btch=color;

		btmodeh=jQuery('select[name="btmodeh"]').val();
		btc1h=jQuery('input[name=btc1h]').val();
		btc2h=jQuery('input[name=btc2h]').val();
		if (what=='btc1h') btc1h=color;
		if (what=='btc2h') btc2h=color;
		css_preview += '#dialog_preview p.submit input:hover {'+ bg_css(btmodeh,btc1h,btc2h) + 'color:'+ btch + ';}';
	
		//External link
		loc = jQuery('input[name=loc]').val();
		if (what=='loc') loc=color;
		loch = jQuery('input[name=loch]').val();
		if (what=='loch') loch=color;
		css_preview += '#external_link a {color:'+ loc + ';}';
		css_preview += '#external_link a:hover {color:'+ loch + ';}';
	
		//Logo
		logo = jQuery('input[name=logo]').val();
		if (logo == 'x' || logo == '') logo = no_logo;
		jQuery('div.logo_preview').css('background-image', 'url(' + logo + ')');
	}
	jQuery('#dynamic_css style').html(css_preview);

}
function bg_css(how, col1, col2) {
	
	if (how=='vertical') {
		reply = 'background: '+col1+';';
		reply += 'background: -moz-linear-gradient(top, '+col1+' 0%, '+col2+' 100%);';
		reply += 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'+col1+'), color-stop(100%,'+col2+'));';
		reply += 'background: -webkit-linear-gradient(top, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -o-linear-gradient(top, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -ms-linear-gradient(top, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: linear-gradient(to bottom, '+col1+' 0%,'+col2+' 100%);';
		reply += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+col1+"', endColorstr='"+col2+"',GradientType=0 );";
	} else 	if (how=='horizontal') {
		reply = 'background: '+col1+';';
		reply += 'background: -moz-linear-gradient(left, '+col1+' 0%, '+col2+' 100%);';
		reply += 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,'+col1+'), color-stop(100%,'+col2+'));';
		reply += 'background: -webkit-linear-gradient(left, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -o-linear-gradient(left, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -ms-linear-gradient(left, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: linear-gradient(to right, '+col1+' 0%,'+col2+' 100%);';
		reply += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+col1+"', endColorstr='"+col2+"',GradientType=1 );";
	} else if (how=='diagonal1') {
		reply = 'background: '+col1+';';
		reply += 'background: -moz-linear-gradient(-45deg, '+col1+' 0%, '+col2+' 100%);';
		reply += 'background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,'+col1+'), color-stop(100%,'+col2+'));';
		reply += 'background: -webkit-linear-gradient(-45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -o-linear-gradient(-45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -ms-linear-gradient(-45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: linear-gradient(135deg, '+col1+' 0%,'+col2+' 100%);';
		reply += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+col1+"', endColorstr='"+col2+"',GradientType=1 );";
	} else 	if (how=='diagonal2') {
		reply = 'background: '+col1+';';
		reply += 'background: -moz-linear-gradient(45deg, '+col1+' 0%, '+col2+' 100%);';
		reply += 'background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,'+col1+'), color-stop(100%,'+col2+'));';
		reply += 'background: -webkit-linear-gradient(45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -o-linear-gradient(45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -ms-linear-gradient(45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: linear-gradient(45deg, '+col1+' 0%,'+col2+' 100%);';
		reply += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+col1+"', endColorstr='"+col2+"',GradientType=1 );";
	} else if (how=='radial') {
		reply = 'background: '+col1+';';
		reply += 'background: -moz-radial-gradient(center, ellipse cover, '+col1+' 0%, '+col2+' 100%);';
		reply += 'background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'+col1+'), color-stop(100%,'+col2+'));';
		reply += 'background: -webkit-radial-gradient(center, ellipse cover, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -o-radial-gradient(center, ellipse cover, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: -ms-radial-gradient(center, ellipse cover, '+col1+' 0%,'+col2+' 100%);';
		reply += 'background: radial-gradient(ellipse at center, '+col1+' 0%,'+col2+' 100%);';
		reply += "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+col1+"', endColorstr='"+col2+"',GradientType=1 );";
	} else {
		reply = 'background: '+col1+';';
	}
	
	return reply;
}