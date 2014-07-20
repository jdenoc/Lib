/**
 * Created by denis on 7/18/14.
 * Don't forget to include the Lib/css/loading.css file.
 */

var loading = {
    active: false,
    img: '',
    start: function(){
        if(!loading.active){
            loading.active = true;
            // add the overlay with loading image to the page
            var over = '<div id="overlay"><img id="loading" src="'+loading.img+'" alt="loading"/></div>';
            $(over).appendTo('body');

            // click on the overlay to remove it
            $('#overlay').click(function() {
                $(this).remove();
                loading.active = false;
            });

            // hit escape to close the overlay
            $(document).keyup(function(e) {
                if (e.which === 27) {
                    $('#overlay').remove();
                    loading.active = false;
                }
            });
        }
    },
    end: function(){
        if(loading.active){
            loading.active = false;
            $('#overlay').delay(250).queue(function(){
                $(this).remove();
                $(this).dequeue();
            });
        }
    },
    setImage: function(imgLocation){
        loading.img = imgLocation;
    }
};