var CreditCard = (function() {
  
    var init = function() {
      $('.credit-card.front input, .credit-card.front  select').on('focus', front_focus);
      $('.credit-card.back input').on('focus', back_focus);
    };
    
    var front_focus = function() {
      $('.credit-card.back').addClass('unfocused');
      $(this).closest('.credit-card').removeClass('unfocused');
    };
    
    var back_focus = function() {
      $('.credit-card.front').addClass('unfocused');
      $(this).closest('.credit-card').removeClass('unfocused');
    };
    
    return {
      init: init
  }
    
  }());
  
$(CreditCard.init);

  $('.price-col').on('click', function () {
    $('.price-col').removeClass('active');
    $('#monthInput').val($(this).data('month'));
    $(this).addClass('active');
    $('.step.choose').show();

    let date = new Date();

    date.setMonth(date.getMonth() + parseInt($(this).data('month')));
    
    $('.receipt-date').text(`${date.getFullYear()}-${date.getMonth}-${date.getDay()}`);
    $('.receipt-price').text($(this).data('price'));
    
  });

  $('.method').on('click', function(){
    if($(this).hasClass('card'))
    {
      $('#type-card').show();

    } else {
      $('#type-card').hide();
    }
    /* $('.step').style('opacity','1');
    $('.step').style('cursor','not-allowed'); */
  })