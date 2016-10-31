var cookieScripts = document.getElementsByTagName("script"),
    cookieScriptSrc = cookieScripts[cookieScripts.length - 1].src,
    cookieQuery = null,
    cookieScriptPosition = "bottom",
    cookieScriptSource = "",
    cookieScriptDomain = "",
    // cookieScriptReadMore = "/policy.html",
    // cookieId = "eb80a3482fa0f23d82a3cfc197452481",
    cookieScriptDebug = 0,
    cookieScriptCurrentUrl = window.location.href,
    cookieScriptTitle = "",
    cookieScriptDesc = "<a rel=\"license\" href=\"http://creativecommons.org/licenses/by-nc-sa/4.0/deed.hu\" target=\"_blank\"><img alt=\"Creative Commons Licenc\" style=\"border-width:0\" src=\"https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png\" /><br /></a>A weboldal cookie-kat haszn\u00e1l a szolg\u00e1ltat\u00e1sok min\u0151s\u00e9g\u00e9nek jav\u00edt\u00e1s\u00e1ra. A weboldal tov\u00e1bbi haszn\u00e1lat\u00e1val j\u00f3v\u00e1hagyja a cookie-k haszn\u00e1lat\u00e1t. ",
    cookieScriptAccept = "Elfogadom",
    cookieScriptMore = "Tov\u00e1bbi inform\u00e1ci\u00f3",
    cookieScriptCopyrights = "Elfogadom",
    cookieScriptLoadJavaScript = function(f, d) {
        var c = document.getElementsByTagName("head")[0],
            a = document.createElement("script");
        a.type = "text/javascript";
        a.src = f;
        void 0 != d && (a.onload = a.onreadystatechange = function() {
            a.readyState && !/loaded|complete/.test(a.readyState) || (a.onload = a.onreadystatechange = null, c && a.parentNode && c.removeChild(a), a = void 0, d())
        });
        c.insertBefore(a, c.firstChild)
    },
    InjectCookieScript =
    function() {
        function f() {
            cookieQuery('iframe[data-cookiescript="accepted"]').not(":has([src])").each(function() {
                var a = this,
                    a = a.contentWindow ? a.contentWindow : a.contentDocument.document ? a.contentDocument.document : a.contentDocument;
                a.document.open();
                a.document.write(cookieQuery(this).attr("alt"));
                a.document.close()
            })
        }

        function d(a) {
            "show" == a ? (cookieQuery("#cookiescript_overlay", cookieScriptWindow).show(), cookieQuery("#cookiescript_info_box", cookieScriptWindow).show()) : "hide" == a && (cookieQuery("#cookiescript_overlay",
                cookieScriptWindow).hide(), cookieQuery("#cookiescript_info_box", cookieScriptWindow).hide())
        }

        function c() {
            cookieQuery('img[data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).attr("src", cookieQuery(this).attr("data-src"))
            });
            cookieQuery('script[type="text/plain"][data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).attr("src") ? cookieQuery(this).after('<script type="text/javascript" src="' + cookieQuery(this).attr("src") + '">\x3c/script>') : cookieQuery(this).after('<script type="text/javascript">' +
                    cookieQuery(this).html() + "\x3c/script>");
                cookieQuery(this).empty()
            });
            cookieQuery('iframe[data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).attr("src", cookieQuery(this).attr("data-src"))
            });
            cookieQuery('embed[data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).replaceWith(cookieQuery(this).attr("src", cookieQuery(this).attr("data-src"))[0].outerHTML)
            });
            cookieQuery('object[data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).replaceWith(cookieQuery(this).attr("data",
                    cookieQuery(this).attr("data-data"))[0].outerHTML)
            });
            cookieQuery('link[data-cookiescript="accepted"]').each(function() {
                cookieQuery(this).attr("href", cookieQuery(this).attr("data-href"))
            })
        }
        cookieScriptDropfromFlag = 0;
        cookieScriptDroptoFlag = 0;
        cookieScriptCreateCookie = function(a, g, b) {
            var e = "https:" == window.location.protocol ? ";secure" : "",
                d = "",
                c;
            b && (c = new Date, c.setTime(c.getTime() + 864E5 * b), d = "; expires=" + c.toGMTString());
            b = "";
            "" != cookieScriptDomain && (b = "; domain=" + cookieScriptDomain);
            document.cookie = a + "=" + g + d + b + "; path=/" + e
        };
        cookieScriptReadCookie = function(a) {
            a += "=";
            for (var c = document.cookie.split(";"), b, e = 0; e < c.length; e++) {
                for (b = c[e];
                    " " == b.charAt(0);) b = b.substring(1, b.length);
                if (0 ==
                    b.indexOf(a)) return b.substring(a.length, b.length)
            }
            return null
        };
        cookieQuery(function() {
            cookieScriptWindow = window.document;
            cookieQuery("#cookiescript_injected", cookieScriptWindow).remove();
            cookieQuery("#cookiescript_overlay", cookieScriptWindow).remove();
            cookieQuery("#cookiescript_info_box", cookieScriptWindow).remove();
            cookieScriptCurrentValue = cookieScriptReadCookie("cookiescriptaccept");
            if ("visit" == cookieScriptCurrentValue) return !1;
            cookieQuery("body", cookieScriptWindow).append('<div id="cookiescript_injected"><div id="cookiescript_wrapper">' +
                cookieScriptDesc + '&nbsp;&nbsp;<a id="cookiescript_readmore" class="btn btn-xs btn-link">' + cookieScriptMore + '</a><div id="cookiescript_accept" class="btn btn-success btn-xs">' + cookieScriptAccept + '</div><div id="cookiescript_pixel"></div></div>');
            cookieQuery("#cookiescript_injected", cookieScriptWindow).css({
                "background-color": "#f5f5f5",
                "z-index": 999999,
                position: "absolute",
                padding: "10px 0",
                width: "100%",
                left: 0,
                "font-size": "13px",
                "font-weight": "normal",
                "text-align": "left",
                "letter-spacing": "normal",
                color: "#333",
                display: "none"
            });
            cookieQuery("#cookiescript_buttons", cookieScriptWindow).css({
                width: "200px",
                margin: "0 auto",
                "font-size": "13px",
                "font-weight": "normal",
                "text-align": "center",
                color: "#333",
            });
            cookieQuery("#cookiescript_wrapper", cookieScriptWindow).css({
                margin: "0 10px",
                "font-size": "13px",
                "font-weight": "normal",
                "text-align": "center",
                color: "#333",
                "line-height": "23px",
                "letter-spacing": "normal"
            });
            "top" == cookieScriptPosition ? cookieQuery("#cookiescript_injected", cookieScriptWindow).css("top", 0) : cookieQuery("#cookiescript_injected", cookieScriptWindow).css("bottom", 0);
            cookieQuery("#cookiescript_injected h4#cookiescript_header", cookieScriptWindow).css({
                "background-color": "#111111",
                "z-index": 999999,
                padding: "0 0 7px 0",
                "text-align": "center",
                color: "#333",
                "font-size": "15px",
                "font-weight": "bold",
                margin: "0"
            });
            cookieQuery("#cookiescript_injected span", cookieScriptWindow).css({
                display: "block",
                "font-size": "100%",
                margin: "5px 0"
            });
            cookieQuery("#cookiescript_injected a", cookieScriptWindow).css({
                "text-decoration": "underline",
                color: "#333"
            });
            cookieQuery("#cookiescript_injected a#cookiescript_link", cookieScriptWindow).css({
                "text-decoration": "none",
                color: "#333",
                "font-size": "85%",
                "text-decoration": "none",
                "float": "right",
                padding: "0px 20px 0 0",
                "letter-spacing": "normal",
                "font-weight": "normal"
            });
            cookieQuery("#cookiescript_injected div#cookiescript_accept", cookieScriptWindow).css({
                // "-webkit-border-radius": "5px",
                // "-khtml-border-radius": "5px",
                // "-moz-border-radius": "5px",
                // "border-radius": "5px",
                // "background-color": "#5BB75B",
                // border: 0,
                // padding: "6px 10px",
                // "font-weight": "bold",
                // cursor: "pointer",
                // margin: "0 10px 0 30px",
                // color: "#333",
                // "-webkit-transition": "0.25s",
                // "-moz-transition": "0.25s",
                // transition: "0.25s",
                // display: "inline",
                // "text-shadow": "rgb(0, 0, 0) 0px 0px 2px",
                // "white-space": "nowrap"
            });
            cookieQuery("#cookiescript_injected #cookiescript_readmore", cookieScriptWindow).css({
                // cursor: "pointer",
                // "text-decoration": "underline",
                // padding: 0,
                // margin: 0,
                // color: "#333",
                // "white-space": "nowrap"
            });
            cookieQuery("#cookiescript_injected div#cookiescript_pixel", cookieScriptWindow).css({
                width: "1px",
                height: "1px",
                "float": "left"
            });
            window._gaq ? _gaq.push(["_trackEvent", "Cookie-Script", "Show", {
                nonInteraction: 1
            }]) : window.ga && ga("send", "event", "Cookie-Script",
                "Show", {
                    nonInteraction: 1
                });
            cookieQuery("#cookiescript_injected div#cookiescript_accept", cookieScriptWindow).hover(function() {
                // cookieQuery(this).css("background-color", "#5BB75B")
            }, function() {
                // cookieQuery(this).css("background-color", "#5BB75B")
            });
            cookieQuery("#cookiescript_injected", cookieScriptWindow).fadeIn(1E3);
            cookieQuery("#cookiescript_injected div#cookiescript_accept", cookieScriptWindow).click(function() {
                window._gaq ? _gaq.push(["_trackEvent", "Cookie-Script", "Accept", {
                        nonInteraction: 1
                    }]) : window.ga &&
                    ga("send", "event", "Cookie-Script", "Accept", {
                        nonInteraction: 1
                    });
                "undefined" === typeof cookieScriptScrollfired && (cookieQuery("#cookiescript_injected", cookieScriptWindow).fadeOut(200), cookieScriptCreateCookie("cookiescriptaccept", "visit", 30), d("hide"), c())
            });
            cookieQuery("#cookiescript_injected #cookiescript_readmore", cookieScriptWindow).click(function() {
                window._gaq ? _gaq.push(["_trackEvent", "Cookie-Script", "Read more", {
                    nonInteraction: 1
                }]) : window.ga && ga("send", "event", "Cookie-Script", "Read more", {
                    nonInteraction: 1
                });
                // Show modal
                $('#cookie').modal({
                    show: 'true'
                }); 
                // window.open(cookieScriptReadMore, "_blank");
                return !1
            });
            cookieQuery("#cookiescript_overlay", cookieScriptWindow).click(function() {
                d("hide")
            });
            cookieQuery("#cookiescript_info_close", cookieScriptWindow).click(function() {
                d("hide")
            });
            document.onkeydown = function(a) {
                a = a || window.event;
                27 == a.keyCode && d("hide")
            };
            f()
        });
        cookieScriptCreateCookie = function(a, c, b) {
            var e = "",
                d;
            b && (d = new Date, d.setTime(d.getTime() + 864E5 * b), e = "; expires=" + d.toGMTString());
            b = "";
            "" != cookieScriptDomain && (b = "; domain=" + cookieScriptDomain);
            document.cookie = a + "=" + c + e + b + "; path=/"
        };
        cookieScriptReadCookie = function(a) {
            a += "=";
            for (var c = document.cookie.split(";"), b, d = 0; d < c.length; d++) {
                for (b = c[d];
                    " " == b.charAt(0);) b = b.substring(1, b.length);
                if (0 == b.indexOf(a)) return b.substring(a.length, b.length)
            }
            return null
        };
        "visit" != cookieScriptReadCookie("cookiescriptaccept") && "shown" != cookieScriptReadCookie("cookiescriptaccept") || c()
    };
window.jQuery && jQuery.fn && /^(1\.[8-9]|2\.[0-9])/.test(jQuery.fn.jquery) ? (cookieScriptDebug && window.console && console.log("Using existing jQuery version " + jQuery.fn.jquery), cookieQuery = window.jQuery, InjectCookieScript()) : (cookieScriptDebug && window.console && console.log("Loading jQuery 1.8.1 from ajax.googleapis.com"), cookieScriptLoadJavaScript(("https:" == document.location.protocol ? "https://" : "http://") + "ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js", function() {
    cookieQuery = jQuery.noConflict(!0);
    InjectCookieScript()
}));