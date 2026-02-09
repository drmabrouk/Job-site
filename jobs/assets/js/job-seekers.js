jQuery(document).ready(function($) {
    function updateSeekersList() {
        var formData = $('#jobs-seekers-filter-form').serialize();
        formData += '&action=jobs_filter_seekers';

        $('#jobs-seekers-results').css('opacity', '0.5');

        $.post(jobs_vars.ajax_url, formData, function(response) {
            $('#jobs-seekers-results').html(response).css('opacity', '1');
        });
    }

    $('#jobs-filter-seekers-btn').on('click', function(e) {
        e.preventDefault();
        updateSeekersList();
    });

    // Initial load
    updateSeekersList();

    // Handle Direct Job Offer (Premium Modal)
    $(document).on('click', '.send-offer-btn-premium', function() {
        const seekerId = $(this).data('seeker-id');
        const seekerName = $(this).data('seeker-name');

        // Use the existing message-modal if it exists, or create one for Job Seekers page
        if($('#message-modal').length === 0) {
            $('body').append(`
                <div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.4); backdrop-filter: blur(12px); z-index:9999; align-items:center; justify-content:center; padding: 20px;">
                    <div class="modal-content" style="background:white; padding:60px; border-radius:40px; width:100%; max-width:600px; box-shadow:0 40px 100px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.3);">
                        <h3 id="modal-title" style="margin-top:0; color: #1d3469; font-size: 2em; font-weight: 800;"></h3>
                        <p style="color: #64748b; margin-top: 10px; font-size: 1.1em;">Send a professional message or a direct job invitation.</p>
                        <textarea id="message-text" placeholder="Write your professional message here..." style="width:100%; height:200px; padding:25px; border-radius:20px; border:1px solid #e2e8f0; margin: 30px 0; font-family: inherit; font-size: 1.1em; background: #f8fafc; outline: none;"></textarea>
                        <div style="display:flex; justify-content:flex-end; gap:20px;">
                            <button class="jobs-btn-minimal close-modal" style="padding: 18px 35px; font-weight: 700; color: #64748b; border: none; background: none; cursor: pointer;">Cancel</button>
                            <button class="jobs-btn" id="confirm-send-job-offer" style="background: #1d3469; color: white; padding: 18px 45px; border-radius: 14px; font-weight: 700; border: none; cursor: pointer;">Send Offer Now</button>
                        </div>
                    </div>
                </div>
            `);
        }

        $('#modal-title').text("Contact " + seekerName);
        $('#confirm-send-job-offer').data('seeker-id', seekerId);
        $('#message-modal').css('display', 'flex').hide().fadeIn(300);
    });

    $(document).on('click', '.close-modal', function() {
        $('#message-modal').fadeOut(300);
    });

    $(document).on('click', '#confirm-send-job-offer', function() {
        const seekerId = $(this).data('seeker-id');
        const message = $('#message-text').val();
        if(!message) return;

        const $btn = $(this);
        $btn.prop('disabled', true).text('Sending...');

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_send_job_offer',
            seeker_id: seekerId,
            message: message,
            nonce: jobs_vars.nonce
        }, function(response) {
            if (response.success) {
                $('#message-modal .modal-content').html(`
                    <div style="text-align:center; padding: 40px;">
                        <div style="font-size: 80px; margin-bottom: 30px;">✅</div>
                        <h2 style="color: #1d3469; font-weight: 800;">Offer Sent!</h2>
                        <p style="color: #64748b; font-size: 1.1em;">The candidate has been notified via dashboard and email.</p>
                        <button class="jobs-btn close-modal" style="margin-top: 40px; background: #1d3469; color: white; padding: 15px 40px; border-radius: 12px;">Close</button>
                    </div>
                `);
            } else {
                alert("Error: " + response.data);
                $btn.prop('disabled', false).text('Send Offer Now');
            }
        });
    });
});
