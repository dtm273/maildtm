$(document).ready(function() {
    //add editor plugin
    $('textarea#wysiwg_full').tinymce({
        // Location of TinyMCE script
        script_url: base_url + '/assets/backend/lib/tiny_mce/tiny_mce.js',
        // General options
        theme: "advanced",
        plugins: "autoresize,style,table,advhr,advimage,advlink,emotions,inlinepopups,preview,media,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template,advlist",
        // Theme options
        theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
        theme_advanced_buttons2: "forecolor,backcolor,|,cut,copy,paste,pastetext,|,bullist,numlist,link,image,media,|,code,preview,fullscreen",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: false,
        font_size_style_values: "8pt,10px,12pt,14pt,18pt,24pt,36pt",
        init_instance_callback: function() {
            function resizeWidth() {
                document.getElementById(tinyMCE.activeEditor.id + '_tbl').style.width = '100%';
            }
            resizeWidth();
            $(window).resize(function() {
                resizeWidth();
            })
        }
    });

    //* colorbox single
    gebo_colorbox_single.init();
});

//* single image colorbox
gebo_colorbox_single = {
    init: function() {
        if ($('.cbox_single').length) {
            $('.cbox_single').colorbox({
                maxWidth: '80%',
                maxHeight: '80%',
                opacity: '0.2',
                fixed: true,
                rel: 'gal'
            });
        }
    }
};
