jQuery(document).ready(function($) {
    // Tab switching
    $('.auth-tab').on('click', function() {
        var target = $(this).data('target');
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.auth-panel').removeClass('active');
        $('#auth-' + target).addClass('active');
    });

    // Handle AJAX Login
    $('#jobs-login-form-ajax').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $status = $('#auth-status-message');
        var data = {
            action: 'jobs_ajax_login', // I need to add this handler in forms-handler.php if I didn't
            log: $form.find('#user_login').val(),
            pwd: $form.find('#user_pass').val(),
            rememberme: $form.find('input[name="rememberme"]').is(':checked') ? 'forever' : '',
            security: jobs_vars.nonce // Ensure jobs_vars is available
        };

        $status.html('<p style="color: blue;">Authenticating...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if (response.success) {
                $status.html('<p style="color: green;">Login successful! Redirecting...</p>');
                window.location.href = response.data.redirect;
            } else {
                $status.html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    // Handle Email Verification
    $('#jobs-verify-email-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $status = $('#verify-status');
        var data = {
            action: 'jobs_verify_email',
            user_id: $form.find('input[name="user_id"]').val(),
            code: $form.find('input[name="code"]').val()
        };

        $status.html('<p style="color: blue;">Verifying...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if (response.success) {
                $status.html('<p style="color: green;">Email verified! Redirecting to setup...</p>');
                setTimeout(function() {
                    window.location.href = response.data.redirect;
                }, 1500);
            } else {
                $status.html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    // Handle Password Reset Request
    $('#jobs-lostpassword-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $status = $('#password-status');
        var data = {
            action: 'jobs_request_password_reset',
            user_login: $form.find('input[name="user_login"]').val()
        };

        $status.html('<p style="color: blue;">Processing request...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if (response.success) {
                $status.html('<p style="color: green;">' + response.data + '</p>');
                $form.hide();
            } else {
                $status.html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    // Handle Password Reset Execution
    $('#jobs-resetpassword-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $status = $('#password-status');
        var data = {
            action: 'jobs_reset_password',
            rp_key: $form.find('input[name="rp_key"]').val(),
            rp_login: $form.find('input[name="rp_login"]').val(),
            pass1: $form.find('input[name="pass1"]').val(),
            pass2: $form.find('input[name="pass2"]').val()
        };

        $status.html('<p style="color: blue;">Resetting password...</p>');

        $.post(jobs_vars.ajax_url, data, function(response) {
            if (response.success) {
                $status.html('<p style="color: green;">' + response.data + '</p>');
                setTimeout(function() {
                    window.location.href = jobs_vars.home_url + '/login/';
                }, 2000);
            } else {
                $status.html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    // Biometric Login Placeholder
    $('#biometric-login-btn').on('click', function() {
        var $status = $('#auth-status-message');
        $status.html('<p style="color: blue;">Checking biometric sensors...</p>');

        $.post(jobs_vars.ajax_url, { action: 'jobs_biometric_login' }, function(response) {
            $status.html('<p style="color: red;">' + response.data + '</p>');
        });
    });

    // Resend verification code
    $('#resend-verify-code').on('click', function(e) {
        e.preventDefault();
        var $status = $('#verify-status');
        $status.html('<p style="color: blue;">Resending code...</p>');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_resend_verify_code', // Need to add this handler
            user_id: $('input[name="user_id"]').val()
        }, function(response) {
            if (response.success) {
                $status.html('<p style="color: green;">New code has been sent!</p>');
            } else {
                $status.html('<p style="color: red;">' + response.data + '</p>');
            }
        });
    });

    // Account Type Toggle logic
    $('.account-type-btn').on('click', function() {
        $('.account-type-btn').removeClass('active').css({'background': '#fff', 'color': '#333', 'border-color': '#ddd'});
        $(this).addClass('active').css({'background': 'var(--jobs-primary-color)', 'color': '#fff', 'border-color': 'var(--jobs-primary-color)'});

        var role = $(this).data('role');
        $('#selected-user-role').val(role);

        $('.conditional-fields').hide();
        $('#' + role.replace('_', '-') + '-fields').fadeIn();
    });

    // Initialize styling for active button
    $('.account-type-btn.active').trigger('click');

    // Username validation: minimum 4 English letters
    $('#reg-username').on('blur', function() {
        var val = $(this).val();
        var $status = $('#auth-status-message');

        if (val.length > 0 && val.length < 4) {
            $status.html('<p style="background: #fef2f2; color: #b91c1c; padding: 10px; border-radius: 8px; border: 1px solid #fecaca;">The username must be at least 4 characters long.</p>');
        } else if (val.length >= 4 && !/^[A-Za-z]+$/.test(val)) {
            $status.html('<p style="background: #fef2f2; color: #b91c1c; padding: 10px; border-radius: 8px; border: 1px solid #fecaca;">The username must contain only English letters.</p>');
        } else {
            $status.empty();
        }
    });
});
