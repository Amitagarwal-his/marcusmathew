/*Admin options pannal data value*/
function weblizar_option_data_save(name) {
  var weblizar_settings_save = "#weblizar_settings_save_" + name;
  var weblizar_theme_options = "#weblizar_theme_options_" + name;
  var weblizar_settings_save_success = weblizar_settings_save + "_success";
  var loding_image = "#weblizar_loding_" + name;
  var weblizar_loding_image = loding_image + "_image";

  jQuery(weblizar_loding_image).show();
  jQuery(weblizar_settings_save).val("1");
  jQuery.ajax({
    url: "themes.php?page=weblizar",
    type: "post",
    data: jQuery(weblizar_theme_options).serialize(),
    success: function(data) {
      jQuery(weblizar_loding_image).fadeOut();
      jQuery(weblizar_settings_save_success).show();
      jQuery(weblizar_settings_save_success).fadeOut(5000);
    }
  });
}
/*Admin options value reset */
function weblizar_option_data_reset(name) {
  var r = confirm("Do you want reset your theme setting!");
  if (r == true) {
    var weblizar_settings_save = "#weblizar_settings_save_" + name;
    var weblizar_theme_options = "#weblizar_theme_options_" + name;
    var weblizar_settings_save_reset = weblizar_settings_save + "_reset";
    jQuery(weblizar_settings_save).val("2");
    jQuery.ajax({
      url: "themes.php?page=weblizar",
      type: "post",
      data: jQuery(weblizar_theme_options).serialize(),
      success: function(data) {
        jQuery(weblizar_settings_save_reset).show();
        jQuery(weblizar_settings_save_reset).fadeOut(5000);
      }
    });
  } else {
    alert("Cancel! reset theme setting process");
  }
}
// js to active the link of option pannel
jQuery(document).ready(function() {
  jQuery("ul li.active ul").slideDown();
  // menu click
  jQuery("#nav > li > a").click(function() {
    if (jQuery(this).attr("class") != "active") {
      jQuery("#nav li ul").slideUp(350);
      jQuery(this).next().slideToggle(350);
      jQuery("#nav li a").removeClass("active");
      jQuery(this).addClass("active");

      jQuery("ul.options_tabs li").removeClass("active");
      jQuery(this).parent().addClass("active");
      var divid = jQuery(this).attr("id");
      var add = "div#option-" + divid;
      var strlenght = add.length;

      if (strlenght < 17) {
        var add = "div#option-ui-id-" + divid;
        var ulid = "#ui-id-" + divid;
        jQuery("ul.options_tabs li li ").removeClass("currunt");
        jQuery(ulid).parent().addClass("currunt");
      }
      jQuery("div.ui-tabs-panel").addClass("deactive").fadeIn(1000);
      jQuery("div.ui-tabs-panel").removeClass("active");
      jQuery(add).removeClass("deactive");
      jQuery(add).addClass("active");
    }
  });

  // child submenu click
  jQuery("ul.options_tabs li li ").click(function() {
    jQuery("ul.options_tabs li li ").removeClass("currunt");
    jQuery(this).addClass("currunt");
    var option_name = jQuery(this).children("a").attr("id");
    var option_add = "div#option-" + option_name;
    jQuery("div.ui-tabs-panel").addClass("deactive").fadeIn(1000);
    jQuery("div.ui-tabs-panel").removeClass("active");
    jQuery(option_add).removeClass("deactive");
    jQuery(option_add).addClass("active");
  });

  /********media-upload******/
  // media upload js
  var uploadID = ""; /*setup the var*/
  var showImg = "";
  jQuery(".upload_image_button").click(function() {
    uploadID = jQuery(this).prev("input"); /*grab the specific input*/
    showImg = jQuery(this).nextAll("img");
    formfield = jQuery(".upload").attr("name");
    tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");

    window.send_to_editor = function(html) {
      imgurl = jQuery("img", html).attr("src");
      showImg.attr("src", imgurl);
      uploadID.val(imgurl); /*assign the value to the input*/
      tb_remove();
    };
    return false;
  });
});

/****  For Option panle facebook Like ******/
(function(d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
})(document, "script", "facebook-jssdk");

/****  For Option panle twitter follower and Like ******/

!(function(d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0];
  if (!d.getElementById(id)) {
    js = d.createElement(s);
    js.id = id;
    js.src = "//platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);
  }
})(document, "script", "twitter-wjs");

(function($) {
  // Add Color Picker to all inputs that have 'color-field' class
  $(function() {
    $(".color-field").wpColorPicker();
  });
})(jQuery);

function SaveSettings() {
  var FbAppId = jQuery("#twitter-page-id-fetch").val();
  var User_name_3 = jQuery("#twitter-page-user-name").val();
  var show_theme = jQuery("#show-theme-background").val();
  var Height = jQuery("#twitter-page-url-Height").val();
  var link_color = jQuery("#twitter-page-lnk-Color").val();
  var replieses = jQuery("#exclude_replies_23").val();
  var photos_acces = jQuery("#photo_1234").val();
  var tw_language = jQuery("#tw_language").val();
  if (!FbAppId) {
    jQuery("#twitter-page-id-fetch").focus();
    return false;
  }
  jQuery("#fb-save-settings").hide();
  jQuery("#fb-img").show();
  jQuery.ajax({
    url: location.href,
    type: "POST",
    data: jQuery("form#fb-form").serialize(),
    dataType: "html",
    //Do not cache the page
    cache: false,
    //success
    success: function(html) {
      jQuery("#fb-img").hide();
      jQuery("#fb-msg").show();
      setTimeout(function() {
        location.reload(true);
      }, 2000);
    }
  });
}

function SaveApiSettings() {
  var wl_twitter_consumer_key = jQuery("#wl_twitter_consumer_key").val();
  var wl_twitter_consumer_secret = jQuery("#wl_twitter_consumer_secret").val();
  var wl_twitter_access_token = jQuery("#wl_twitter_access_token").val();
  var wl_twitter_token_secret = jQuery("#wl_twitter_token_secret").val();
  if (!wl_twitter_consumer_key) {
    jQuery("#wl_twitter_consumer_key").focus();
    return false;
  }

  if (!wl_twitter_consumer_secret) {
    jQuery("#wl_twitter_consumer_secret").focus();
    return false;
  }

  if (!wl_twitter_access_token) {
    jQuery("#wl_twitter_access_token").focus();
    return false;
  }

  if (!wl_twitter_token_secret) {
    jQuery("#wl_twitter_token_secret").focus();
    return false;
  }
  jQuery("#fb-api-save-settings").hide();
  jQuery("#twitter-img").show();
  jQuery.ajax({
    url: location.href,
    type: "POST",
    data: jQuery("form#api-form").serialize(),
    dataType: "html",
    //Do not cache the page
    cache: false,
    //success
    success: function(html) {
      jQuery("#twitter-img").hide();
      jQuery("#wl_twitter_preview").show();
      setTimeout(function() {
        location.reload(true);
      }, 2000);
    }
  });
}

!(function(d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0],
    p = /^http:/.test(d.location) ? "http" : "https";
  if (!d.getElementById(id)) {
    js = d.createElement(s);
    js.id = id;
    js.src = p + "://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);
  }
})(document, "script", "twitter-wjs");
