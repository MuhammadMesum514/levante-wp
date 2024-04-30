"use strict";

var $j = jQuery.noConflict();

$j(document).bind("gform_post_render", function (event, form_id) {
    var spfMainData = window["spfMainData_" + form_id];

    if (!spfMainData) {
        return;
    }
    var arr_el = spfMainData.elements;

    $j.each(arr_el, function (index, name) {
        var obj = name;
        var options = {
            utilsScript: spfMainData.utilsScript,
        };

        for (var i = 0; i < obj.length; i++) {
            var inputTel = obj[0],
                autoIp = obj[1],
                intCountry = obj[2],
                preCountry = obj[3],
                hiddenInput = obj[4],
                flagOption = obj[6];

            if (
                (intCountry != "none" && autoIp === false) ||
                (intCountry != "none" && autoIp === "")
            ) {
                options.initialCountry = intCountry;
            }

            if (preCountry != "none") {
                options.preferredCountries = preCountry;
            }

            if (flagOption != "flagdial") {
                options.hiddenInput = hiddenInput;
                options.autoHideDialCode = false;
                options.nationalMode = false;
            } else {
                options.separateDialCode = true;
                options.autoHideDialCode = true;
                options.nationalMode = true;
            }

            if (autoIp === true) {
                options.initialCountry = "auto";
                options.geoIpLookup = function (success, failure) {
                    $j.get("https://ipinfo.io", function () {}, "jsonp").always(
                        function (resp) {
                            var countryCode =
                                resp && resp.country ? resp.country : "";
                            success(countryCode);
                        }
                    );
                };
            }
        }

        $j(inputTel).intlTelInput(options);
    });

    /*
     *   Phone number validation
     */

    $j.each(arr_el, function (index, name) {
        var inputId = "#" + index,
            teleInput = $j(inputId),
            seInputID = "spf_" + index,
            setCountryCode = "spf_c_" + index,
            flagWidth =
                teleInput.closest("div").find(".iti__flag-container").width() +
                3,
            flagOption = name[6];

        teleInput.keyup(function () {
            var rawNumber = teleInput.intlTelInput("getNumber");
            var country = teleInput.intlTelInput("getSelectedCountryData");
            if (flagOption != "flagdial") {
                sessionStorage.setItem(seInputID, rawNumber);
                teleInput.closest("div").find("input:hidden").val(rawNumber);
            }
            sessionStorage.setItem(setCountryCode, country.iso2);
        });

        if (flagOption != "flagdial") {
            var ova = teleInput.val();
            if (!ova.length) {
                sessionStorage.removeItem(seInputID);
            } else {
                sessionStorage.setItem(seInputID, ova);
            }
            teleInput.on("change", function () {
                var nno = $j(this).val();
                if (!nno.length) {
                    sessionStorage.removeItem(seInputID);
                }
            });
            teleInput.css("padding-left", flagWidth + "px");
        }

        if (
            sessionStorage.getItem(seInputID) != undefined &&
            flagOption != "flagdial"
        ) {
            teleInput
                .closest("div")
                .find("input:hidden")
                .val(sessionStorage.getItem(seInputID));
        }

        if (sessionStorage.getItem(setCountryCode) != undefined) {
            teleInput.intlTelInput(
                "setCountry",
                sessionStorage.getItem(setCountryCode)
            );
        }

        teleInput.blur(function () {
            isInputValid($j(this));
        });

        teleInput.keydown(function () {
            hideInputValidation($j(this));
        });

        function hideInputValidation(phoneID) {
            phoneID.removeClass("error");
            phoneID
                .parent()
                .parent()
                .parent()
                .find(".valid-msg")
                .addClass("hide");
            phoneID
                .parent()
                .parent()
                .parent()
                .find(".error-msg")
                .addClass("hide");
        }

        function isInputValid(phoneID) {
            var errorMsg = phoneID
                    .parent()
                    .parent()
                    .parent()
                    .find(".error-msg"),
                validMsg = phoneID
                    .parent()
                    .parent()
                    .parent()
                    .find(".valid-msg");
            if ($j.trim(phoneID.val())) {
                if (phoneID.intlTelInput("isValidNumber")) {
                    validMsg.removeClass("hide");
                    errorMsg.addClass("hide");
                } else {
                    phoneID.addClass("error");
                    errorMsg.removeClass("hide");
                    validMsg.addClass("hide");
                }
            }
        }
    });
});

$j(document).on("gform_confirmation_loaded", function (event, formId) {
    var spfData = window["spfMainData_" + formId];
    if (!spfData) {
        return;
    }
    var arr_el = spfData.elements;

    $j.each(arr_el, function (index) {
        var getInputID = "spf_" + index,
            setCountryCode = "spf_c_" + index;
        sessionStorage.removeItem(getInputID);
        sessionStorage.removeItem(setCountryCode);
    });
});
