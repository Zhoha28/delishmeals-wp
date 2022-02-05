jQuery(function( $ ) {
	//Display options when a custom start is selected
	$("#_wscsd_delay_type").change(function(){
	    $(".wscsd_delay_additional_options").css("display", "none");
	     var str = $("#_wscsd_delay_type").val();
	     if( !str ) {
	        $(".wscsd_hide_date").css("display", "block");
		 	$(".wscsd_cut_off").css("display", "none");
		 	$(".wscsd_max_dates").css("display", "none");
		 	$(".wscsd_label").css("display", "none");
		 	
	    } else {
	    	
	    	if( str == 'delay') {
	    		$(".wscsd_hide_date").css("display", "block");
		 		$(".wscsd_cut_off").css("display", "none");
		 		$(".wscsd_max_dates").css("display", "none");
		 	} else if( str == 'fixed' ){
		 		$(".wscsd_hide_date").css("display", "block");
		 		$(".wscsd_cut_off").css("display", "block");
		 		$(".wscsd_max_dates").css("display", "block");
		 	} else {
		 		$(".wscsd_hide_date").css("display", "none");
		 		$(".wscsd_label").css("display", "block");
		 		$(".wscsd_max_dates").css("display", "none");
		 		$(".wscsd_cut_off").css("display", "block");		
		 	}
	    }
		$(".wscsd_"+str).css("display", "block");
	});

	
	//Display date options
	$("#_wscsd_hide_date").click(function(){
	    $(".wscsd_hide_date_options").css("display", "block");
	     if($("#_wscsd_hide_date").prop("checked") == false) {
	          $(".wscsd_hide_date_options").css("display", "block");
	          $(".wscsd_max_dates").css("display", "block");
	          $(".wscsd_label").css("display", "block");
	    } else {
	    	$(".wscsd_hide_date_options").css("display", "none");
	    	$(".wscsd_max_dates").css("display", "none");
	    	$(".wscsd_label").css("display", "none");
	    }
	});

	/*****
	Fixed dates
	******/
	//Add dates
	$('body').on('click', '#_wscsd_fixed_start_dates_add', function(){
		var start_date = $(this).parent().siblings("#_wscsd_fixed_start_dates_field").val();
		if ($(this).closest('.wscsd_fixed').children('.wscsd_fixed_start_dates_save').val().indexOf(start_date) < 0) {
			$(this).closest('._wscsd_fixed_start_dates_field_field').siblings("#_wscsd_fixed_start_dates_display").append("<span class='wscsd_fixed_start_date'><span class='wscsd_fixed_start_date_data'>"+start_date+"</span><span class='wscsd_fixed_start_date_delete'> X </span></span>");
    	  	$(this).closest('.wscsd_fixed').children('.wscsd_fixed_start_dates_save').val($(this).closest('.wscsd_fixed').children('.wscsd_fixed_start_dates_save').val()  + start_date + '|');
    	  	$(this).parent().siblings("#_wscsd_fixed_start_dates_field").val('');
		}
	});
	//Delete dates
	$('body').on('click', '.wscsd_fixed_start_date_delete', function(){
		var date_to_delete = $(this).closest('.wscsd_fixed_start_date').children('.wscsd_fixed_start_date_data').html() + '|';
		$('#_wscsd_fixed_start_dates_save').val($('#_wscsd_fixed_start_dates_save').val().replace(date_to_delete, ''));
		//variation
		var var_old_dates = $(this).closest('.wscsd_variation').children('.wscsd_fixed_start_dates_save').val();
		if ( (var_old_dates) && (var_old_dates.length > 0) ) {
			$(this).closest('.wscsd_variation').children('.wscsd_fixed_start_dates_save').val($(this).closest('.wscsd_variation').children('.wscsd_fixed_start_dates_save').val().replace(date_to_delete, ''));
		}
		//end variation
	    $(this).closest('.wscsd_fixed_start_date').remove();
	});
	//Delete all dates
	$('body').on('click', '.wscsd_fixed_start_dates_deleteall', function(){
		$(this).closest('.wscsd_fixed').children('.wscsd_fixed_start_dates_save').val('');
	    $(this).siblings("#_wscsd_fixed_start_dates_display").children('.wscsd_fixed_start_date').remove();
	});

	/*****
	Fixed delays
	******/
	//Add dates
	$('body').on('click', '#_wscsd_fixed_delays_add', function(){
		var start_delay = $(this).parent().siblings("#_wscsd_fixed_delay_field").val() + ' ' + $(this).parent().siblings("#_wscsd_fixed_delay_period").val();
		if ($(this).closest('.wscsd_delay').children('.wscsd_fixed_delays_save').val().indexOf(start_delay) < 0) {
			$(this).closest('.form-field').siblings("#_wscsd_fixed_delays_display").append("<span class='wscsd_fixed_delay'><span class='wscsd_fixed_delay_data'>"+start_delay+"</span><span class='wscsd_fixed_delay_delete'> X </span></span>");
    	  	$(this).closest('.wscsd_delay').children('.wscsd_fixed_delays_save').val($(this).closest('.wscsd_delay').children('.wscsd_fixed_delays_save').val()  + start_delay + '|');
    	  	$(this).parent().siblings('#_wscsd_fixed_delay_field').val('');
		}
	});
	//Delete dates
	$('body').on('click', '.wscsd_fixed_delay_delete', function(){
		var delay_to_delete = $(this).closest('.wscsd_fixed_delay').children('.wscsd_fixed_delay_data').html() + '|';
		//$('#_wscsd_fixed_delays_save').val($('#_wscsd_fixed_delays_save').val()  + delay_to_delete + '|');
		$('#_wscsd_fixed_delays_save').val($('#_wscsd_fixed_delays_save').val().replace(delay_to_delete, ''));
		//variation
		var var_old_delay = $(this).closest('.wscsd_variation').children('.wscsd_fixed_delays_save').val();
		if ( (var_old_delay) && (var_old_delay.length > 0) ) {
			$(this).closest('.wscsd_variation').children('.wscsd_fixed_delays_save').val($(this).closest('.wscsd_variation').children('.wscsd_fixed_delays_save').val().replace(delay_to_delete, ''));
		}
		
		//end variation
	    $(this).closest('.wscsd_fixed_delay').remove();
	});
	//Delete all dates
	$('body').on('click', '.wscsd_fixed_delays_deleteall', function(){
		$(this).closest('.wscsd_delay').children('.wscsd_fixed_delays_save').val('');
	    $(this).siblings("#_wscsd_fixed_delays_display").children('.wscsd_fixed_delay').remove();
	});

		//VARIATIONS
	$('body').on('change', '[id^="_wscsd_delay_type"]', function(event) {
	  	var variation_ID = '\[' + (event.target.id).split("[").pop();
	  	if ( ( variation_ID ) && ( 0 < variation_ID.length ) ) {
		  	var delay_type = $(document.getElementById("_wscsd_delay_type" + variation_ID )).val();
				$(document.getElementById("_wscsd_fixed" + variation_ID )).css("display", "none");
				$(document.getElementById("_wscsd_delay" + variation_ID )).css("display", "none");
				
		     if( !delay_type ) {
		        $(document.getElementById("_wscsd_hide_date_options" + variation_ID )).css("display", "block");
			 	$(document.getElementById("_wscsd_cut_off_field" + variation_ID )).css("display", "none");
			 	$(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "none");
			 	$(document.getElementById("_wscsd_start_date_label" + variation_ID )).css("display", "none");
			 	
		    } else {
		    	
		    	if( delay_type == 'delay') {
		    		$(document.getElementById("_wscsd_hide_date_options" + variation_ID )).css("display", "block");
			 		$(document.getElementById("_wscsd_cut_off_field" + variation_ID )).css("display", "none");
			 		$(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "none");
			 	} else if( delay_type == 'fixed' ){
			 		$(document.getElementById("_wscsd_hide_date_options" + variation_ID )).css("display", "block");
			 		$(document.getElementById("_wscsd_cut_off_field" + variation_ID )).css("display", "block");
			 		$(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "block");
			 	} else {
			 		$(document.getElementById("_wscsd_hide_date_options" + variation_ID )).css("display", "none");
			 		$(document.getElementById("_wscsd_start_date_label" + variation_ID )).css("display", "block");
			 		$(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "none");
			 		$(document.getElementById("_wscsd_cut_off_field" + variation_ID )).css("display", "block");		
			 	}
		    }
			$(document.getElementById("_wscsd_" + delay_type + variation_ID)).css("display", "block");
		}
	});

	//Display date options
	$('body').on('click', '[id^="_wscsd_hide_date"]', function(event) {
		var variation_ID = '\[' + (event.target.id).split("[").pop();
	    if ( ( variation_ID ) && ( 0 < variation_ID.length ) ) {
		    if($(document.getElementById("_wscsd_hide_date" + variation_ID )).prop("checked") == false) {
		          $(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "block");
		          $(document.getElementById("_wscsd_start_date_label" + variation_ID )).css("display", "block");
		    } else {
		    	$(document.getElementById("_wscsd_max_dates_field" + variation_ID )).css("display", "none");
		    	$(document.getElementById("_wscsd_start_date_label" + variation_ID )).css("display", "none");
		    }
		}
	});

	/*****
	Fixed dates
	******/
	//Add dates
	$('body').on('click', '[id^="_wscsd_fixed_start_dates_add"]', function(event){
		var variation_ID = '\[' + (event.target.id).split("[").pop();
		if ( ( variation_ID ) && ( 0 < variation_ID.length ) ) {
			var start_date = $(document.getElementById("_wscsd_fixed_start_dates_field" + variation_ID )).val();
			if ($(document.getElementById("_wscsd_fixed_start_dates_save" + variation_ID )).val().indexOf(start_date) < 0) {
				$(document.getElementById("_wscsd_fixed_start_dates_display" + variation_ID )).append("<span class='wscsd_fixed_start_date'><span class='wscsd_fixed_start_date_data'>"+start_date+"</span><span class='wscsd_fixed_start_date_delete'> X </span></span>");
	    	  	$(document.getElementById("_wscsd_fixed_start_dates_save" + variation_ID )).val($(this).closest('.wscsd_fixed').children('.wscsd_fixed_start_dates_save').val()  + start_date + '|');
	    	  	$(document.getElementById("_wscsd_fixed_start_dates_field" + variation_ID )).val('');
			}
		}
	});
	//Delete all dates
	$('body').on('click', '[id^="_wscsd_fixed_start_dates_deleteall"]', function(){
		var variation_ID = '\[' + (event.target.id).split("[").pop();
		if ( ( variation_ID ) && ( 0 < variation_ID ) ) {
			$(document.getElementById("_wscsd_fixed" + variation_ID)).children('.wscsd_fixed_start_dates_save').val('');
		    $(document.getElementById("_wscsd_fixed_start_dates_display" + variation_ID)).children('.wscsd_fixed_start_date').remove(); 
		}
	});
	//end variation

});
