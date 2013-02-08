var social_titles = function(){
    document.getElementById('0').title='Like us on Facebook!';
    document.getElementById('1').title='Talk to us on Twitter!';
    document.getElementById('2').title='Check out our Blog and give us feedback!';
    document.getElementById('3').title='Check out our video tutorials and webinar recordings!';
    document.getElementById('4').title='Join the Swift-Kanban User Group on LinkedIn!';
}

$(function() {
if(document.getElementById('partner'))
{
    var $tabs = $('#tabs').tabs();
    var nav_string;
    $(".partner-offer").each(function(i){


        var totalSize = $(".partner-offer").size() - 1;
        nav_string="<div class='partner-offer-footer'>";

        if (i != 0) {
            prev = i;
            nav_string=nav_string+"<a href='#' class='prev-tab mover' rel='" + prev + "'>" +
                "<img src='http://www.swift-kanban.com/images/partner-prev-btn.png' alt=''></a>";
        }
        if (i != totalSize) {
            next = i + 2;
            nav_string=nav_string+"<a href='#' class='next-tab mover' rel='" + next + "'>" +
                "<img src='http://www.swift-kanban.com/images/partner-next-btn.png' alt=''></a>";
        }
        nav_string=nav_string+"</div>";


        $(this).prepend(nav_string);

    });

    $('.next-tab, .prev-tab').click(function() {
        $tabs.tabs('select', $(this).attr("rel"));
        return false;
    });
    $( "#tabs" ).bind( "tabsselect", function(event, ui) {
        document.getElementById('displayme').scrollIntoView();
    });

}

});


