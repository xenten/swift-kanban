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

/*Survey Popup code starts here*/
var display_survey_first_time = 150;  //time in seconds
var display_survey_next_delay = 600;  //time in seconds
var currentTime = (new Date()).getTime();

if(window.location.pathname.indexOf('free-kanban-trial')>=0)
    $.cookie("free_trial", true);

$.cookie("first_visit_time", currentTime); // Sample 2
if( $.cookie("first_visit_time")==null && $.cookie("survey_popup_displayed")==null)
    $.cookie("first_visit_time", currentTime);

$.doTimeout(1000, function(){
    var newTime = (new Date()).getTime();
    if($.cookie("survey_popup_displayed")!=null)
        return false;
    if(newTime-$.cookie("first_visit_time") > (display_survey_first_time*1000) )
    {
        showDialog();
        return false;
    }
    return true;
});

var survey_click = function(btn_value){
    var survey = $('#survey');
    survey.dialog('close');
    if(btn_value=='yes')
        window.open('http://www.swift-kanban.com/survey');
}
var showDialog = function (popup_type)
{
    $.cookie("survey_popup_displayed","true",{ expires: 30 });
    loc = window.location;
    if (loc.pathname.indexOf("free-kanban-trial") >= 0)
        return;

    $.doTimeout(5000, function(){
        var popup_type = randomPopupGenerator();
        if(popup_type==2 && ($.cookie("free_trial")==null))
            show_free_trial_dialog();
        else
            show_survey_dialog();

    });
}

var show_survey_dialog = function()
{
    $( "#survey" ).dialog({

        width: '457px',
        height: 'auto',
        modal: true,
        resizable: false,
        draggable: false,
        dialogClass:'survey_dialog',
        open:function(event, ui){
            _gaq.push(['_trackEvent', 'Website Popup', 'Displayed', 'Website Survey']);
        }
    });
};

var release_dialog = 0;
var show_release_dialog = function()
{
    $( "#release_30" ).dialog({

        width: '1000px',
        height: '500px',
        modal: true,
        resizable: false,
        draggable: false,
        dialogClass:'release_dialog',
        open:function(event, ui){
            _gaq.push(['_trackEvent', '3.0 Release Popup', 'Displayed', 'Release']);
        },
        position: { my: "center", at: "bottom", of: '#header' },
        create: function(event, ui) {
            //$("body").css({ overflow: 'hidden' })
            release_dialog=1;
        },
        beforeClose: function(event, ui) {
            //$("body").css({ overflow: 'inherit' })
            release_dialog=0;
        }

});

    $.cookie("release_30","true",{ expires: 30 });
};

var show_free_trial_dialog = function()
{
    $( "#survey-get-free" ).dialog({
        width: '457px',
        height: 'auto',
        modal: true,
        resizable: false,
        draggable: false,
        dialogClass:'free_trial_dialog',
        open: function(event, ui) {
            $('.free_trial_dialog').click(function() {
                window.location='http://www.swift-kanban.com/trial-popup';
            });

            var free_trial_close = $('.ui-icon-closethick');
            free_trial_close.click(function()
            {
                $('#survey-get-free').dialog('close');
                return false;
            });

            _gaq.push(['_trackEvent', 'Website Popup', 'Displayed', 'Get Free Trial']);
        }
    });
};


var randomPopupGenerator = function ()
{
    var from= 2, to=3;
    return Math.floor(Math.random()*(to-from+1)+from);
}

/*Survey Popup code ends here*/
/*Social Media code starts here*/
/*UserVoice code starts here*/
var uservoiceOptions = {
    /* required */
    key: 'swiftkanban',
    host: 'swiftkanban.uservoice.com',
    forum: '85253',
    showTab: true,
    /* optional */
    alignment: 'left',
    background_color:'#f00',
    text_color: 'white',
    hover_color: '#06C',
    lang: 'en'
};
function _loadUserVoice() {
    var s = document.createElement('script');
    s.setAttribute('type', 'text/javascript');
    s.setAttribute('src', ("https:" == document.location.protocol ? "https://" : "http://") + "cdn.uservoice.com/javascripts/widgets/tab.js");
    document.getElementsByTagName('head')[0].appendChild(s);
}
_loadSuper = window.onload;
window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() {
    _loadSuper();
    _loadUserVoice();
};
/*UserVoice code ends here*/
/*Social Media code ends here*/


/* javascript for follow twitter popup starts here */

function follow_twitter() {
    window.open("https://twitter.com/intent/follow?original_referer=http%3A%2F%2Fwww.swift-kanban.com%2Ftest%2Ftest-font-data-uri.html&region=follow_link&screen_name=swiftkanban&tw_p=followbutton&variant=2.0", "mywindow", "menubar=1,resizable=0,width=565,height=520");
}

/* javascript for follow twitter popup ends here */

