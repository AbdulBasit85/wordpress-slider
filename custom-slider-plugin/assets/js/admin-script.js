jQuery(document).ready(function($) {
    // Add new slide
    $('#add-slide').on('click', function(e) {
        e.preventDefault();
        var index = $('.slide-item').length;
        var newSlide = `
            <div class="slide-item" data-index="${index}">
                <div class="slide-header">
                    <span class="slide-title">Slide ${index + 1}</span>
                    <button class="remove-slide">Remove Slide</button>
                </div>
                <div class="slide-content">
                    <label for="slide-image-${index}">Image URL:</label>
                    <input type="text" id="slide-image-${index}" name="slides[${index}][image]" placeholder="Enter image URL">
                    <label for="slide-content-${index}">Content:</label>
                    <textarea id="slide-content-${index}" name="slides[${index}][content]" placeholder="Enter slide content"></textarea>
                </div>
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
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    name = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });
            });
        }
    });
});