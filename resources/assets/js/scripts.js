$(document).ready(function () {
   if($(".error_box").length !== 0){
       $(".error_box").animate({
           right: "10"
       }, 500, function () {
           setTimeout(function () {
               $(".error_box").animate({
                   right: "-350"
               }, 500, function () {
                   $(".error_box").css("display","none")
               })
           }, 3000)
       })
   }

    $(".close_box").click(function () {
        $(".error_box").animate({
            right: "-350"
        }, 500, function () {
            $(".error_box").css("display","none")
        })
    });
});