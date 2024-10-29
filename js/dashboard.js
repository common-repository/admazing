var admazing_selected_icon = '';
var admazing_auto_name=true;
var admazing_selected_color = '';
var admazing_selected_font = '';

jQuery(document).ready(function() {
	//Make icons sortable
	admazingMakeEditable();

	jQuery ('div.admazing_step_2 select, input[name=admazing-option-url-custom], input[type=checkbox]').click(function() {
		jQuery('input:radio', jQuery(this).parent()).prop("checked", true);
	});
	jQuery ('div.admazing_step_2 select').change(function() {
		if (admazing_auto_name) {
			name = jQuery('option:selected', this).html();
			name = name.replace(' &gt; ','');
			jQuery('input[name=admazing_name]').val(name);
			admazingUpdate_name_preview();
		}
	});
	jQuery('input[name=admazing_name]').change( function () {
		admazingUpdate_name_preview();
	});
	jQuery('input[name=admazing_name]').keyup( function () {
		admazingUpdate_name_preview();
	});
	jQuery('input[name=admazing_name]').blur( function () {
		admazing_auto_name = (jQuery(this).val()=='');
		admazingUpdate_name_preview();
	});

	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: false,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){ 
			jQuery('#icon-preview a').attr('style', 'color:' + ui.color);
			admazing_selected_color=ui.color;
			//alert(ui.color);
			//actualitza_css(jQuery(this).attr('name'), ui.color); 
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

	jQuery ('#admazing-addicon').click(function () {
		menu = jQuery('input[name=admazing-menu]:checked').val();
		adminside = jQuery('input[name=admazing-adminside\\[\\]]').map(function(){return jQuery(this).attr('checked')?true:false;;}).get();
		adminside_yn='0';
		if (menu=='custom') {
			url = jQuery('input[name=admazing-option-url-custom]').val();
		} else {
			if (menu != 0) adminside_yn=adminside[menu-1];
			url = jQuery('select[name=admazing-option-url\\[\\]]').map(function(){return jQuery(this).val();}).get();
			url = url[menu];
		}
		if (adminside_yn != '1') {
			adminside_yn = '0';
		} else {
			adminside_yn = '1';
		}
		admazing_name = jQuery('input[name=admazing_name]').val();
		admazing_blank = 0; if (jQuery('input[name=admazing-blank]').is(':checked')) admazing_blank = 1;
		if (admazing_selected_color == jQuery('div.admazing_step_2 input.wp-color-picker').attr('data-default-color')) admazing_selected_color='';
		
		/*$title=$this->post_safe('title');
		$adminside=$this->post_safe('adminside');
		$url=$this->post_safe('url');
		$icon=$this->post_safe('icon');
		$menu=$this->post_safe('menu');*/

		thedata = 'action=admazing&do=add&title=' + admazing_name + '&adminside=' + adminside_yn + '&menu=' + menu + '&url=' + encodeURIComponent(url) + '&icon=' + admazing_selected_icon + '&blank=' + admazing_blank + '&color=' + admazing_selected_color + '&font=' + admazing_selected_font;

		jQuery.post(ajaxurl, thedata, function(response) {
			if(response!=1) {
				alert(response);
				alert("Error saving icon");
			} else {
				tb_remove();
			}
			jQuery.post(ajaxurl, 'action=admazing&do=updateDash', function(response) {
				jQuery('#admazing-widget div.inside').html(response);
				admazingMakeEditable();
			}, 'html');
			
		});
		return false;
	});

});

function admazingDelIcon(id) {
	jQuery.post(ajaxurl, '&action=admazing&do=del&id=' + id, function(response) {
		if(response==0) alert("Error deleting icon");
	});
	jQuery('li#admazing-icon-' + id).fadeOut(function () {
		jQuery('li#admazing-icon-' + id).remove();
	});
}

function admazingPhase2(n, font) {
	jQuery('div.admazing_step_1').hide();
	jQuery('div.admazing_step_2').show();
	jQuery('a.admazing_holder').html(String.fromCharCode(n) );
	jQuery('#icon-preview a').removeClass('fa').removeClass('genericon');
	jQuery('#icon-preview a').addClass( font );
	admazing_selected_icon=n;
	admazing_selected_font=font;
}
function admazingUpdate_name_preview() {
	name=jQuery('input[name=admazing_name]').val();
	if (name=='') name="Name";
	jQuery('#icon-preview span').html(name);	
}
function admazingMakeEditable() {
	if (jQuery.fn.sortable) {
		jQuery( "div.admazing-sortable ul" ).sortable({
			opacity: 0.6, 
			items: '> li:not(.new, .save, .forget)',
			update: function() {
				jQuery.post(ajaxurl, jQuery(this).sortable('serialize') + '&action=admazing&do=sort', function(response) {
					if(response==0) alert("Error saving sort order");
				});
			}
		});
		jQuery( "div.admazing-sortable ul" ).disableSelection();
		jQuery('div.admazing-sortable a').not('.delete_icon').not('li.new a, li.save a, li.forget a').click(function() {
			return false;
		});
	}

	jQuery('div.admazing-icons li.new a').click (function () {
		jQuery('div.admazing_step_1').show(); jQuery('div.admazing_step_1 div.admazing-icons img').show()
		jQuery('div.admazing_step_2').hide();
		tb_show('New Icon', "#TB_inline?inlineId=admazingPopup");
		jQuery('#TB_ajaxContent').attr('style','');
		jQuery('#TB_window').css('backgroundImage','none');
		jQuery('div.admazing_step_1 div.admazing-icons').css({height:jQuery('#TB_window').innerHeight()-155});
		jQuery('#TB_ajaxContent a.cancel').click(function() {
			tb_remove();
		});
		return false;
	});

	jQuery('li.save a').click(function () {
		jQuery.post(ajaxurl, 'action=admazing&do=saveDash', function(response) {
			jQuery('div.admazing-icons').parent().html(response);
		}, 'html');
		return false;
	});

	jQuery('li.forget a').click(function () {
		jQuery.post(ajaxurl, 'action=admazing&do=forgetDash', function(response) {
			jQuery('div.admazing-icons').parent().html(response);
		}, 'html');
		return false;
	});
}
