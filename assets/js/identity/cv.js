if (typeof identity === "undefined"){
    identity = {};
}

/**
 * Javascript library for CV page
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
identity.cv = {
    /**
     * Bind events to the CV page
     */
    bind: function ()
    {
        $(".page-main-cv dl.accordion").each(identity.cv.bindList);
    },

    /**
     * Bind events to a given definition list
     *
     * @param Integer    number  The current cursor(jQuery requirement)
     * @param DOMElement element The DOM element to bind
     */
    bindList: function (number, element)
    {
        var current = null;
        var titles = $(element).find("dt");

        titles.css("cursor", "pointer");
        titles.each(function (i, e) {
            var element = $(e);
            element.html('<span class="more">+</span> ' + $(e).html());
            element.next().hide();
        });
        titles.click(function () {
            var title = $(this);
            var definition = title.next();

            if (current) {
                current.slideUp();
                var currentTitle = current.prev();
                currentTitle.removeClass("active");
                currentTitle.find(".more").text("+");
            }
            if (definition.is(current)) {
                current = null;
                return;
            }
            definition.slideDown();
            title.addClass("active");
            title.find(".more").text("");
            current = definition;
        });
    }
};
