// document
//     .getElementById("password-addon")
//     .addEventListener("click", function () {
//         var e = document.getElementById("password-input");
//         "password" === e.type ? (e.type = "text") : (e.type = "password");
//     });
$(document).ready(function () {
    $(document).on("click", "#password-addon", function () {
        var passEle = $(this).parent("div").find("input");
        var type = passEle.attr("type");
        passEle.attr(
            "type",
            "password" === type ? (type = "text") : (type = "password")
        );
    });

    $(document).on("click", "#password-confirmation-addon", function () {
        var passEle = $(this).parent("div").find("input");
        var type = passEle.attr("type");
        passEle.attr(
            "type",
            "password" === type ? (type = "text") : (type = "password")
        );
    });
});
