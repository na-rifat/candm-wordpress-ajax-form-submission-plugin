var current_guest = 0;
(function ($) {
    $(document).ready(function () {
        $(`.candm-rsvp-item`).eq(1).hide();
        $(`.sub-group`).hide();

        $(`#form_step_1`).on(`click`, function (e) {
            $(`.candm-rsvp-item`).eq(0).hide(500);
            $(`.candm-rsvp-item`).eq(1).show(500);
        });

        $(`#previous`).on(`click`, function (e) {
            $(`.candm-rsvp-item`).eq(1).hide(500);
            $(`.candm-rsvp-item`).eq(0).show(500);
        });

        $(`#like_too_add_more_guest`).on(`click`, function (e) {
            if ($(this).prop(`checked`)) {
                $(`.sub-group`).eq(0).show(500);
            } else {
                $(`.sub-group`).eq(0).hide(500);
            }
        });

        $(`#help_accommodation_dubrovnik_yes`).on(`click`, function (e) {
            if ($(this).prop(`checked`)) {
                $(`.sub-group`).eq(1).show(500);
                $(`#help_accommodation_dubrovnik_no`).prop(`checked`, false);
            } else {
                $(`.sub-group`).eq(1).hide(500);
                $(`#help_accommodation_dubrovnik_no`).prop(`checked`, true);
            }
        });

        $(`#help_accommodation_dubrovnik_no`).on(`click`, function (e) {
            if ($(this).prop(`checked`)) {
                $(`.sub-group`).eq(1).hide(500);
                $(`#help_accommodation_dubrovnik_yes`).prop(`checked`, false);
            } else {
                $(`.sub-group`).eq(1).show(500);
                $(`#help_accommodation_dubrovnik_yes`).prop(`checked`, true);
            }
        });

        $(`#attending_pre_wedding_day_yes`).on(`click`, function (e) {
            if ($(this).prop(`checked`)) {
                $(`#attending_pre_wedding_day_no`).prop(`checked`, false);
            } else {
                $(`#attending_pre_wedding_day_no`).prop(`checked`, true);
            }
        });

        $(`#attending_pre_wedding_day_no`).on(`click`, function (e) {
            if ($(this).prop(`checked`)) {
                $(`#attending_pre_wedding_day_yes`).prop(`checked`, false);
            } else {
                $(`#attending_pre_wedding_day_yes`).prop(`checked`, true);
            }
        });

        //  Rose background
        $(`.rose-image img`).after(`<div class="rose-bg"></div>`);
        let roseImg = $(`.rose-bg`).siblings(`img`);
        $(`.rose-bg`).css({
            height: roseImg.height(),
            width: roseImg.width(),
        });

        // Form submission
        $(`.schema-form`).on(`submit`, function (e) {
            e.preventDefault();

            if (
                $(`#like_too_add_more_guest`).prop(`checked`) == true &&
                $(`.guest-list tr`).length <= 1
            ) {
                alert(`Please add guest to the list.`);
                return;
            }

            $(`.submission-loader`).addClass(`is-active`);

            let data = $(this).serialize();

            $.ajax({
                type: "post",
                url: candm.ajax_url,
                data: data + "&action=rsvp_submission",
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        alert(
                            `Your RSVP submission has been successfully added.`
                        );
                        location.href = candm.home_url;
                    } else {
                        alert(res.data.msg);
                    }
                },
                error: function (res) {
                    alert(`Wrong!`);
                },
                complete: function () {
                    $(`.submission-loader`).removeClass(`is-active`);
                },
            });
        });

        $(`.submit-button`).on(`click`, function (e) {
            $(`.schema-form`).trigger(`submit`);
        });

        $(`.remove-guest`).on(`click`, function (e) {
            $(`.guest-list tbody tr`).eq(current_guest).remove();
            $(`#guest_first_name`).val(``);
            $(`#guest_last_name`).val(``);
            $(`#guestdinner_meal_preference`).val(``);
        });

        addGuest();
    });
})(jQuery);

function addGuest() {
    let $ = jQuery;

    $(`.add-guest`).on(`click`, function (e) {
        let table = $(`.guest-list table tbody`);
        let firstName = $(`#guest_first_name`).val();
        let lastName = $(`#guest_last_name`).val();
        let dinnerMeal = $(`#guestdinner_meal_preference`).val();

        if (empty(firstName) || empty(lastName) || empty(dinnerMeal)) {
            return;
        }

        table.append(
            `<tr>
                <td>${firstName}<input type="hidden" name="guest_first_name[]" value="${firstName}" /></td>
                <td>${lastName}<input type="hidden" name="guest_last_name[]" value="${lastName}" /></td>
                <td>${dinnerMeal}<input type="hidden" name="guestdinner_meal_preference[]" value="${dinnerMeal}" /></td>
                
            </tr>`
        );

        $(`#guest_first_name`).val(``);
        $(`#guest_last_name`).val(``);
        $(`#guestdinner_meal_preference`).val(``);
        removeGuest();
    });
}

/**
 *
 * Checks if a variable is empty
 *
 * @param {*} value
 * @returns
 */
function empty(value) {
    if (value == undefined || value == null || value.length == 0) {
        return true;
    }
    return false;
}

function removeGuest() {
    let $ = jQuery;

    $(`.guest-list tbody tr`)
        .unbind(`click`)
        .on(`click`, function (e) {
            $(`#guest_first_name`).val($(this).find(`td`).eq(0).text());
            $(`#guest_last_name`).val($(this).find(`td`).eq(1).text());
            $(`#guestdinner_meal_preference`).val(
                $(this).find(`td`).eq(2).text()
            );

            current_guest = $(this).index();
        });
}
