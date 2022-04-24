(function($){
    $(document).ready(function(){

        /**
         * Confirm Delete a Ticket
         */
        $("a.info-delete").on("click", function(){
            if( ! confirm("SIND SIE SICHER ?")){
                return false;
            }
        });
         
        /**
         * Media Image Upload
         */
        $(document).on("click",".js-image-upload", function(e){
            e.preventDefault();
            
            var button = $(".js-image-upload");
            
            var tm_wp_media = wp.media({
                
                title: 'Select Image',
                
                library: {
                    type: 'image'
                },
                
                button: {
                    text: 'Upload Image'
                },

                multiple: false

            });

            tm_wp_media.open();

            tm_wp_media.on( "select", function(){
                var attachment = tm_wp_media.state().get('selection').first().toJSON();

                button.siblings('input.image-link-input').val(attachment.url);
                
                $('.uploaded-logo').attr('src', attachment.url);
            });
        });
    });

  
    
    /**
     * Ticket Template Options Changing
     */
  
    let ticket_options = document.querySelectorAll("select#bixxs_events_event_template option");
    
    /**
     * Ticket Template On Change
     */
    $("select#bixxs_events_event_template").on("change", function(){
        // console.log(ticket_options);

        ticket_options.forEach(element => {
            element.removeAttribute("selected");
        });
        this.options[this.selectedIndex].setAttribute("selected", "selected");

    });

})(jQuery);