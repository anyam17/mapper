<div class="alert alert-success" id="msg" class="col-md-3" style="display: none;"></div>

<div class="panel panel-default">
    <div class="panel-heading" style="background-color: #00aced;">New Car</div>
    <div class="list-group form-horizontal" style="padding: 20px 10px 40px 10px">
        <!-- <form class="form-horizontal" id="car_form" action="store" method="post" enctype="multipart/form-data" role="form">
            {{ csrf_field() }} -->
            <div id="name-group" class="form-group">
                <label for="name" class="col-md-3 control-label">Name</label>
                <div class="col-md-9 name-group">
                    <input id="name" type="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="Car Name">
                </div>
            </div>

            <div id="latitude-group" class="form-group">
                <label for="name" class="col-md-3 control-label">Latitude</label>
                <div class="col-md-9 latitude-group">
                    <input id="latitude" type="latitude" class="form-control" name="latitude" value="{{ old('latitude') }}" placeholder="Latitude">
                </div>
            </div>

            <div id="longitude-group" class="form-group">
                <label for="name" class="col-md-3 control-label">Longitude</label>
                <div class="col-md-9 longitude-group">
                    <input id="longitude" type="longitude" class="form-control" name="longitude" value="{{ old('longitude') }}" placeholder="Longitude">
                </div>
            </div>

            <div id="image-group" class="form-group">
                <label for="name" class="col-md-3 control-label">Image</label>
                <div class="col-md-9 image-group">
                    <input id="image" type="File" class="form-control" name="image" placeholder="">
                </div>
            </div>

            <button class="btn btn-sm btn-info" id="car-button" style="float: right;">
                <i class="fa fa-plus"></i> Add 
            </button>
        <!-- </form> -->
    </div>
</div>

@section('custom-js')
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMWKCEVgLIsM6NCUKrRL2SGKlxvZsE6zc&callback=initMap">
</script>

<script type="text/javascript">
    var _token = $('meta[name="_token"]').attr('content');

    $(document).ready(function() {
        $(document).on("click", '#car-button', function(event) {

            /*var formData = new FormData($(this)[0]);*/
            /*var formData = this.files[0];
            $.post(store, data);*/
            var name = $("#name").val();
            var latitude = $("#latitude").val();
            var longitude = $("#longitude").val();
            /*var image = $("#image").val();*/
            /*var file_data = $("#image").prop("files")[0];   // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("file", file_data)*/

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST', // Type of response and matches what we said in the route
                url: 'store',
                cache: false,
                /*contentType: false,
                processData: false,*/
                dataType: 'json',
                data: { name, latitude, longitude, _token },
 

                success: function(data) {

                    /*Error handling and validation messages*/
                    if (data.error) {
                        /*handle errors for name*/
                        if (data.error[0]) {
                            $('#name-group').addClass('has-error'); // add the error class to show red input
                            $('.name-group').append('<div class="help-block"><strong>' + data.error[0] + '</strong></div>'); 
                        }

                        /*handling errors for latitude*/
                        if (data.error[1]) {
                            $('#latitude-group').addClass('has-error'); // add the error class to show red input
                            $('.latitude-group').append('<div class="help-block"><strong>' + data.error[1] + '</strong></div>'); 
                        }

                        /*handling errors for longitude*/
                        if (data.error[2]) {
                            $('#longitude-group').addClass('has-error'); // add the error class to show red input
                            $('.longitude-group').append('<div class="help-block"><strong>' + data.error[2] + '</strong></div>'); 
                        }
                    }
                    else {

                        var infoWindow = new google.maps.InfoWindow();

                        var coordinates = new google.maps.LatLng(10.363, 15.044);
                        var mapOptions = {
                            zoom: 3,
                            center: coordinates
                        };

                        var map = new google.maps.Map(document.getElementById('map-content'), mapOptions);

                        for (var i = 0; i < data.length; i++) {
                            var each_data = data[i];
                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(each_data.latitude, each_data.longitude),
                                map: map,
                                icon: {
                                        url: "/images/van.jpg",
                                        scaledSize: new google.maps.Size(30, 30)
                                    },
                                title: each_data.name
                            });

                            /*Creating a closure to retain the correct data */
                            (function(marker, each_data) {
                                /*Attaching a click event to the current marker*/
                                google.maps.event.addListener(marker, "click", function(e) {
                                    infoWindow.setContent(each_data.name, each_data.latitude);
                                    infoWindow.open(map, marker);
                                });
                            }) (marker, each_data);
                        }
                    }

                    /*Displays message on successful submition of form*/
                    $('#msg').html(each_data.name + ' Successfully Added<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>').show();

                    /*Remove the error class and the error text on resubmition of the form*/
                    $('.form-group').removeClass('has-error'); 
                    $('.help-block').remove(); 
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    $('#map-content').html('<p class="text text-danger">Oops, something unexpected happened. Please, refresh and try again. </p>').show();
                },
            });
        });

    });


  function initMap() {
    var uluru = {lat: -25.363, lng: 131.044};
    var map = new google.maps.Map(document.getElementById('map-content'), {
      zoom: 3,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
}

</script>
@endsection