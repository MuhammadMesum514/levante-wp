;(function ($) {
    $(function () {

        $(document)
            .on('rtcl_recaptcha_loaded', function () {
                const $agentContactForms = $("form.agent-email-form, form#agent-email-form");
                if ($agentContactForms.length && typeof grecaptcha !== 'undefined' && $.inArray("agent_contact", rtcl.recaptcha.on) !== -1) {
                    $agentContactForms.each((index, form) => {
                        const $form = $(form);
                        if (!$form.data('reCaptchaId')) {
                            const args = {'sitekey': rtcl.recaptcha.site_key};
                            if ($form.find('#rtcl-agent-contact-g-recaptcha').length) {
                                $form.data('reCaptchaId', grecaptcha.render($form.find('#rtcl-agent-contact-g-recaptcha')[0], args));
                            } else if ($form.find('.rtcl-g-recaptcha-wrap').length) {
                                $form.data('reCaptchaId', grecaptcha.render($form.find('.rtcl-g-recaptcha-wrap')[0], args));
                            }
                        }
                    });
                }
            });

        if ($.fn.validate) {
            // Agent Contact Form
            $("form.agent-email-form, form#agent-email-form")
                .each(function () {
                    $(this)
                        .validate({
                            submitHandler: function (form) {
                                var $form = $(form),
                                    targetBtn = $form.find('.sc-submit'),
                                    responseHolder = $form.find('.rtcl-response'),
                                    msgHolder = $("<div class='alert'></div>"),
                                    recaptchId = $form.data('reCaptchaId'),
                                    sc_response = '';
                                // recaptch v2
                                if (rtcl.recaptcha && typeof grecaptcha !== 'undefined' && rtcl.recaptcha.on && $.inArray("agent_contact", rtcl.recaptcha.on) !== -1) {
                                    if (rtcl.recaptcha.v === 2 && recaptchId !== undefined) {
                                        const response = grecaptcha.getResponse(recaptchId);
                                        responseHolder.html('');
                                        if (0 === response.length) {
                                            responseHolder.removeClass('text-success').addClass('text-danger').html(rtcl.recaptcha.msg.invalid);
                                            grecaptcha.reset(recaptchId);
                                            return false;
                                        }
                                        submit_agent_form_data_ajax(response);
                                        return false;
                                    } else if (rtcl.recaptcha.v === 3) {
                                        grecaptcha.ready(function () {
                                            $form.rtclBlock();
                                            grecaptcha.execute(rtcl.recaptcha.site_key, {action: 'agent_contact'}).then(function (token) {
                                                $form.rtclUnblock();
                                                submit_agent_form_data_ajax(token);
                                            });
                                        });
                                        return false;
                                    }
                                }
                                submit_agent_form_data_ajax();
                                return false;

                                function submit_agent_form_data_ajax(recaptcha_token) {
                                    var formData = new FormData(form);
                                    formData.append('action', 'rtcl_send_mail_to_agent');
                                    formData.append('user_id', rtcl_agent.user_id || 0);
                                    formData.append('__rtcl_wpnonce', rtcl.__rtcl_wpnonce);
                                    if (recaptcha_token) {
                                        formData.append('g-recaptcha-response', recaptcha_token);
                                    }
                                    $.ajax({
                                        url: rtcl_agent.ajaxurl,
                                        dataType: 'json',
                                        data: formData,
                                        type: 'POST',
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        beforeSend: function () {
                                            $form.rtclBlock();
                                            $form.addClass("rtcl-loading");
                                            $form.find('input textarea').prop("disabled", true);
                                            targetBtn.prop("disabled", true);
                                            responseHolder.html('');
                                            $('<span class="rtcl-icon-spinner animate-spin"></span>').insertAfter(targetBtn);
                                        },
                                        success: function (response) {
                                            $form.rtclUnblock();
                                            targetBtn.prop("disabled", false).next('.rtcl-icon-spinner').remove();
                                            $form.find('input textarea').prop("disabled", false);
                                            $form.removeClass("rtcl-loading");
                                            if (response.success) {
                                                msgHolder.removeClass('alert-danger').addClass('alert-success').html(response.data.message).appendTo(responseHolder);
                                                $form[0].reset();
                                                if ($form.parent("#agent-email-area").parent().data('hide') !== 0) {
                                                    setTimeout(function () {
                                                        responseHolder.html('');
                                                        //$form.parent("#agent-email-area").slideUp();
                                                    }, 1000);
                                                }
                                            } else {
                                                msgHolder.removeClass('alert-success').addClass('alert-danger').html(response.data.error).appendTo(responseHolder);
                                            }
                                            if (rtcl.recaptcha && rtcl.recaptcha.v === 2 && recaptchId !== undefined) {
                                                grecaptcha.reset(recaptchId);
                                            }
                                        },
                                        error: function (e) {
                                            $form.rtclUnblock();
                                            $form.find('input textarea').prop("disabled", false);
                                            msgHolder.removeClass('alert-success').addClass('alert-danger').html(e.responseText).appendTo(responseHolder);
                                            targetBtn.prop("disabled", false).next('.rtcl-icon-spinner').remove();
                                            $form.removeClass("rtcl-loading");
                                        }
                                    });
                                }
                            }
                        });
                });
        }
    });
}(jQuery));