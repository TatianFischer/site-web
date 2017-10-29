$(function () {

    $('#to-top').hide();
    $(window).on("scroll", function() {
        if($(window).scrollTop() == 0){
            $('#to-top').hide();
        } else {
            $('#to-top').show();
        }
    });
    
});

/*
            
                            
            */