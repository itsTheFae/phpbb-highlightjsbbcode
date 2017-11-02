hljs.initHighlightingOnLoad();

$( document ).ready(function() {
    $(".codehlb_toggle").click(function(){
        var ltext = $(this).text();
        var cbox = $(this).parent().parent();
        var codeArea = cbox.children("pre");
        codeArea.toggle();
        if( ltext.toLowerCase() == hljsLangBtnShow.toLowerCase() ) {
            $(this).text( hljsLangBtnHide );
        } else {
            $(this).text( hljsLangBtnShow );
        }
    });
});