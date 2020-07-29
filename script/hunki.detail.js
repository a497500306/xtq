/*!
 * hunki大淘客CMS淘口令
 * 版本 v5.0.7
 */

var taoKeyNum = "";

$(function () {


    $(".img .ui-link").unbind("click");

    $(".img .ui-link").on("click", function() {
        var _url = $(this).attr('href') ? $(this).attr('href') : $(this).data('href');
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {

            openTips();
            $(".kl-tkl2").html($("#hunkiTkl").val());
            $(".kl-tkl2").val($("#hunkiTkl").val());
            event.preventDefault()
        } else {
            window.location.href = _url;
            return false;
        }
    });


});


$(".detail-mask").on("click", function() {
    $(".dialog").fadeOut(100, function() {
        $(".detail-mask-command-ios, .detail-mask-command-android").hide();
    });
});

document.addEventListener("selectionchange", function(e) {
    if (window.getSelection().anchorNode.parentNode.id == 'code1_ios' && document.getElementById('code1_ios').innerText != window.getSelection()) {
        var key = document.getElementById('code1_ios');
        window.getSelection().selectAllChildren(key);
    }
    if (window.getSelection().anchorNode.parentNode.id == 'code2_ios' && document.getElementById('code2_ios').innerText != window.getSelection()) {
        var key = document.getElementById('code2_ios');
        window.getSelection().selectAllChildren(key);
    }
}, false);

//判断安卓苹果显示
if (isIOS()) {
    $(".detail-command-box" + " textarea").hide();
    $(".detail-command-box" + " span").show();

} else {
    $(".detail-command-box" + " span").hide();
    $(".detail-command-box" + " textarea").show();

}

//打开提示框
function openTips() {
    if (isIOS()) {
        $(".detail-mask-command-ios").show();
        $(".detail-mask-command-android").hide();
        $(".detail-mask-command-box" + " textarea").hide();
        $(".detail-mask-command-box" + " span").show();
    } else {
        $(".detail-mask-command-android").show();
        $(".detail-mask-command-ios").hide();
        $(".detail-mask-command-box" + " span").hide();
        $(".detail-mask-command-box" + " textarea").show();
    }
    $(".dialog").fadeIn(100);
    //判断打开淘口令时  页面不滚动
    document.body.addEventListener('touchmove', function(event) {
        if ($(".dialog").css("display") == "block") {
            event.preventDefault();
        }
    }, false);
}

//文字输入
function iptNum(ths, sta) {
    if (sta) {
        taoKeyNum = ths.value;
    }
    if (ths.value != taoKeyNum) {
        ths.value = taoKeyNum;
    }
}

//判断是安卓还是苹果
function isIOS() {
    var ua = navigator.userAgent.toLowerCase();
    if (/iphone|ipad|ipod/.test(ua)) {
        return true;
    } else if (/android/.test(ua)) {
        return false;
    }
}