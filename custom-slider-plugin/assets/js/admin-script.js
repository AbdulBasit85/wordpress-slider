jQuery(document).ready(function($) {
    // Initialize color pickers
    $('.cas-color-picker').wpColorPicker();

    // Add new slide
    $('#cas-add-slide').on('click', function() {
        const $container = $('.cas-slides-container');
        const index = $container.children().length;
        const post_id = typeof cas_slider_vars !== 'undefined' ? cas_slider_vars.post_id : '';
        
        const slideHTML = `
            <div class="cas-slide" data-index="${index}">
                <div class="cas-slide-header">
                    <h3>Slide ${index + 1}</h3>
                    <button type="button" class="cas-remove-slide button">Remove</button>
                </div>
                <div class="cas-slide-content">
                    <div class="cas-form-group">
                        <label>Background Image</label>
                        <div class="cas-image-upload">
                            <input type="hidden" class="cas-image-url" name="cas_slides[${index}][image]" value="">
                            <button type="button" class="cas-upload-image button">Select Image</button>
                            <button type="button" class="cas-remove-image button">Remove</button>
                            <div class="cas-image-preview"></div>
                        </div>
                    </div>
                    
                    <div class="cas-form-group">
                        <label>Slide Content</label>
                        <textarea name="cas_slides[${index}][content]" rows="5" class="widefat"></textarea>
                        <p class="description">Note: Shortcodes in slide content will not be processed</p>
                    </div>
                    
                    <div class="cas-form-row">
                        <div class="cas-form-group">
                            <label>Background Color</label>
                            <input type="text" class="cas-color-picker" name="cas_slides[${index}][bg_color]" value="#ffffff">
                        </div>
                        
                        <div class="cas-form-group">
                            <label>Content Position</label>
                            <select name="cas_slides[${index}][content_position]">
                                <option value="left">Left</option>
                                <option value="center" selected>Center</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="cas-shortcode-display">
                        <label>Shortcode for this slide:</label>
                        <div class="cas-shortcode-input-wrapper">
                            <input type="text" readonly value="[cas_slide id='${post_id}' slide='${index}']" class="widefat">
                            <button class="cas-copy-shortcode button">Copy</button>
                        </div>
                        <p class="description">Use this shortcode to display this specific slide anywhere</p>
                    </div>
                </div>
            </div>`;
        
        $container.append(slideHTML);
        
        // Initialize color picker for new slide
        $('.cas-color-picker').wpColorPicker();
    });

    // Image upload
    $(document).on('click', '.cas-upload-image', function(e) {
        e.preventDefault();
        const $button = $(this);
        const $preview = $button.siblings('.cas-image-preview');
        const $urlField = $button.siblings('.cas-image-url');
        
        const frame = wp.media({
            title: 'Select or Upload Image',
            button: { text: 'Use this image' },
            multiple: false
        });
        
        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            $urlField.val(attachment.url);
            $preview.html('<img src="' + attachment.url + '" style="max-width: 200px;">');
        });
        
        frame.open();
    });

    // Remove image
    $(document).on('click', '.cas-remove-image', function(e) {
        e.preventDefault();
        const $button = $(this);
        $button.siblings('.cas-image-url').val('');
        $button.siblings('.cas-image-preview').empty();
    });

    // Remove slide
    $(document).on('click', '.cas-remove-slide', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to remove this slide?')) {
            $(this).closest('.cas-slide').remove();
        }
    });

    // Copy shortcode
    $(document).on('click', '.cas-copy-shortcode', function(e) {
        e.preventDefault();
        const $input = $(this).siblings('input');
        $input.select();
        document.execCommand('copy');
        
        // Show copied message
        const $button = $(this);
        const originalText = $button.text();
        $button.text('Copied!');
        setTimeout(function() {
            $button.text(originalText);
        }, 2000);
    });

    // Make slides sortable
    $('.cas-slides-container').sortable({
        handle: '.cas-slide-header',
        placeholder: 'cas-slide-placeholder',
        forcePlaceholderSize: true,
        update: function() {
            $('.cas-slide').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('input, select, textarea').each(function() {
                    const name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });
                
                // Update shortcode
                const post_id = typeof cas_slider_vars !== 'undefined' ? cas_slider_vars.post_id : '';
                $(this).find('.cas-shortcode-display input').val('[cas_slide id=\'' + post_id + '\' slide=\'' + index + '\']');
            });
        }
    });
});