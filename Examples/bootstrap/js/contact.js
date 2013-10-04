$(document).ready(function () {
    $('input').focus(function () {
        $(this).removeClass('error');
    });
    $('textarea').focus(function () {
        $(this).removeClass('error');
    });
    $('#contactbtn').click(function () {
        var valid = true;
        var name = $('input[name=name]');
        validate(name);
        var email = $('input[name=email]');
        validate(email);
        var message = $('textarea[name=message]');
        validate(message);
        if (valid == true) {
            $('button').attr('disabled', 'disabled');
            $('#contactbtn').button('loading');
            var data = "name=" + name.val() + "&email=" + email.val() + "&message=" + message.val();
            $.ajax({
                url: "process.php",
                type: "GET",
                data: data,
                cache: false,
                beforeSend: function () {
                    $('button').removeAttr('disabled');
                    $('#contactbtn').button('reset');
                    $('#myModal').modal('hide');
                    $('#contact-submited').html('Sending message...');
                    $('#contact-submited').fadeIn();
                }
            })
                .done(function (html) {
                if (html == 1) {
                    $('#contact-submited').fadeOut();
                    $('#contact-submited').html('Thank you for your message. I\'ll get back to you ASAP.');
                    $('#contact-submited').fadeIn();
                    
                }
            })
                .fail(function () {
                $('#contact-submited').fadeOut();
                $('#contact-submited').html('Sorry, the message was not sent. Please email me at jose.m.mateo@ieee.org');
                $('#contact-submited').fadeIn();
                
            });

        } else {
            return false;
        }
    });


    function validate(input) {
        if (input.val() == null || input.val() == '') {
            input.addClass('error');

            valid = false;
            return false;
        } else {
            input.removeClass('error');
        }
    }
    return false;
});