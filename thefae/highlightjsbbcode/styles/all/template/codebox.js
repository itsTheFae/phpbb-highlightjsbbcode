hljs.initHighlightingOnLoad();

$( document ).ready(function() {
    $(".codehlb_toggle").click(function(){
        var ltext = $(this).text();
        var cbox = $(this).parent().parent();
        var codeArea = cbox.children("pre");
        codeArea.toggle();
        if (ltext.toLowerCase() == '[show]') {
            $(this).text('[hide]');
        } else {
            $(this).text('[show]');
        }
    });
});