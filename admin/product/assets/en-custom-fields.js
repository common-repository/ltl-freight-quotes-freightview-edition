jQuery(document).ready(function () {
//          JS for edit product nested fields
    jQuery("._nestedMaterials").closest('p').addClass("_nestedMaterials_tr");
    jQuery("._nestedPercentage").closest('p').addClass("_nestedPercentage_tr");
    jQuery("._maxNestedItems").closest('p').addClass("_maxNestedItems_tr");
    jQuery("._nestedDimension").closest('p').addClass("_nestedDimension_tr");
    jQuery("._nestedStakingProperty").closest('p').addClass("_nestedStakingProperty_tr");

    if (!jQuery('._nestedMaterials').is(":checked")) {
        jQuery('._nestedPercentage_tr').hide();
        jQuery('._nestedDimension_tr').hide();
        jQuery('._maxNestedItems_tr').hide();
        jQuery('._nestedDimension_tr').hide();
        jQuery('._nestedStakingProperty_tr').hide();
    } else {
        jQuery('._nestedPercentage_tr').show();
        jQuery('._nestedDimension_tr').show();
        jQuery('._maxNestedItems_tr').show();
        jQuery('._nestedDimension_tr').show();
        jQuery('._nestedStakingProperty_tr').show();
    }
    jQuery("._nestedPercentage").keypress(function (e) {
        daylight_lfq_validation(e);

    });
    jQuery("._nestedPercentage").keyup(function (e) {
        var class_name = '._nestedPercentage';
        daylight_lfq_count(e, class_name);
    });
    jQuery("._maxNestedItems").keypress(function (e) {
        daylight_lfq_validation(e);

    });
    jQuery("._maxNestedItems").keyup(function (e) {
        var class_name = '._maxNestedItems';
        daylight_lfq_count(e, class_name);
    });

    jQuery("._nestedMaterials").change(function () {
        if (!jQuery('._nestedMaterials').is(":checked")) {
            jQuery('._nestedPercentage_tr').hide();
            jQuery('._nestedDimension_tr').hide();
            jQuery('._maxNestedItems_tr').hide();
            jQuery('._nestedDimension_tr').hide();
            jQuery('._nestedStakingProperty_tr').hide();
        } else {
            jQuery('._nestedPercentage_tr').show();
            jQuery('._nestedDimension_tr').show();
            jQuery('._maxNestedItems_tr').show();
            jQuery('._nestedDimension_tr').show();
            jQuery('._nestedStakingProperty_tr').show();
        }
    });

    function daylight_lfq_validation(e) {
        if (!String.fromCharCode(e.keyCode).match(/^[0-9\d\s]+$/i)) {
            return false;
        }
    }

    function daylight_lfq_count(e, class_name) {
        var nested_per = jQuery(class_name).val();
        if (nested_per.match(/^[1-9]\d{0,2}?$/) == null || nested_per.match(/^[1-9][0-9]?$|^100$/) == null) {
            var previouse_val = nested_per.slice(0, -1);
            if (previouse_val > 100) {
                previouse_val = previouse_val.slice(0, -1);
            }
            jQuery(class_name).val(previouse_val);
            return false;
        }
    }

})