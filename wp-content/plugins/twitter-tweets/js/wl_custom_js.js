function rangeslider_value(source, target) {
	var slider = document.getElementById(source);
	var output = document.getElementById(target);
	output.innerHTML = slider.value;

	slider.oninput = function() {
	  output.innerHTML = this.value;
	}	
}

rangeslider_value("wl_twitter_tweets", "wl_twitter_range_show");
rangeslider_value("twitter-page-url-Height", "twitter-range-val");