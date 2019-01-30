/**
 *  Added New Validation Methods
 */
if(jQuery.validator){
    jQuery.validator.addMethod("trim", function(value, element) {
        return true;
    }, "");

    jQuery.validator.addMethod("equalto", function(value, element, params) {
        return value == $(params).val();
    }, "Mismatch  ");

    jQuery.validator.addMethod("candidate_email_unique", function(value, element, params) {
        var href =   $(location).attr('href');
        var job_id = href.substr(href.lastIndexOf('/') + 1);
        var isValid = false;
        $.ajax( {
            url : baseURL + "validate_unique?candidates%5Bemail%5D=" + value + "&candidates%5Bjob_id%5D=" + job_id,
            async: false,
            success : function( data ) {
                if(data.trim() != '"Already Exists"') {
                    isValid = true;
                }

            }
        });
        console.log(isValid);
        return isValid;
    }, "Email address already assigned");
}

/**
 * Handle Form Validation.
 *
 */

var form = $(".validateForm");
form.each(function(){
    var validationRules = {};
    var field = '';
    $(this).find(':input').each(function(){
        field = $(this).attr('name');
        if(typeof field !== 'undefined')
            validationRules[field]= $(this).data();
    });
    var error = $('.alert-error', $(this));
    var success = $('.alert-success', $(this));
    $(this).validate({
        rules: validationRules,
        ignore: ".ignore",
        errorClass : 'help-inline error-msg',
        errorElement: 'span',
        errorPlacement: function (error, element) { // render error placement for each input type

            var type = $(element).prop('type');
            var id = $(element).prop('id');
            if(type == 'checkbox' || type == 'radio'){
                error.insertAfter(element.closest('.'+type));
                error.css('float','none');
            } else if(id == 'industries' || id =='profession'){
                error.insertAfter(element.next());
            }else {
                error.insertAfter(element); // for other inputs, just perform default behavoir
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            success.hide();
            error.show();
        },

        highlight: function (element) { // hightlight error inputs
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },

        unhighlight: function (element) { // revert the change dony by hightlight
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set error class to the control group
        },

        /*success: function (label) {
            /!*if (label.attr("for") == "service" || label.attr("for") == "membership") { // for checkboxes and radip buttons, no need to show OK icon
                label
                    .closest('.control-group').removeClass('error').addClass('success');
                label.remove(); // remove error label here
            } else { // display success icon for other inputs
                label
                    .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
                    .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group
            }*!/
        }*/


    });
});


/**
 * Trigger form submit on modal footer submit button click
 */

$('body').on('click','.modal-footer button:submit',function(){
   $($(this).data('form')).submit();
});


function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#profile-pic-preview').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#select_profile_pic").change(function(){
    readURL(this);
});

$(document).ready(function() {

    $("[rel='tooltip']").tooltip();

    $('[data-toggle="popover"]').popover();

    var dataTable = $('.dataTables');
    if(dataTable.DataTable){
        dataTable.DataTable({
            responsive: true
        });
    }

    var body =  $('body');
    /**
     * Make all anchor tags
     */
    body.on('click','a.ajax', function(e) {
        e.preventDefault();
        var element = $(this);
        element.button('loading');
        $.getJSON(element.attr('href'),function (result) {
            if(!window[element.data('success')](result, element))
                element.button('reset');
        });
        return false;
    });

    body.on('submit','form.ajax', function (e) {
        e.preventDefault();
        var element = $(this);
        var data = element.serialize();  // form data
        element[0].reset();              // reset form
        $.ajax({
            url:  element.attr('action'),
            data: data,
            method : element.attr('method'),
            dataType : 'json',
            success : function (result) {
                window[element.data('success')](result, element);
            }
        });
        return false;
    });

    body.on('click', '.modal-footer button:submit', function(){
        $(this).closest('.modal').find('form').submit();
    });


    /*body.on('click','#delete', function () {
        var flag= 0;
        $('input').each(function () {
            $(this).on('ifChecked', function(event){
                flag++;
            });
        });
        alert(flag);
    });*/

});


function change_status(result, element) {
    var addClass = (result) ? 'label-success' : 'label-default';
    var removeClass = (result) ? 'label-default' : 'label-success';
    var text = (result) ? 'Active' : 'Inactive';
    element.removeClass(removeClass).addClass(addClass).text(text);
    return true;
}


$(window).load(function(){
    $(".done").click(function(){
        var this_li_ind = $(this).parent().parent("li").index();
        if($('.payment-wizard li').hasClass("jump-here")){
            $(this).parent().parent("li").removeClass("active").addClass("completed");
            $(this).parent(".wizard-content").slideUp();
            $('.payment-wizard li.jump-here').removeClass("jump-here");
        }else{
            $(this).parent().parent("li").removeClass("active").addClass("completed");
            $(this).parent(".wizard-content").slideUp();
            $(this).parent().parent("li").next("li:not('.completed')").addClass('active').children('.wizard-content').slideDown();
        }
    });

    $('.payment-wizard li .wizard-heading').click(function(){
        if($(this).parent().hasClass('completed')){
            var this_li_ind = $(this).parent("li").index();
            var li_ind = $('.payment-wizard li.active').index();
            if(this_li_ind < li_ind){
                $('.payment-wizard li.active').addClass("jump-here");
            }
            $(this).parent().addClass('active').removeClass('completed');
            $(this).siblings('.wizard-content').slideDown();
        }
    });
})
