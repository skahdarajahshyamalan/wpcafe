
"use strict";
jQuery(document).ready( function($) {
    _wpMediaViewsL10n.insertIntoPost = 'Insert';
    function ct_media_upload(button_class, input_field_id) {
        
        if(input_field_id === '#sound_media_file'){
        $('body').on('click', button_class, function(e) {
            e.preventDefault;
            var button_id = '#'+$(this).attr('id');
            var button = $(button_id);

            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor ){
                
                // Create the media frame.
                var frame = wp.media({
                    library: {
                            type: [ 'video', 'audio' ]
                    },
                });
   
                // Finally, open the modal.
                frame.open(button);

                frame.on( 'select', function() {
                    // Get media attachment details from the frame state
                    var attachment = frame.state().get('selection').first().toJSON();
                    
                    $(input_field_id).val(attachment.id);
                    $('#sound-media-wrapper').html('<audio class="custom_media_sound" style="margin:0;padding:0;max-height:100px;float:none;" controls><source src="" type="audio/ogg"><source src="" type="audio/mpeg"></audio>');
                    $('#sound-media-wrapper .custom_media_sound source').attr('src',attachment.url);
                });

            }
            return false;
        });
        } else {
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;


            $('body').on('click', button_class, function(e) {
                e.preventDefault;
                var button_id = '#'+$(this).attr('id');
                var button = $(button_id);
                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media ) {
                    $('#location_image').val(attachment.id);
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                    $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
                    } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                    }
                }
                if ( typeof wp !== 'undefined' && wp.media && wp.media.editor ){
                    wp.media.editor.open(button);
                }
                return false;
            });
        }
    }

    function ct_media_remove(remove_button_class, input_field_id){
        $('body').on('click', remove_button_class, function(){
            $(input_field_id).val('');
            if(input_field_id === '#sound_media_file'){
                $('#sound-media-wrapper').html('');
            } else {
                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
            }
            
        });
    }
    

    ct_media_upload('.ct_tax_media_button.button', '#location_image');
    ct_media_upload('.sound_media_button.button', '#sound_media_file');

    ct_media_remove('.ct_tax_media_remove', '#location_image');
    ct_media_remove('.sound_media_remove', '#sound_media_file');

});
