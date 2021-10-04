jQuery(document).ready(function($){
  $('#signUp').click(function(){
    $(this).val('Submitting...');
    var firstname = $('#firstName').val();
    var lastname = $('#lastName').val();
    var useremail = $('#userEmail').val();
    $('.form-error').remove();
    $('input').css('border', '1px solid #ccc');
    $.ajax({
      url : ajax_custom.ajaxurl,
      type : 'post',
      data : {
        action : 'custom_signup',
        firstname : firstname,
        lastname : lastname,
        useremail : useremail,
      },
      success : function( response ) {
        $('#signUp').val('Sign-Up');
        if(response == 'fullempty'){
          $('#firstName').css('border', '1px solid red');
          $('#firstName').after('<span class="form-error">Please fill this field</span>');
          $('#lastName').css('border', '1px solid red');
          $('#lastName').after('<span class="form-error">Please fill this field</span>');
          $('#userEmail').css('border', '1px solid red');
          $('#userEmail').after('<span class="form-error">Please fill this field</span>');
        }else if(response == 'empty'){
          $('#lastName').css('border', '1px solid red');
          $('#lastName').after('<span class="form-error">Please fill this field</span>');
          $('#userEmail').css('border', '1px solid red');
          $('#userEmail').after('<span class="form-error">Please fill this field</span>');
        }else if(response == 'firstname') {
          $('#firstName').css('border', '1px solid red');
          $('#firstName').after('<span class="form-error">Please fill this field</span>');
        }else if(response == 'lastName') {
          $('#lastName').css('border', '1px solid red');
          $('#lastName').after('<span class="form-error">Please fill this field</span>');
        }else if(response == 'email') {
          $('#userEmail').css('border', '1px solid red');
          $('#userEmail').after('<span class="form-error">Please fill this field</span>');
        }else if(response == 'invalid') {
          $('#userEmail').css('border', '1px solid red');
          $('#userEmail').after('<span class="form-error">Please enter valid email address</span>');
        }else if(response == 'emailexist'){
          $('#userEmail').css('border', '1px solid red');
          $('#userEmail').after('<span class="form-error">Email already exists</span>');  
        }else if(response == 'failed'){
          $('#signUp').after('<span class="form-error">Opps! something error');
        }else {
          $('.donateform .form-clear').remove();
          $('.formtxt').text('Thank you for signing up. Please consider making a donation to my grassroots campaign if you support my policy objectives.');
          $('#signUp').remove();
            //$('.btn-blue').before('<span class="form-success">Thank you!</form>');
        }
      }
    });
  });
});


jQuery(document).ready(function($) {
  $(".set > a").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this)
        .siblings(".content")
        .slideUp(200);
      $(".set > a .toggleright_icon i")
        .removeClass("fa-chevron-up")
        .addClass("fa-chevron-down");
    } else {
      $(".set > a .toggleright_icon i")
        .removeClass("fa-chevron-up")
        .addClass("fa-chevron-down");
      $(this)
        .find(".toggleright_icon i")
        .removeClass("fa-chevron-down")
        .addClass("fa-chevron-up");
      $(".set > a").removeClass("active");
      $(this).addClass("active");
      $(".content").slideUp(200);
      $(this)
        .siblings(".content")
        .slideDown(200);
    }
  });
});


jQuery(document).ready( function($) {
  $("#more_posts").click(function(event) {
    event.preventDefault();
    $('.lds-dual-ring').remove();
    // Post per page
    var ppp = 3; 
    var offset = $(this).attr('data-offset'); 
    var totalpostcounts = $('.totalpostcounts').html();
    
    $('#more_posts').before('<span class="lds-dual-ring"></span>');
    $.ajax({
      type: "POST",
      url: ajax_custom.ajaxurl,
      data : {
        action : 'more_post_ajax',
        ppp: ppp,
        offset: offset,
      },
      success: function(response){
        var finaloffset = parseInt(offset) + parseInt(ppp);
        $('#more_posts').attr('data-offset', finaloffset);
        $('.media-listing').last().append(response);
        $('.lds-dual-ring').remove();
        var finalTotal = parseInt(totalpostcounts);
        var finaloffest = $('#more_posts').attr('data-offset');
        if(finalTotal <= finaloffest) {
          $('#more_posts').remove();
        }
      },
    });
  });
});


jQuery(document).ready( function($) {
  $("#more_custom_posts").click(function(event) {
    event.preventDefault();
    $('.lds-dual-ring').remove();
    // Post per page
    var ppp = 3; 
    var offset = $(this).attr('data-offset'); 
    var totalcustompost = $('.totalcustompost').html();
    
    $('#more_custom_posts').before('<span class="lds-dual-ring"></span>');
    $.ajax({
      type: "POST",
      url: ajax_custom.ajaxurl,
      data : {
        action : 'more_custom_post_ajax',
        ppp: ppp,
        offset: offset,
      },
      success: function(response){
        var finaloffset = parseInt(offset) + parseInt(ppp);
        $('#more_custom_posts').attr('data-offset', finaloffset);
        $('.custom-list').last().append(response);
        $('.lds-dual-ring').remove();
        var finalCustomTotal = parseInt(totalcustompost);
        var finalCustomoffest = $('#more_custom_posts').attr('data-offset');
        if(finalCustomTotal <= finalCustomoffest) {
          $('#more_custom_posts').remove();
        }
      },
    });
  });
});

jQuery(document).ready(function($){
  $('#cookie_hdr_showagain').click(function(e){
    e.stopPropagation();
    $('.cli-modal').addClass('cli-show');
    $('.cli-modal').addClass('cli-blowup');
    $('.cli-modal-backdrop').addClass('cli-show');
    $('.cli-modal').removeClass('cli-out');
  });
  $('#cliModalClose').click(function(){
    $('.cli-modal-backdrop').removeClass('cli-show');
  });
  $('body').click(function(){
    $('.cli-modal-backdrop').removeClass('cli-show');
  });
});