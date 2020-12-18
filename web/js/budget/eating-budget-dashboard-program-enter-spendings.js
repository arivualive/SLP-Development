var app = {
    init: function () {
        console.log('test');
        
        $(".dropdown-list").autocomplete({
            source: ["AI","CG","DS","OS","C","C#","JAVA"]
        });
    }
}
$(app.init);