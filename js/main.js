$(document).ready(function(){

	// ---
	function off_animate( $t ){
		$($t).find("svg .fil0").animate(
			{"width":2001, "opacity":0.5},
			600 )
		}

	// ---
	function on_animate( $t ){
		$($t).find("svg .fil0").animate(
			{"width":4301, "opacity":1},
			600 )
	}

	// ---
	var off_future = false;
	var off_passed = true;
	$(".passed").fadeToggle();
	on_animate( $("#passed") )


	// ---
	$("#future").click(function(){
		if( off_future ) off_animate( this );
		else on_animate( this );
		off_future = ! off_future;
		$(".future").fadeToggle();
	})

	$("#passed").click(function(){
		if( off_passed ) off_animate( this );
		else on_animate( this );
		off_passed = ! off_passed;
		$(".passed").fadeToggle();
	})

	// todo dox

})