if (typeof identity === "undefined"){
    identity = {};
}

identity.CV = {
    bind: function ()
    {
        $(".page-main-cv dd").hide();
        $(".page-main-cv dt").css("cursor", "pointer");

        var currentDefinition = null;
        $(".page-main-cv dt").click(function () {
            var title = $(this);
            var definition = title.next();

            if (currentDefinition) {
                currentDefinition.slideUp();
                currentDefinition.prev().removeClass("active");
            }
            if (definition.is(currentDefinition)) {
                currentDefinition = null;
                return;
            }
            definition.slideDown();
            title.addClass("active");
            currentDefinition = definition;
        });
    }
};
