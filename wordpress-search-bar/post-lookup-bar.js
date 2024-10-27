jQuery(document).ready(function($) {
    $('#post-lookup-input').on('input', function() {
        let query = $(this).val();
        
        if (query.length < 2) {
            $('#post-lookup-results').hide();
            return;
        }

        $.ajax({
            url: postLookup.ajax_url,
            method: 'POST',
            data: {
                action: 'post_lookup',
                nonce: postLookup.nonce,
                query: query
            },
            success: function(response) {
                let resultsDiv = $('#post-lookup-results');
                resultsDiv.empty().show();

                if (response.length) {
                    response.forEach(function(post) {
                        resultsDiv.append(
                            `<div class="lookup-result-item"><a href="${post.link}">${post.title}</a></div>`
                        );
                    });
                } else {
                    resultsDiv.append('<div class="lookup-result-item">No results found.</div>');
                }
            }
        });
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#post-lookup-results, #post-lookup-input').length) {
            $('#post-lookup-results').hide();
        }
    });
});
