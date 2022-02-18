$(document).ready(function(){
    $("ul#tabs li").click(function(e){
        if (!$(this).hasClass("active")) {
            var tabNum = $(this).index();
            var nthChild = tabNum+1;
            $("ul#tabs li.active").removeClass("active");
            $(this).addClass("active");
            $(".main-content").css({
             "background": $(this).data("bg-color")
            });

            $("ul#tab li.active").removeClass("active");
            $("ul#tab li:nth-child("+nthChild+")").addClass("active");

        }
    });
});
