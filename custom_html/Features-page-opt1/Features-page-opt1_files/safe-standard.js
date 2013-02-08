var psgiLtsid = "KJbyYiovRO8z";
// safe-standard@gecko.js

var psgiLtiso;
try {
	psgiLtiso = (opener != null) && (typeof(opener.name) != "unknown") && (opener.psgiLtwid != null);
} catch(e) {
	psgiLtiso = false;
}
if (psgiLtiso) {
	window.psgiLtwid = opener.psgiLtwid + 1;
	psgiLtsid = psgiLtsid + "_" + window.psgiLtwid;
} else {
	window.psgiLtwid = 1;
}
function psgiLtn() {
	return (new Date()).getTime();
}
var psgiLts = psgiLtn();
function psgiLtst(f, t) {
	if ((psgiLtn() - psgiLts) < 7200000) {
		return setTimeout(f, t * 1000);
	} else {
		return null;
	}
}
var psgiLtol = false;
function psgiLtow() {
	if (psgiLtol || (1 == 1)) {
		var pswo = "menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=680,height=600";
		var pswn = "pscw_" + psgiLtn();
		var url = "http://messenger.providesupport.com/messenger/digitemarketing.html?ps_s=" + psgiLtsid;
		if (true && !false) {
			window.open(url, pswn, pswo); 
		} else {
			var w = window.open("", pswn, pswo); 
			w.location.href = url;
			/*
			try {
				w.document.body.innerHTML += '<form id="pscf" action="http://messenger.providesupport.com/messenger/digitemarketing.html" method="post" target="' + pswn + '" style="display:none"><input type="hidden" name="ps_s" value="'+psgiLtsid+'"></form>';
				w.document.getElementById("pscf").submit();
			} catch (e) {
				w.location.href = url;
			}
			*/
		}
	} else if (1 == 2) {
		document.location = "http\u003a\u002f\u002f";
	}
}
var psgiLtil;
var psgiLtit;
function psgiLtpi() {
	var il;
	if (3 == 2) {
		il = window.pageXOffset + 50;
	} else if (3 == 3) {
		il = (window.innerWidth * 50 / 100) + window.pageXOffset;
	} else {
		il = 50;
	}
	il -= (276 / 2);
	var it;
	if (3 == 2) {
		it = window.pageYOffset + 50;
	} else if (3 == 3) {
		it = (window.innerHeight * 50 / 100) + window.pageYOffset;
	} else {
		it = 50;
	}
	it -= (189 / 2);
	if ((il != psgiLtil) || (it != psgiLtit)) {
		psgiLtil = il;
		psgiLtit = it;
		var d = document.getElementById('cigiLt');
		if (d != null) {
			d.style.left  = Math.round(psgiLtil) + "px";
			d.style.top  = Math.round(psgiLtit) + "px";
		}
	}
	setTimeout("psgiLtpi()", 100);
}
var psgiLtlc = 0;
function psgiLtsi(t) {
	window.onscroll = psgiLtpi;
	window.onresize = psgiLtpi;
	psgiLtpi();
	psgiLtlc = 0;
	var url = "https://messenger.providesupport.com/" + ((t == 2) ? "auto" : "chat") + "-invitation/digitemarketing.html?ps_s=" + psgiLtsid + "&ps_t=" + psgiLtn() + "";
	var d = document.getElementById('cigiLt');
	if (d != null) {
		d.innerHTML = '<iframe allowtransparency="true" style="background:transparent;width:276;height:189" src="' + url + 
			'" onload="psgiLtld()" frameborder="no" width="276" height="189" scrolling="no"></iframe>';
	}
}
function psgiLtld() {
	if (psgiLtlc == 1) {
		var d = document.getElementById('cigiLt');
		if (d != null) {
			d.innerHTML = "";
		}
	}
	psgiLtlc++;
}
if (false) {
	psgiLtsi(1);
}
var psgiLtd = document.getElementById('scgiLt');
if (psgiLtd != null) {
	if (psgiLtol || (1 == 1) || (1 == 2)) {
		if (false) {
			psgiLtd.innerHTML = '<table style="display:inline" cellspacing="0" cellpadding="0" border="0"><tr><td align="center"><a href="#" onclick="psgiLtow(); return false;"><img name="psgiLtimage" src="https://image.providesupport.com/image/digitemarketing/offline-2117922372.png" width="63" height="51" border="0"></a></td></tr><tr><td align="center"><a href="http://www.providesupport.com/pb/digitemarketing" target="_blank"><img src="https://image.providesupport.com/lcbps.gif" width="140" height="17" border="0"></a></td></tr></table>';
		} else {
			psgiLtd.innerHTML = '<a href="#" onclick="psgiLtow(); return false;"><img name="psgiLtimage" src="https://image.providesupport.com/image/digitemarketing/offline-2117922372.png" width="63" height="51" border="0"></a>';
		}
	} else {
		psgiLtd.innerHTML = '';
	}
}
var psgiLtop = false;
function psgiLtco() {
	var w1 = psgiLtci.width - 1;
	psgiLtol = (w1 & 1) != 0;
	psgiLtsb(psgiLtol ? "https://image.providesupport.com/image/digitemarketing/online-722418504.png" : "https://image.providesupport.com/image/digitemarketing/offline-2117922372.png");
	psgiLtscf((w1 & 2) != 0);
	var h = psgiLtci.height;

	if (h == 1) {
		psgiLtop = false;

	// manual invitation
	} else if ((h == 2) && (!psgiLtop)) {
		psgiLtop = true;
		psgiLtsi(1);
		//alert("Chat invitation in standard code");
		
	// auto-invitation
	} else if ((h == 3) && (!psgiLtop)) {
		psgiLtop = true;
		psgiLtsi(2);
		//alert("Auto invitation in standard code");
	}
}
var psgiLtci = new Image();
psgiLtci.onload = psgiLtco;
var psgiLtpm = false;
var psgiLtcp = psgiLtpm ? 30 : 60;
var psgiLtct = null;
function psgiLtscf(p) {
	if (psgiLtpm != p) {
		psgiLtpm = p;
		psgiLtcp = psgiLtpm ? 30 : 60;
		if (psgiLtct != null) {
			clearTimeout(psgiLtct);
			psgiLtct = null;
		}
		psgiLtct = psgiLtst("psgiLtrc()", psgiLtcp);
	}
}
function psgiLtrc() {
	psgiLtct = psgiLtst("psgiLtrc()", psgiLtcp);
	try {
		psgiLtci.src = "https://image.providesupport.com/cmd/digitemarketing?" + "ps_t=" + psgiLtn() + "&ps_l=" + escape(document.location) + "&ps_r=" + escape(document.referrer) + "&ps_s=" + psgiLtsid + "" + "";
	} catch(e) {
	}
}
psgiLtrc();
var psgiLtcb = "https://image.providesupport.com/image/digitemarketing/offline-2117922372.png";
function psgiLtsb(b) {
	if (psgiLtcb != b) {
		var i = document.images['psgiLtimage'];
		if (i != null) {
			i.src = b;
		}
		psgiLtcb = b;
	}
}

