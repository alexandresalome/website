if (typeof blog === "undefined"){
    blog = {};
}

blog.PostEdit = {
    bind: function () {
        $("#post-body-html-button").click(function (){
            $.post($(this).attr('href'), {
                markdown: $("#post_body").val()
            }, function (result) {
                $("#post_body").hide();
                $("#post-body-preview").html(result).show();
                $("#post-body-html-button").hide();
                $("#post-body-markdown-button").show();
            });
            return false;
        });
        $("#post-body-markdown-button").click(function (){
            $("#post_body").show();
            $("#post-body-preview").hide();
            $("#post-body-html-button").show();
            $("#post-body-markdown-button").hide();
            return false;
        });
        $("#post-body-fullscreen-button").click(function (){
            $("#form-body-edit").toggleClass("fullscreen");
            return false;
        });
    }
};
