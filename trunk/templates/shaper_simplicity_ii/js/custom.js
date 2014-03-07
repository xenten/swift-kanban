/*! jquery.cookie v1.4.0 | MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{a=decodeURIComponent(a.replace(g," "))}catch(b){return}try{return h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setDate(k.getDate()+j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0!==a.cookie(b)?(a.cookie(b,"",a.extend({},c,{expires:-1})),!0):!1}});


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
        open: function(event, ui) {
            $('.survey_dialog').click(function() {
                window.location='http://www.swift-kanban.com/trial-popup';
            });

            var survey_close = $('.ui-icon-closethick');
            survey_close.click(function()
            {
                $('#survey').dialog('close');
                return false;
            });
        }
    });
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




//jQuery.noConflict();
var social_titles = function(){
    document.getElementById('0').title='Like us on Facebook!';
    document.getElementById('1').title='Talk to us on Twitter!';
    document.getElementById('2').title='Check out our Blog - SwiftStream and give us feedback!';
    document.getElementById('3').title='Check out our video tutorials and webinar recordings!';
    document.getElementById('4').title='Join the SwiftKanban User Group on LinkedIn!';
};

jQuery(function() {
if(document.getElementById('partner'))
{
    var jQuerytabs = jQuery('#tabs').tabs();
    var nav_string;
    jQuery(".partner-offer").each(function(i){


        var totalSize = jQuery(".partner-offer").size() - 1;
        nav_string="<div class='partner-offer-footer'>";

        if (i != 0) {
            prev = i;
            nav_string=nav_string+"<a href='#' class='prev-tab mover' rel='" + prev + "'>" +
                "<img src='http://www.swiftkanban.com/images/partner-prev-btn.png' alt=''></a>";
        }
        if (i != totalSize) {
            next = i + 2;
            nav_string=nav_string+"<a href='#' class='next-tab mover' rel='" + next + "'>" +
                "<img src='http://www.swiftkanban.com/images/partner-next-btn.png' alt=''></a>";
        }
        nav_string=nav_string+"</div>";


        jQuery(this).prepend(nav_string);

    });

    jQuery('.next-tab, .prev-tab').click(function() {
        jQuerytabs.tabs('select', jQuery(this).attr("rel"));
        return false;
    });
    jQuery( "#tabs" ).bind( "tabsselect", function(event, ui) {
        document.getElementById('displayme').scrollIntoView();
    });

}

});

/*Survey Popup code starts here*/
var display_survey_first_time = 150;  //time in seconds
var display_survey_next_delay = 600;  //time in seconds
var currentTime = (new Date()).getTime();

if(window.location.pathname.indexOf('free-kanban-trial')>=0)
    jQuery.cookie("free_trial", true);

jQuery.cookie("first_visit_time", currentTime); // Sample 2
if( jQuery.cookie("first_visit_time")==null && jQuery.cookie("survey_popup_displayed")==null)
    jQuery.cookie("first_visit_time", currentTime);

jQuery.doTimeout(1000, function(){
    var newTime = (new Date()).getTime();
    if(jQuery.cookie("survey_popup_displayed")!=null)
        return false;
    if(newTime-jQuery.cookie("first_visit_time") > (display_survey_first_time*1000) )
    {
        showDialog();
        return false;
    }
    return true;
});

var survey_click = function(btn_value){
    var survey = jQuery('#survey');
    survey.dialog('close');
    if(btn_value=='yes')
        window.open('http://www.swiftkanban.com/survey');
}
var showDialog = function (popup_type)
{
    jQuery.cookie("survey_popup_displayed","true",{ expires: 30 });
    loc = window.location;
    if (loc.pathname.indexOf("free-kanban-trial") >= 0)
        return;

    jQuery.doTimeout(5000, function(){
        var popup_type = randomPopupGenerator();
        if(popup_type==2 && (jQuery.cookie("free_trial")==null))
            show_free_trial_dialog();
        else
            show_survey_dialog();

    });
}

var show_survey_dialog = function()
{
    jQuery( "#survey" ).dialog({

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
    jQuery( "#release_30" ).dialog({

        width: '1000px',
        height: '500px',
        modal: true,
        resizable: false,
        draggable: false,
        dialogClass:'release_dialog',
        open:function(event, ui){
            _gaq.push(['_trackEvent', '3.0 Release Popup', 'Displayed', 'Release']);
        },
        position: { my: "center", at: "bottom", of: '#popup_head' },
        create: function(event, ui) {
            //jQuery("body").css({ overflow: 'hidden' })
            release_dialog=1;
        },
        beforeClose: function(event, ui) {
            //jQuery("body").css({ overflow: 'inherit' })
            release_dialog=0;
        }

});

    jQuery.cookie("release_30","true",{ expires: 30 });
};

var show_free_trial_dialog = function()
{
    jQuery( "#survey-get-free" ).dialog({
        width: '457px',
        height: 'auto',
        modal: true,
        resizable: false,
        draggable: false,
        dialogClass:'free_trial_dialog',
        open: function(event, ui) {
            jQuery('.free_trial_dialog').click(function() {
                window.location='http://www.swiftkanban.com/trial-popup';
            });

            var free_trial_close = jQuery('.ui-icon-closethick');
            free_trial_close.click(function()
            {
                jQuery('#survey-get-free').dialog('close');
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
    window.open("https://twitter.com/intent/follow?original_referer=http%3A%2F%2Fwww.swiftkanban.com%2Ftest%2Ftest-font-data-uri.html&region=follow_link&screen_name=swiftkanban&tw_p=followbutton&variant=2.0", "mywindow", "menubar=1,resizable=0,width=565,height=520");
}

/* javascript for follow twitter popup ends here */

