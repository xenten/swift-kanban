/**
 * 
 */

jQuery("#diBtnSubmitComent").live("click",function() {jQuery("#diBtnSubmitComent").attr("href","Javascript:di_submit_form()");});

jQuery(".trButtons a:first").live("click",function() {jQuery(".trButtons a:first").attr("href","Javascript:di_submit_form()");});
		
		//id='diBtnSubmitComent'
		//$('.trButtons a:first')

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(
			/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};

function di_submit_form() {

	// TODO: validate replies
	// add validation here
	if (jQuery("#diRT").val() == '') {

		if (jQuery("#txtdiBody").val() == '') {

			jQuery.diMsg({
				title : "Mistake.",
				body : "Please enter a message.",
				hideButton : false
			});
			return;

		}
		if (jQuery("#txtdiNickname").val() == '') {

			jQuery.diMsg({
				title : "Mistake.",
				body : "Please enter your name .",
				hideButton : false
			});
			return;

		}
		if (jQuery("#txtdiEmail").val() != '') {

			if (!isValidEmailAddress(jQuery("#txtdiEmail").val())) {
				jQuery
						.diMsg({
							title : "Mistake.",
							body : "Please enter a valid email address or leave this field blank",
							hideButton : false
						});
				return;
			}
		} 
	} else {
		
		if (jQuery("#txtdiReplyBody").val() == '') {

			jQuery.diMsg({
				title : "Mistake.",
				body : "Please enter a message.",
				hideButton : false
			});
			return;

		}
		if (jQuery("#txtdiRNickname").val() == '') {

			jQuery.diMsg({
				title : "Mistake.",
				body : "Please enter your name .",
				hideButton : false
			});
			return;

		}
		if (jQuery("#txtdiREmail").val() != '') {

			if (!isValidEmailAddress(jQuery("#txtdiREmail").val())) {
				jQuery
						.diMsg({
							title : "Mistake.",
							body : "Please enter a valid email address or leave this field blank",
							hideButton : false
						});
				return;
			}
		}
	}

	jQuery.diMsg({
		body : "Please wait...",
		title : "Posting message",
		hideButton : true
	});
	// jQuery("#comment-form").submit();
	jQuery(".comment-form").append(
			'<input type="hidden" id="diRT" name="diRT" value="'
					+ jQuery("#diRT").val() + '"></input>');
	jQuery(".comment-form").append(
			'<input type="hidden" id="diIdent" name="diIdent" value="'
					+ jQuery("#diIdent").val() + '"></input>');
	jQuery(".comment-form").submit();

}

function submitform() {

	jQuery.diMsg({
		body : "Please wait...",
		title : "Posting message",
		hideButton : true
	});
	jQuery("#comment-form").submit();
}
