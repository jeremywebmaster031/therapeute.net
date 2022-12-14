(function ($) {

    var lpregions = false;
    var lpattern = jQuery('#page').data('lpattern');
    if (lpattern === "with_region") {
        lpregions = true;
    }


    $.fn.cityAutocomplete = function (options) {
        var autocompleteService = new google.maps.places.AutocompleteService();
        var predictionsDropDown = $('<div class="city-autocomplete"></div>').appendTo('body');
        var input = this;

        input.keyup(function () {
            var searchStr = $(this).val();

            if (searchStr.length > 0) {

                if (!lpregions) {
                    var params = {
                        input: searchStr,
                        types: ['(cities)']
                    };
                } else {
                    var params = {
                        input: searchStr,
                        types: ['(regions)']
                    };
                }


                if (input.data('country').length > 0) {
                    params.componentRestrictions = { country: input.data('country') }
                }

                autocompleteService.getPlacePredictions(params, updatePredictions);
            } else {
                predictionsDropDown.hide();
            }
        });

        predictionsDropDown.delegate('div', 'click', function () {
            //New update 2.7.0
            substrr = $(this).text();
            substrr = substrr.split(",");
            if (substrr[1] == 'undefined' || substrr[1] == '' || substrr[1] == null) {
                substrr = substrr[0];
            } else {
                substrr = substrr[0] + ',' + substrr[1];
            }
            //End New update 2.7.0
            jQuery('input[name=lp_s_loc]').val(substrr);
            jQuery('input[name=location]').val(substrr);
            input.val($(this).text());
            input.data('isseleted', 'true');
            predictionsDropDown.hide();
        });

        $(document).mouseup(function (e) {
            if (!predictionsDropDown.is(e.target) && predictionsDropDown.has(e.target).length === 0) {
                predictionsDropDown.hide();
            }
        });

        $(window).resize(function () {
            updatePredictionsDropDownDisplay(predictionsDropDown, input);
        });

        updatePredictionsDropDownDisplay(predictionsDropDown, input);

        function updatePredictions(predictions, status) {
            if (google.maps.places.PlacesServiceStatus.OK != status) {
                predictionsDropDown.hide();
                return;
            }

            predictionsDropDown.empty();
            var predcities = [];
            $.each(predictions, function (i, prediction) {
                if (!lpregions) {
                    predcities.push($.fn.cityAutocomplete.transliterate(prediction.terms[0].value));
                } else {
                    predcities.push(prediction.description);
                }
            });
            if (!lpregions) {
                predcities = predcities.filter((v, i, a) => a.indexOf(v) === i);
            }
            $.each(predcities, function (i, cities) {
                predictionsDropDown.append('<div class="help">' + cities + '</div');
            });
            predictionsDropDown.show();
        }

        return input;
    };

    $.fn.cityAutocomplete.transliterate = function (s) {
        s = String(s);

        var char_map = {
            // Latin
            '??': 'A', '??': 'A', '??': 'A', '??': 'A', '??': 'A', '??': 'A', '??': 'AE', '??': 'C',
            '??': 'E', '??': 'E', '??': 'E', '??': 'E', '??': 'I', '??': 'I', '??': 'I', '??': 'I',
            '??': 'D', '??': 'N', '??': 'O', '??': 'O', '??': 'O', '??': 'O', '??': 'O', '??': 'O',
            '??': 'O', '??': 'U', '??': 'U', '??': 'U', '??': 'U', '??': 'U', '??': 'Y', '??': 'TH',
            '??': 'ss',
            '??': 'a', '??': 'a', '??': 'a', '??': 'a', '??': 'a', '??': 'a', '??': 'ae', '??': 'c',
            '??': 'e', '??': 'e', '??': 'e', '??': 'e', '??': 'i', '??': 'i', '??': 'i', '??': 'i',
            '??': 'd', '??': 'n', '??': 'o', '??': 'o', '??': 'o', '??': 'o', '??': 'o', '??': 'o',
            '??': 'o', '??': 'u', '??': 'u', '??': 'u', '??': 'u', '??': 'u', '??': 'y', '??': 'th',
            '??': 'y',

            // Latin symbols
            '??': '(c)',

            // Greek
            '??': 'A', '??': 'B', '??': 'G', '??': 'D', '??': 'E', '??': 'Z', '??': 'H', '??': '8',
            '??': 'I', '??': 'K', '??': 'L', '??': 'M', '??': 'N', '??': '3', '??': 'O', '??': 'P',
            '??': 'R', '??': 'S', '??': 'T', '??': 'Y', '??': 'F', '??': 'X', '??': 'PS', '??': 'W',
            '??': 'A', '??': 'E', '??': 'I', '??': 'O', '??': 'Y', '??': 'H', '??': 'W', '??': 'I',
            '??': 'Y',
            '??': 'a', '??': 'b', '??': 'g', '??': 'd', '??': 'e', '??': 'z', '??': 'h', '??': '8',
            '??': 'i', '??': 'k', '??': 'l', '??': 'm', '??': 'n', '??': '3', '??': 'o', '??': 'p',
            '??': 'r', '??': 's', '??': 't', '??': 'y', '??': 'f', '??': 'x', '??': 'ps', '??': 'w',
            '??': 'a', '??': 'e', '??': 'i', '??': 'o', '??': 'y', '??': 'h', '??': 'w', '??': 's',
            '??': 'i', '??': 'y', '??': 'y', '??': 'i',

            // Turkish
            '??': 'S', '??': 'I', '??': 'C', '??': 'U', '??': 'O', '??': 'G',
            '??': 's', '??': 'i', '??': 'c', '??': 'u', '??': 'o', '??': 'g',

            // Russian
            '??': 'A', '??': 'B', '??': 'V', '??': 'G', '??': 'D', '??': 'E', '??': 'Yo', '??': 'Zh',
            '??': 'Z', '??': 'I', '??': 'J', '??': 'K', '??': 'L', '??': 'M', '??': 'N', '??': 'O',
            '??': 'P', '??': 'R', '??': 'S', '??': 'T', '??': 'U', '??': 'F', '??': 'H', '??': 'C',
            '??': 'Ch', '??': 'Sh', '??': 'Sh', '??': '', '??': 'Y', '??': '', '??': 'E', '??': 'Yu',
            '??': 'Ya',
            '??': 'a', '??': 'b', '??': 'v', '??': 'g', '??': 'd', '??': 'e', '??': 'yo', '??': 'zh',
            '??': 'z', '??': 'i', '??': 'j', '??': 'k', '??': 'l', '??': 'm', '??': 'n', '??': 'o',
            '??': 'p', '??': 'r', '??': 's', '??': 't', '??': 'u', '??': 'f', '??': 'h', '??': 'c',
            '??': 'ch', '??': 'sh', '??': 'sh', '??': '', '??': 'y', '??': '', '??': 'e', '??': 'yu',
            '??': 'ya',

            // Ukrainian
            '??': 'Ye', '??': 'I', '??': 'Yi', '??': 'G',
            '??': 'ye', '??': 'i', '??': 'yi', '??': 'g',

            // Czech
            '??': 'C', '??': 'D', '??': 'E', '??': 'N', '??': 'R', '??': 'S', '??': 'T', '??': 'U',
            '??': 'Z',
            '??': 'c', '??': 'd', '??': 'e', '??': 'n', '??': 'r', '??': 's', '??': 't', '??': 'u',
            '??': 'z',

            // Polish
            '??': 'A', '??': 'C', '??': 'e', '??': 'L', '??': 'N', '??': 'o', '??': 'S', '??': 'Z',
            '??': 'Z',
            '??': 'a', '??': 'c', '??': 'e', '??': 'l', '??': 'n', '??': 'o', '??': 's', '??': 'z',
            '??': 'z',

            // Latvian
            '??': 'A', '??': 'C', '??': 'E', '??': 'G', '??': 'i', '??': 'k', '??': 'L', '??': 'N',
            '??': 'S', '??': 'u', '??': 'Z',
            '??': 'a', '??': 'c', '??': 'e', '??': 'g', '??': 'i', '??': 'k', '??': 'l', '??': 'n',
            '??': 's', '??': 'u', '??': 'z'
        };

        for (var k in char_map) {
            s = s.replace(new RegExp(k, 'g'), char_map[k]);
        }

        return s;
    };

    function updatePredictionsDropDownDisplay(dropDown, input) {
        dropDown.css({
            'width': input.outerWidth(),
            'left': input.offset().left,
            'top': input.offset().top + input.outerHeight()
        });
    }

}(jQuery));