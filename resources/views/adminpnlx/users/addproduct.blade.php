@extends('adminpnlx.layouts.default')
@section('section')
    <section>
        <form action="{{ route('addproduct') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>Register</h1>

                                <div class="row align-items-center">
                                    <div class="col mt-4">
                                        <div class="form-group">
                                            <label for="firstname"><b>name</b></label>
                                            <input type="text" class="form-control" placeholder="Enter First Name"
                                                name="name" id="title" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row align-items-center">
                                    <div class="col mt-4">
                                        <div class="form-group">
                                            <label for="firstname"><b>description</b></label>
                                            <input type="text" class="form-control" placeholder="Enter description"
                                                name="description" id="title" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sliderimage" class="form-label">image</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="sliderimage" class="form-label">Price</label>
                                    <input type="text" class="form-control" placeholder="Enter price" name="price"
                                        id="title" required>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <select id="brand" name="brand_id" class="form-select">
                                    <label class="form-label" for="brand">Brand</label>
                                    <option value="">Select Brand</option>
                                    @foreach ($brand as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-outline mb-4">
                                <select id="color_id " name="color" class="form-select">
                                    <label class="form-label" for="brand">color </label>
                                    <option value="">Select color</option>
                                    @foreach ($lookups as $color)
                                        @if ($color->type == 'color')
                                            <option value="{{ $color->id }}">{{ $color->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-outline mb-4">
                                <select id="color_id " name="tags" class="form-select">
                                    <label class="form-label" for="tags">color </label>
                                    <option value="">Select tags</option>
                                    @foreach ($lookups as $tag)
                                        @if ($tag->type == 'tag')
                                            <option value="{{ $tag->code }}">{{ $tag->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-outline mb-4">
                                <select id="color_id " name="size" class="form-select">
                                    <label class="form-label" for="tags">size </label>
                                    <option value="">Select Size</option>
                                    @foreach ($lookups as $size)
                                        @if ($size->type == 'size')
                                            <option value="{{ $size->code }}">{{ $size->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="newsize">
                                <button type="button" onclick="addnewsize()" id="newsize" class="btn btn-primary">Add New
                                    Size</button>

                            </div>
                            <div class="form-outline mb-4">
                                <select id="color_id " name="usertags" class="form-select">
                                    <label class="form-label" for="tags">User </label>
                                    <option value="">Select tags For user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="button" onclick="addNewTag()" class="btn btn-primary">Add New
                                    tag</button>

                                <div class="newtag">

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <button class="btn btn-primary mt-4">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </form>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            /*------------------------------------------
            --------------------------------------------
            Country Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#country-dropdown').on('change', function() {
                var idCountry = this.value;
                $("#state-dropdown").html('');
                $.ajax({
                    url: "{{ url('api/fetch-states') }}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#state-dropdown').html(
                            '<option value="">-- Select State --</option>');
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');
                    }
                });
            });

            /*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#state-dropdown').on('change', function() {
                var idState = this.value;
                $("#city-dropdown").html('');
                $.ajax({
                    url: "{{ url('api/fetch-cities') }}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');
                        $.each(res.cities, function(key, value) {
                            $("#city-dropdown").append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });



        // function checkTagOption(value) {
        //     if (value === "") {
        //         document.getElementById("tag_input").style.display = "block";

        //     } else {
        //         document.getElementById("selected_tag").value = value;
        //     }
        // }

        function addNewTag() {
            $(".newtag").html('<input type="text" name="newtags" placeholder="Enter New Tag" id="tag_input" value="">')
            var a = $("#tag_input").attr('name');

        }

        function addnewsize() {
            $(".newsize").html('<input type="text" name="newsize" placeholder="Enter New size" id="tag_input" value="">')


        }
    </script>
@endsection
