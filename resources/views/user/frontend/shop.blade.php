@extends('user.layout.default')
@section('section')
    <form action="{{ route('viewshop') }}" method="GET" class="mb-3" enctype="multipart/form-data">
        @csrf
        <section class="product-shop spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">

                        <div class="filter-widget">
                            <h4 class="fw-title">Brand</h4>
                            @foreach ($brands as $brand)
                                @if ($brand->is_active == 1)
                                    <div class="fw-brand-check">
                                        <div class="bc-item">
                                            <input type="checkbox" name="brand[]" class="trail" value="{{ $brand->id }}"
                                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                                            <label for="brand-{{ $brand->id }}">{{ $brand->name }}</label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div>
                            <div class="filter-widget">
                                <h4 class="fw-title">Price</h4>
                                <div class="filter-range-wrap">
                                    <div class="range-slider">
                                        <div class="price-input">
                                            <input type="text" name="minamount" id="minamount"
                                                value="{{ request('minamount') }}">
                                            <input type="text" name="maxamount" id="maxamount"
                                                value="{{ request('maxamount') }}">
                                        </div>
                                    </div>
                                    @php
                                        $max = $pricerange->max_price;
                                        $min = $pricerange->min_price;
                                    @endphp
                                    <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                        data-min="{{ $min }}" data-max="{{ $max }}">
                                        <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="filter-widget">
                                <h4 class="fw-title">Color</h4>
                                @foreach ($Lookups as $color)
                                    @if ($color->type == 'color')
                                        <div class="fw-brand-check">
                                            <div class="bc-item">

                                                <input type="checkbox" name="searchcolor[]" value="{{ $color->id }}"
                                                    {{ in_array($color->id, request('searchcolor', [])) ? 'checked ' : '' }}>

                                                <label for="color-{{ $color->code }}">{{ $color->code }}</label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="filter-widget">
                                <h4 class="fw-title">Size</h4>
                                <div class="fw-size-choose">
                                    <div class="sc-item">
                                        @php
                                            $sizeSearch = isset($_GET['size']) ? $_GET['size'] : '';
                                            
                                        @endphp
                                    </div>
                                    @foreach ($Lookups as $size)
                                        @if ($size->type == 'size')
                                            <input type="radio" name="size" value="{{ $size->id }}"
                                                id="size{{ $size->id }}"
                                                {{ $sizeSearch == $size->id ? 'checked' : '' }}>
                                            <label for="size{{ $size->id }}">{{ $size->code }}</label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="filter-widget">
                                <h4 class="fw-title">Tags</h4>
                                <div class="fw-tags">
                                    <div class="bc-item">
                                        @foreach ($Lookups as $view)
                                            @if ($view->type == 'tag')
                                                <input type="checkbox" name="tag[]" class="trail"
                                                    value="{{ $view->id }}"
                                                    {{ in_array($view->id, request('tag', [])) ? 'checked' : '' }}>
                                                <label for="brand-{{ $view->id }}">{{ $view->code }}</label>
                                            @endif
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('viewshop') }}" class="btn btn-danger">Clear</a>
                        </div>
    </form>
    </div>

    <div class="col-lg-9 order-1 order-lg-2">
        <div class="product-show-option">
            <div class="row">
                <div class="col-lg-7 col-md-7">
                    <div class="select-option">
                        <input type="text" value="" name="sortType" class="sort-input">
                        <select class="sorting" name="asc">
                            <option value="ASC">Ascending</option>
                            <option value="DESC">Descending</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    @foreach ($products as $product)
                        @if ($product->is_active == 1)
                            <div class="col-lg-4 col-sm-6 col-12 col-xl-4 cols-md-4" style="height:400px;">
                                <div class="product-item h-75">
                                    <div class="pi-pic h-100">
                                        <img class="h-75"
                                            src="{{ Config('constants.catimage_IMAGE_ROOT_PATH') }}/{{ $product->image }}"
                                            alt="">
                                        <div class="sale pp-sale">Sale</div>

                                        <?php $likeData = customlike($product->id); ?>

                                        <div class="icon">
                                            <?php $likeData = customlike($product->id); ?>

                                            @if (!empty($likeData) && $likeData->is_likes == 1)
                                                <i onclick="likeBtn({{ $product->id }})"id="heartIcon"
                                                    class="fa-sharp fa-regular fa-heart heartIcon{{ $product->id }}"
                                                    style="color: #fa0000;" data-product-id="fa-solid fa-heart"></i>
                                            @else
                                                <i onclick="likeBtn({{ $product->id }})"id="heartIcon"
                                                    class="fa-sharp fa-regular fa-heart heartIcon{{ $product->id }}"
                                                    style="color: #1d1b1c;"></i>
                                            @endif
                                        </div>
                                        <ul>
                                            <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a>
                                            </li>

                                            <li class="quick-view">

                                                <a id="addToCart" product-id="{{ $product->id ?? '' }}"
                                                    style="cursor: pointer;">
                                                    Add
                                                    to Cart</a>

                                            </li>
                                            <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="pi-text">
                                        <div class="catagory-name">{{ $product->brand_id }}</div>
                                        <a href="#">
                                            <h5>{{ $product->name }}</h5>
                                        </a>
                                        <div class="product-price">
                                            {{ $product->price }}
                                            <span>$35.00</span>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @include('pagination.default', ['products' => $products])
                </section>

                <div class="partner-logo">
                    <div class="container">
                        <div class="logo-carousel owl-carousel">
                            <div class="logo-item">
                                <div class="tablecell-inner">
                                    <img src="img/logo-carousel/logo-1.png" alt="">
                                </div>
                            </div>
                            <div class="logo-item">
                                <div class="tablecell-inner">
                                    <img src="img/logo-carousel/logo-2.png" alt="">
                                </div>
                            </div>
                            <div class="logo-item">
                                <div class="tablecell-inner">
                                    <img src="img/logo-carousel/logo-3.png" alt="">
                                </div>
                            </div>
                            <div class="logo-item">
                                <div class="tablecell-inner">
                                    <img src="img/logo-carousel/logo-4.png" alt="">
                                </div>
                            </div>
                            <div class="logo-item">
                                <div class="tablecell-inner">
                                    <img src="img/logo-carousel/logo-5.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


                <script>
                    function likeBtn(productId) {
                        var a = $.ajax({
                            url: "{{ route('storelike') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                product_id: productId,

                            },
                            success: function(response) {
                                if (response.status == true) {


                                    if (response.is_likes == 1) {

                                        $('.heartIcon' + productId).css("color", "red");
                                    } else {

                                        $('.heartIcon' + productId).css("color", "white");
                                    }
                                } else {
                                    console.log('Error occurred while toggling like.');
                                }
                            },
                            error: function() {
                                console.log('AJAX request failed.');
                            }
                        });
                        console.log(a);
                    }

                    $("body").on("click", "#addToCart", function() {
                        var productid = $(this).attr('product-id');
                        $.ajax({
                            url: '/addtocart/' + productid,
                            method: 'GET',
                            success: function(response) {
                                if (response.status === false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.message,

                                    });
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Your Cart has been Added',
                                        showConfirmButton: false,
                                        timer: 10000
                                    })
                                    setTimeout(() => {
                                        location.reload()
                                    }, 2000);
                                }
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'An error occurred.',
                                });
                                setTimeout(() => {
                                    location.reload();
                                    S
                                }, 2000);
                            }
                        });
                    });



                    // var rangeSlider = $(".price-range"),
                    //     minamount = $("#minamount"),
                    //     maxamount = $("#maxamount"),
                    //     minPrice = rangeSlider.data('min'),
                    //     maxPrice = rangeSlider.data('max');

                    // rangeSlider.slider({
                    //     range: true,
                    //     min: minPrice,
                    //     max: maxPrice,
                    //     values: [minPrice, maxPrice],
                    //     slide: function(event, ui) {
                    //         minamount.val('$' + ui.values[0]);
                    //         // maxamount.val('$' + ui.values[1]);
                    //         maxamount.val('$' + ui.values[1]);

                    //     }
                    // });
                    // if (minamount.val() && maxamount.val()) {
                    //     var minValue = minamount.val().replace('$', '');
                    //     var maxValue = maxamount.val().replace('$', '');

                    //     rangeSlider.slider("values", [minValue, maxValue]);
                    // } else {

                    //     minamount.val('$' + rangeSlider.slider("values", 0));
                    //     maxamount.val('$' + rangeSlider.slider("values", 1));
                    // }
                </script>
            @endsection
