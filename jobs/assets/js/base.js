window.JobsState = {
    search: {
        job_search: '',
        specialization: '',
        country: '',
        city: '',
        lat: 0,
        lng: 0,
        paged: 1
    },
    ui: {
        isSearching: false,
        activeModule: null
    }
};

jQuery(document).ready(function($) {
    // Contact User Logic
    $(document).on('click', '.contact-user-btn', function() {
        var userId = $(this).data('user-id');
        var name = $(this).data('name');
        var message = prompt('Send a message to ' + name + ':');

        if (message) {
            $.post(jobs_vars.ajax_url, {
                action: 'jobs_send_message',
                receiver_id: userId,
                message: message,
                nonce: jobs_vars.nonce
            }, function(response) {
                if (response.success) {
                    alert('Message sent successfully!');
                } else {
                    alert('Error: ' + response.data);
                }
            });
        }
    });

    // Global Phone Initialization
    window.initJobsPhoneFields = function() {
        if (window.intlTelInput) {
            $('.jobs-intl-phone, .v2-phone-input').not('.iti-initialized').each(function() {
                var $this = $(this);
                $this.addClass('iti-initialized');
                window.intlTelInput(this, {
                    preferredCountries: ['eg', 'ae', 'sa', 'jo', 'us', 'gb'],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
                    separateDialCode: true,
                });
            });
        }
    };
    window.initJobsPhoneFields();
    $(document).on('jobs_module_loaded', window.initJobsPhoneFields); // Custom event when module loads
});
