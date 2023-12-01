document.addEventListener("DOMContentLoaded", function () {
    var e = document.querySelectorAll(".choice-picker");
    // console.log(e);

    for (i = 0; i < e.length; ++i) {
        var a = e[i];
        var plshoder= $(a).attr("placeholder");
        new Choices(a, {
            placeholderValue: plshoder,
             classNames: {
                containerInner:"choices__inner form-select",
            },
        });
        var is_remove =$(a).data('remove_attr');
        if(is_remove)
       $(a).parents('.choices').removeAttr(is_remove)
    }

    var e = document.querySelectorAll(".choice-picker-multiple");
    // console.log(e);
    var choicesele = [];
    for (i = 0; i < e.length; ++i) {
        var a = e[i];
        $(a).find('[value=""]').prop("disabled", 1);
        var removeable = a.getAttribute("data-remove");
        // console.log(removeable);

        var placeholder = a.getAttribute("placeholder") ?? "select";
        // console.log();
        choicesele[i] = new Choices(a, {
            removeItemButton: removeable,
            // placeholder: true,
            itemSelectText: placeholder,
            classNames: {
                // placeholder: "d-none",
                containerInner:"form-select pb-0 pt-1",
                containerOuter: "choices mb-0 form-group",
            },
        });

        choicesele[i].passedElement.element.addEventListener(
            "change",
            function (event) {
                var ele = $(event.target);
                if (ele) {
                    var newArray = ele.val().filter(function (v) {
                        return v !== "";
                    });
                    if ((refEle = $("#" + ele.data("id")))) {
                        refEle.val(newArray.join(","));
                    }
                    refEle.click();
                }
            },
            false
        );
    }
    var e = document.querySelectorAll(".form-select");
    // console.log(e);

    // for (i = 0; i < e.length; ++i) {
    //     var a = e[i];
    //     $(a).find('[value=""]').prop("disabled", 1);
    // }
    // new Choices("#choices-single-no-sorting", { shouldSort: !1 });
    if ($(".choices-text-unique-values").length > 0) {
        new Choices(".choices-text-unique-values", {
            paste: !1,
            duplicateItemsAllowed: !1,
            editItems: !0,
            removeItemButton: true,
        });
    }
});

// Color Pickr

// var classicPickr = Pickr.create({
//         el: ".classic-colorpicker",
//         theme: "classic",
//         default: "#4a4fea",
//         swatches: [
//             "rgba(244, 67, 54, 1)",
//             "rgba(233, 30, 99, 0.95)",
//             "rgba(156, 39, 176, 0.9)",
//             "rgba(103, 58, 183, 0.85)",
//             "rgba(63, 81, 181, 0.8)",
//             "rgba(33, 150, 243, 0.75)",
//             "rgba(3, 169, 244, 0.7)",
//             "rgba(0, 188, 212, 0.7)",
//             "rgba(0, 150, 136, 0.75)",
//             "rgba(76, 175, 80, 0.8)",
//             "rgba(139, 195, 74, 0.85)",
//             "rgba(205, 220, 57, 0.9)",
//             "rgba(255, 235, 59, 0.95)",
//             "rgba(255, 193, 7, 1)",
//         ],
//         components: {
//             preview: !0,
//             opacity: !0,
//             hue: !0,
//             interaction: {
//                 hex: !0,
//                 rgba: !0,
//                 hsva: !0,
//                 input: !0,
//                 clear: !0,
//                 save: !0,
//             },
//         },
//     }),
//     monolithPickr = Pickr.create({
//         el: ".monolith-colorpicker",
//         theme: "monolith",
//         default: "#27bbe8",
//         swatches: [
//             "rgba(244, 67, 54, 1)",
//             "rgba(233, 30, 99, 0.95)",
//             "rgba(156, 39, 176, 0.9)",
//             "rgba(103, 58, 183, 0.85)",
//             "rgba(63, 81, 181, 0.8)",
//             "rgba(33, 150, 243, 0.75)",
//             "rgba(3, 169, 244, 0.7)",
//         ],
//         defaultRepresentation: "HEXA",
//         components: {
//             preview: !0,
//             opacity: !0,
//             hue: !0,
//             interaction: {
//                 hex: !1,
//                 rgba: !1,
//                 hsva: !1,
//                 input: !0,
//                 clear: !0,
//                 save: !0,
//             },
//         },
//     }),
//     nanoPickr = Pickr.create({
//         el: ".nano-colorpicker",
//         theme: "nano",
//         default: "#f7cc53",
//         swatches: [
//             "rgba(244, 67, 54, 1)",
//             "rgba(233, 30, 99, 0.95)",
//             "rgba(156, 39, 176, 0.9)",
//             "rgba(103, 58, 183, 0.85)",
//             "rgba(63, 81, 181, 0.8)",
//             "rgba(33, 150, 243, 0.75)",
//             "rgba(3, 169, 244, 0.7)",
//         ],
//         defaultRepresentation: "HEXA",
//         components: {
//             preview: !0,
//             opacity: !0,
//             hue: !0,
//             interaction: {
//                 hex: !1,
//                 rgba: !1,
//                 hsva: !1,
//                 input: !0,
//                 clear: !0,
//                 save: !0,
//             },
//         },
//     });
// flatpickr("#datepicker-basic", { defaultDate: new Date() }),
//     flatpickr("#datepicker-datetime", {
//         enableTime: !0,
//         dateFormat: "m-d-Y H:i",
//         defaultDate: new Date(),
//     }),
//     flatpickr("#datepicker-humanfd", {
//         altInput: !0,
//         altFormat: "F j, Y",
//         dateFormat: "Y-m-d",
//         defaultDate: new Date(),
//     }),
//     flatpickr("#datepicker-minmax", {
//         minDate: "today",
//         defaultDate: new Date(),
//         maxDate: new Date().fp_incr(14),
//     }),
//     flatpickr("#datepicker-disable", {
//         onReady: function () {
//             this.jumpToDate("2025-01");
//         },
//         disable: [
//             "2025-01-30",
//             "2025-02-21",
//             "2025-03-08",
//             new Date(2025, 4, 9),
//         ],
//         dateFormat: "Y-m-d",
//         defaultDate: new Date(),
//     }),
//     flatpickr("#datepicker-multiple", {
//         mode: "multiple",
//         dateFormat: "Y-m-d",
//         defaultDate: new Date(),
//     }),
//     flatpickr("#datepicker-range", { mode: "range", defaultDate: new Date() }),
//     flatpickr("#datepicker-timepicker", {
//         enableTime: !0,
//         noCalendar: !0,
//         dateFormat: "H:i",
//         defaultDate: new Date(),
//     }),
//     flatpickr("#datepicker-inline", { inline: !0, defaultDate: new Date() });
