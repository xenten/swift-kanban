
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

