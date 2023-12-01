document.addEventListener("DOMContentLoaded", function () {
    // IMask(document.getElementById("regexp-mask"), { mask: /^[1-6]\d{0,5}$/ }),
    //     IMask(document.getElementById("phone-mask"), {
    //         mask: "+{7}(000)000-00-00",
    //     }),
    //     IMask(document.getElementById("number-mask"), {
    //         mask: Number,
    //         min: -1e4,
    //         max: 1e4,
    //         thousandsSeparator: " ",
    //     }),
    //     IMask(document.getElementById("date-mask"), {
    //         mask: Date,
    //         min: new Date(1990, 0, 1),
    //         max: new Date(2020, 0, 1),
    //         lazy: !1,
    //     }),
    //     IMask(document.getElementById("dynamic-mask"), {
    //         mask: [{ mask: "+{7}(000)000-00-00" }, { mask: /^\S*@?\S*$/ }],
    //     }),
    //     IMask(document.getElementById("currency-mask"), {
    //         mask: "$num",
    //         blocks: { num: { mask: Number, thousandsSeparator: " " } },
    //     });
    // IMask(document.getElementById("input-phone_number"), {
    //     mask: "+#(000)000-00-00",
    //     definitions: {
    //         "#": /[1-9][0-9]{1}/,
    //     },
    // });

    if ($("#price-mask").length > 0) {
        var currencyMask = IMask(document.getElementById("price-mask"), {
            mask: [
                { mask: "" },
                {
                    mask: "num",
                    lazy: false,
                    blocks: {
                        num: {
                            mask: Number,
                            scale: 2,
                            // thousandsSeparator: ",",
                            padFractionalZeros: true,
                            radix: ".",
                            mapToRadix: ["."],
                        },
                    },
                },
            ],
        });
    }
    if ($("#preparation_time").length > 0) {
        var currencyMask = IMask(document.getElementById("preparation_time"), {
            mask: [
                { mask: "" },
                {
                    mask: "num minutes",
                    lazy: false,
                    blocks: {
                        num: {
                            mask: Number,
                            scale: 0,
                            // thousandsSeparator: ",",
                            normalizeZeros: false,
                            radix: ":",
                            mapToRadix: ["."],
                            max: 150,
                        },
                    },
                },
            ],
        });
    }
});
