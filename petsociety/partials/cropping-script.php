<script>
    var bs_modal = $("#modal"); //select cropping-box
    var image = document.getElementById("image"); //select #image (in cropping-box)
    var imgUrl = document.getElementById("imgUrl"); //select #imgUrl (input field that sends img path to db)
    var cropper, reader, file;

    //when user uploads new file (<input type="file"> has class .image):
    $("body").on("change", ".image", function(e) {
        var files = e.target.files; //get the file

        var done = function(url) {
            image.src = url; //set image url to the file
            bs_modal.modal("show"); //open cropping-box
        };

        //if there is a file, select one
        if (files && files.length > 0) {
            file = files[0];

            //if file is in URL format send it to done()
            if (URL) {
                done(URL.createObjectURL(file));

                //if file isn't in URL format read it, convert it and send it do done()    
                /* FileReader object lets web applications asynchronously read the contents of files 
                (or raw data buffers) stored on the user's computer */
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    //send result to done() function
                    done(reader.result);
                };
                reader.readAsDataURL(file); //format of result = URL file format
            }
        }
    });

    //when the cropping-box is visible create new cropper object
    bs_modal.on("shown.bs.modal", function() {
        cropper = new Cropper(image, {
            //cropper settings
            aspectRatio: 1, //square
            viewMode: 1, //restrict the crop box to not exceed the size of the canvas
            autoCropArea: 1, //automatic cropping area size (1 = 100%)
            preview: ".preview" //small box showing result from canvas
        });
        //when the cropping-box is hidden destroy the cropper object
    }).on("hidden.bs.modal", function() {
        cropper.destroy();
        cropper = null;
    });

    //when user clicks on crop button
    $("#crop").click(function() {

        //returns cropped part of canvas (small square on the right)
        canvas = cropper.getCroppedCanvas({
            minWidth: 256,
            minHeight: 256,
            maxWidth: 4096,
            maxHeight: 4096,
            fillColor: "#fff", //fill transparent values with white (so we can save it as .jpeg)
            imageSmoothingEnabled: true, //smooth image
            imageSmoothingQuality: "high" //get image in high quality
        });

        //save HTML canvas as image in blob format
        canvas.toBlob(function(blob) {

            //convert blob to base64 format
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;

                /*send image data (in base64 format) to partials/img-upload-cropped.php for processing - 
                converting to jpeg, checking file size, saving in temp folder*/
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "partials/img-upload-cropped.php",
                    data: {
                        image: base64data
                    },
                    //if the image was processed successfully
                    success: function(data) {
                        bs_modal.modal("hide"); //hide cropping-box
                        alert("Your image successfully uploaded on server. Submit the form to use this image."); //tell user the image was saved
                        imgUrl.value = data; //set the new location as value of input field
                    }
                });
            };
        }, "image/jpeg", 1);
    });
</script>