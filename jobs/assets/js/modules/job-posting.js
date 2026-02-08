jQuery(document).ready(function($) {
    const locationData = {
        "uae": ["Dubai", "Abu Dhabi", "Sharjah", "Ajman", "Fujairah", "Ras Al Khaimah", "Umm Al Quwain"],
        "saudi-arabia": ["Riyadh", "Jeddah", "Mecca", "Medina", "Dammam", "Khobar", "Abha"],
        "qatar": ["Doha", "Al Wakrah", "Al Rayyan", "Al Khor"],
        "kuwait": ["Kuwait City", "Al Ahmadi", "Hawalli", "Salmiya"],
        "egypt": ["Cairo", "Alexandria", "Giza", "Sharm El Sheikh", "Hurghada", "Luxor"],
        "jordan": ["Amman", "Zarqa", "Irbid", "Aqaba"],
        "lebanon": ["Beirut", "Tripoli", "Sidon", "Tyre"],
        "oman": ["Muscat", "Salalah", "Sohar", "Nizwa"],
        "bahrain": ["Manama", "Riffa", "Muharraq", "Hamad Town"],
        "algeria": ["Algiers", "Oran", "Constantine"],
        "iran": ["Tehran", "Mashhad", "Isfahan"],
        "iraq": ["Baghdad", "Basra", "Erbil"],
        "libya": ["Tripoli", "Benghazi"],
        "morocco": ["Casablanca", "Rabat", "Marrakesh"],
        "palestine": ["Gaza City", "Ramallah", "Hebron"],
        "syria": ["Damascus", "Aleppo"],
        "tunisia": ["Tunis", "Sfax"],
        "yemen": ["Sanaa", "Aden"],
        "usa": ["New York", "Los Angeles", "Chicago", "Houston", "Phoenix"],
        "uk": ["London", "Birmingham", "Manchester", "Glasgow"],
        "canada": ["Toronto", "Montreal", "Vancouver"],
        "australia": ["Sydney", "Melbourne", "Brisbane"],
        "new-zealand": ["Auckland", "Wellington"],
        "ireland": ["Dublin", "Cork"],
        "south-africa": ["Johannesburg", "Cape Town", "Durban"]
    };

    $(document).on('change', '#posting-country', function() {
        const country = $(this).val();
        const $citySelect = $('#posting-city');
        $citySelect.empty().append('<option value="">Select City</option>');

        if (country && locationData[country]) {
            locationData[country].forEach(function(city) {
                $citySelect.append('<option value="' + city.toLowerCase().replace(/ /g, '-') + '">' + city + '</option>');
            });
        }
    });

    $(document).on('submit', '#jobs-post-job-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $msg = $form.find('.jobs-module-message');
        var formData = $form.serialize();

        $form.css('opacity', '0.5');

        $.post(jobs_vars.ajax_url, formData + '&action=jobs_post_job_handler', function(response) {
            $form.css('opacity', '1');
            if (response.success) {
                $msg.html('<p style="color: green; background: #e6ffed; padding: 10px; border-radius: 5px; margin-bottom: 20px;">' + response.data + '</p>');
                if ($form.find('[name="is_draft"]').val() !== '1') {
                    $form[0].reset();
                }
            } else {
                $msg.html('<p style="color: red; background: #fff1f0; padding: 10px; border-radius: 5px; margin-bottom: 20px;">' + response.data + '</p>');
            }

            // Scroll to message
            $('.jobs-module-container').animate({ scrollTop: 0 }, 'slow');
        });
    });

    // Handle draft saving
    $(document).on('click', '.jobs-save-draft-btn', function() {
        $('#is_draft').val('1');
        $('#jobs-post-job-form').submit();
        // Reset is_draft after submit
        setTimeout(function() {
            $('#is_draft').val('0');
        }, 500);
    });
});
