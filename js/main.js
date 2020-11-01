$(document).ready(function(){

	/**
	 * \function off_animate
	 * \brief Animuje przejscie przycisku ze stanu on na off
	 * \param $t - target animacji, obiekt jQuery przycisku
	 */
	function off_animate( $t ){
		$($t).find("svg .fil0").animate(
			{"width":2001, "opacity":0.5},
			600 )
		}

	/**
	 * \function on_animate
	 * \brief Animuje przejscie przycisku ze stanu off na on
	 * \param $t - target animacji, obiekt jQuery przycisku
	 */
	function on_animate( $t ){
		$($t).find("svg .fil0").animate(
			{"width":4301, "opacity":1},
			600 )
	}

	// Wartości początkowe
	var off_future = false;
	var off_passed = true;
	$(".passed").fadeToggle();
	on_animate( $("#passed") )

	// Obsługa zdarzeń
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

})