window.onload = function () {
    var validEle = document.getElementById("pristine-valid");

    if (validEle != undefined) {
        var t = new Pristine(validEle);

        validEle.addEventListener("submit", function (e) {
            if (!t.validate()) {
                e.preventDefault();
                $('.has-danger:first .form-control').focus();
            }else{
                $(document).find('body').css('pointer-events','none');
                $(document).find('body').css('overflow','hidden');
                $(document).find('.pace').toggleClass('pace-active pace-inactive');
                
             
            }
        });
    }
    var ePassword = document.getElementById("pristine-password-valid");
    if (ePassword) {
        var pristine = new Pristine(ePassword);

        // PError = ePassword.getAttribute("data-pristine-password-message")
        //     ? ePassword.getAttribute("data-pristine-password-message")
        //     : "Your password must be at least 8 characters, one upper case letter, one digit, one special character, and one lower case letter.";
        PError = "Your password must be at least 8 characters and one special character.";

        pristine.addValidator(
            ePassword,
            function (value) {
                if (
                    value.length < 8 ||
                    value.search(/[a-z]/) == -1 ||
                    value.search(/[+=!@#$%^&*]/) == -1
                )
                    return false;
                else return true;
            },
            PError,
            2,
            1
        )
    }
    var ephone = document.getElementById("pristine-phone-valid");
    // console.log(ephone);
    if (ephone) {
        var pristine = new Pristine(ephone);

        PError = ephone.getAttribute("data-pristine-phone-message")
            ? ephone.getAttribute("data-pristine-phone-message")
            : "Please enter a valid phone number";

        var regex = new RegExp(/^[+]{0,1}[1-9]{0,1}[0-9 -\(\)]{7,15}$/);
        pristine.addValidator(
            ephone,
            function (value) {
                if (value == "") {
                    return true;
                } else if (regex.test(value)) {
                    return true;
                } else return false;
            },
            PError,
            2,
            1
        );
    }

    var eCPassword = document.getElementById(
        "pristine-password_confirmation-valid"
    );

    if (eCPassword) {
        var pristine = new Pristine(eCPassword);

        let CPError = eCPassword.getAttribute(
            "data-pristine-password_confirmation-message"
        )
            ? eCPassword.getAttribute(
                  "data-pristine-password_confirmation-message"
              )
            : "Your password and confirmation password are not equal.";
        pristine.addValidator(
            eCPassword,
            function (value) {
                var passwordID = eCPassword.getAttribute(
                    "data-pristine-password-metch"
                );
                var password = document.getElementById(passwordID);
                if (password === undefined) {
                    return false;
                } else if (password.value != value) {
                    return false;
                }
                return true;
            },
            CPError,
            2,
            1
        );
    }

    var InValidte = document.getElementsByClassName("pristine-in-validators");
    for (i = 0; i < InValidte.length; ++i) {
        var ins = InValidte[i].getAttribute("pristine-value-in");
        if (ins) {
            var pristine = new Pristine(InValidte[i]);
            // var that = InValidte[i];

            PError = InValidte[i].getAttribute("data-pristine-value-in-message")
                ? InValidte[i].getAttribute("data-pristine-value-in-message")
                : "Please select valide data.";
            console.log(InValidte[i]);
            pristine.addValidator(
                InValidte[i],
                function (value) {
                    var that = $(this);
                    var validValArray = that
                        .attr("pristine-value-in")
                        .split(",");
                    var value = value.split(",");
                    var diff = validValArray.filter((element) =>
                        value.includes(element)
                    );
                    if (value.length == 0) {
                        return false;
                    } else if (value.length == 0) {
                        return false;
                    }
                    return true;

                },
                PError,
                2,
                1
            );
        }
    }
 var InValidte = document.getElementsByClassName("pristine-custom-pattern");
    for (i = 0; i < InValidte.length; ++i) {
        var ins = InValidte[i].getAttribute("custom-pattern");
        if (ins) {
            var pristine = new Pristine(InValidte[i]);
            // var that = InValidte[i];

            PError = InValidte[i].getAttribute("data-pristine-pattern-message")
                ? InValidte[i].getAttribute("data-pristine-pattern-message")
                : "Please enter a valid data.";
            // console.log(InValidte[i]);
            pristine.addValidator(
                InValidte[i],
                function (value) {
                    var that = $(this);
                    var validValArray = that
                        .attr("custom-pattern");
               
                 if(that.attr('name') == "app_smtp_username"){
                     var regex = new RegExp(validValArray, 'g');
                     let result = regex.exec(value);
                         if(result == null){
                                return false
                            }
                            return true;
                 }else{
                    var regex = new RegExp(validValArray, '');
                    // console.log(regex,value, regex.exec(value));
                    if ((m = regex.exec(value)) !== null) {
                             return true;
                        }
                         return false;
                 }
                // console.log(result);
               
                },
                PError,
                2,
                1
            );
        }
    }


    var metchs = document.querySelectorAll("[data-in-metch]");

        if (metchs) {
            var o = -1;
            metchs.forEach(function(d) {
                o++;

                pristine = new Pristine(d);
            var required =d.getAttribute("data-in-metch")
                PError = d.getAttribute("data-pristine-metch-message") ?
                    d.getAttribute("data-pristine-metch-message") :
                    "Your field must contain the  "+required + " string.";

                pristine.addValidator(
                    d,
                    function(value) {
                        var that = $(this);
                        var validValArray = that
                            .attr("data-in-metch")
                            .split(",");
                        var counter = 0;
                        validValArray.forEach(function(q){
                            if(value.match(q)){
                                counter++;
                            }
                           

                        })
                        if(counter == validValArray.length){
                            return true;
                        }
                        return false;
                    },
                    PError,
                    2,
                    1
                );
            })
        }
};
