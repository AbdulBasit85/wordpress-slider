jQuery(document).ready(function($) {
    // Add new slide
    $('#add-slide').on('click', function(e) {
        e.preventDefault();
        var index = $('.slide-item').length;
        var newSlide = `
            <div class="slide-item" data-index="${index}">
                <textarea name="slides[${index}][content]" placeholder="Slide Content"></textarea>
                <input type="text" name="slides[${index}][image]" placeholder="Image URL">
                <button class="remove-slide">Remove Slide</button>
            </div>
        `;
        $('.slides-container').append(newSlide);
    });

    // Remove slide
    $('.slides-container').on('click', '.remove-slide', function(e) {
        e.preventDefault();
        $(this).closest('.slide-item').remove();
    });

    // Make slides sortable
    $('.slides-container').sortable({
        update: function(event, ui) {
            // Update slide indexes
            $('.slide-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('textarea, input').each(function() {
                    var name = $(this).attr('name');
                    name = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });
            });
        }
    });
});