@extends('user.layout.default')
@section('section')
    <section class="checkout-section spad">
        <div class="container">
            <form action="{{ route('placeorder') }}" method="POST" class="checkout-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="checkout-content">


                            <?php $login = Auth::user(); ?>
                            @if ($login)
                                <a href="#" class="content-btn">{{ Auth::user()->name }}</a>
                            @else
                                <a href="#" class="content-btn">Click Here To Login</a>
                            @endif
                        </div>
                        <h4>Biiling Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="fir">First Name<span>*</span></label>
                                <input type="text" name="fname" id="fir">
                            </div>
                            <div class="col-lg-6">
                                <label for="last">Last Name<span>*</span></label>
                                <input type="text"name="lname" id="last">
                            </div>
                            <div class="col-lg-12">
                                <label for="cun">Country<span>*</span></label>
                                <input type="text"name="country" id="cun">
                            </div>



                            <div class="col-lg-12">
                                <label for="street">Street Address<span>*</span></label>
                                <input type="text"name="address" id="address" class="street-first">

                            </div>
                            <div class="col-lg-12">
                                <label for="zip">Postcode / ZIP (optional)</label>
                                <input type="text"name="postcode" id="zip">
                            </div>
                            <div class="col-lg-12">
                                <label for="town">Town / City<span>*</span></label>
                                <input type="text"name="city" id="town">
                            </div>
                            <div class="col-lg-6">
                                <label for="email">Email Address<span>*</span></label>
                                <input type="text"name="email" id="email">
                            </div>
                            <div class="col-lg-6">
                                <label for="phone">Phone<span>*</span></label>
                                <input type="phone"name="phone" id="phone">
                            </div>
                            <div class="col-lg-12">
                                <div class="create-item">
                                    <label for="acc-create">
                                        Create an account?
                                        <input type="checkbox" id="acc-create">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout-content">
                            <input type="text" placeholder="Enter Your Coupon Code">
                        </div>
                        <div class="place-order">
                            <h4>Your Order</h4>
                            <div class="order-total">
                                <ul class="order-table">
                                    <li>Product <span>Total</span></li>
                                    @foreach ($checkouts as $total)
                                        <li class="fw-normal">{{ $total->product_name }} x {{ $total->quantity }}
                                            <span>{{ $total->finalprice }}</span>
                                        </li>


                                        <button class="btn btn-danger"> <a
                                                href="{{ route('deletecheckoutproduct', $total->id) }}"
                                                class="text-light">Remove</a>
                                        </button>
                                    @endforeach
                                    </td>
                                    <input type="hidden" class="subtotals" name="subtotals" value="{{ $subtotal }}">

                                    <li class="subtotal">Subtotal <span id="subtotal">{{ $subtotal }}</span>
                                    <li class="gst">GST% <span id="gst">{{ $gst }}</span>
                                    <li class="discount"> <span id="discount"></span></li>
                                    <li class="final">Final Amount <span id="final">{{ $finalamount }}</span>

                                </ul>


                                <div class="discount-coupon">
                                    <h6>Discount Codes</h6>

                                    <input type="text" class="coupon_code" id="coupon_code" name="coupon_code"
                                        placeholder="Enter your codes">


                                    <button type="button" onclick="applycouponcode()"
                                        class="site-btn coupon-btn">Apply</button>




                                </div>

                                <div class="payment-check">
                                    <div class="pc-item">
                                        <label for="pc-check">
                                            Cheque Payment
                                            <input type="checkbox" id="pc-check">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pc-item">
                                        <label for="pc-paypal">
                                            Paypal
                                            <input type="checkbox" id="pc-paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="order-btn">
                                    <button type="submit" class="site-btn place-btn">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function applycouponcode() {
            var couponCode = $('#coupon_code').val();
            var subtotals = $('.subtotals').val();

            if (couponCode != "") {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('applyCoupon') }}',
                    data: {
                        coupon_code: couponCode,
                        subtotal: subtotals,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            var discount = response.discount;
                            var finalAmount = response.final_amount;

                            Swal.fire(
                                'Success!',
                                'Your Coupon Has Been Applied',
                                'success'
                            );

                            $('#subtotal').text(subtotals);
                            $('#discount').text(discount);
                            $('#final').text(finalAmount);
                            $(".discount").append('discount');
                            // Show the discount field
                            // $('.discount-coupon').addClass('discount');

                            $('#coupon_code_msg').html('Coupon applied successfully.');
                        } else {
                            Swal.fire(
                                'Error!',
                                'Your Coupon Is Wrong',
                                'error'
                            );

                            $('#coupon_code_msg').html('Failed to apply the coupon code.');
                        }
                    },
                    error: function() {
                        $('#coupon_code_msg').html('An error occurred during the AJAX request.');
                    }
                });
            } else {
                Swal.fire(
                    'Alert!',
                    'Please enter your coupon code',
                    'info'
                );
            }
        }
    </script>
@endsection
