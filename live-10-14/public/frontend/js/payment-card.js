$(".form").find(".cd-numbers").find(".fields").find("input").on('keyup change', function(e){
        
        var charLength = $(this).val().length;
    
        $(".card").removeClass("flip");
        
        if(charLength == 4){
            $(this).next("input").focus();
        }
    
        if($(this).hasClass("1")){
            var inputVal = $(this).val();
            if(!inputVal.length == 0){
                $(".card").find(".front").find(".cd-number").find("span.num-1").text(inputVal);
            }
        }
    
        if($(this).hasClass("2")){
            var inputVal = $(this).val();
            if(!inputVal.length == 0){
                $(".card").find(".front").find(".cd-number").find("span.num-2").text(inputVal);
            }
        }   
    
        if($(this).hasClass("3")){
            var inputVal = $(this).val();
            if(!inputVal.length == 0){
                $(".card").find(".front").find(".cd-number").find("span.num-3").text(inputVal);
            }
        }   
    
        if($(this).hasClass("4")){
            var inputVal = $(this).val();
            if(!inputVal.length == 0){
                $(".card").find(".front").find(".cd-number").find("span.num-4").text(inputVal);
            }
        }   
    
});
$(".form").find(".cd-holder").find("input").on('keyup change', function(e){
    var inputValCdHolder = $(this).val();

    $(".card").removeClass("flip"); 
    
    if(!inputValCdHolder.length == 0){
        $(".card").find(".front").find(".bottom").find(".cardholder").find("p.holder").text(inputValCdHolder)
    }
    
});
$(".form").find(".cd-validate").find(".cvc").find('input').on('keyup change', function(e){
    var inputCvcVal = $(this).val();
    
    if(!inputCvcVal.length == 0){
        $(".card").addClass("flip").find(".cvc").find("p").text(inputCvcVal);
    }else   if(inputCvcVal.length == 0){
        $(".card").removeClass("flip");
    } 
});
$(".form").find(".cd-validate").find(".expiration").find('select#month').on('keyup change', function(){
    
    $(".card").removeClass("flip"); 
    if(!$(this).val().length == 0){
        $(".card").find('.bottom').find('.expires').find("p").find("span.month").text($(this).val())
    }
    
});
$(".form").find(".cd-validate").find(".expiration").find('select#year').on('keyup change', function(){
    
    $(".card").removeClass("flip"); 
    if(!$(this).val().length == 0){
        $(".card").find('.bottom').find('.expires').find("p").find("span.year").text($(this).val())
    }
    
});
$("button.submit").on('click', function(e){
    e.preventDefault();
    $(this).parents("form").submit();
});