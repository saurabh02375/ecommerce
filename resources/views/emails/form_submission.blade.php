@extends('user.layout.default')
<form method="POST" action="{{ route('submit') }}"
    enctype="multipart/form-data">
    @csrf
<section class="vh-100" style="background-color: #2779e2;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-9">

                <h1 class="text-white mb-4">Apply for a job</h1>

                <div class="card" style="border-radius: 15px;">
                    <div class="card-body">

                        <div class="row align-items-center pt-4 pb-3">
                            <div class="col-md-3 ps-5">

                                <h6 class="mb-0">Full name</h6>

                            </div>
                            <div class="col-md-9 pe-5">

                                <input type="text" name="name" class="form-control form-control-lg" />

                            </div>
                        </div>



                        <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">

                                <h6 class="mb-0">Email address</h6>

                            </div>
                            <div class="col-md-9 pe-5">

                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="example@example.com" />

                            </div>
                        </div>

                        <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">

                                <h6 class="mb-0">Phone</h6>

                            </div>
                            <div class="col-md-9 pe-5">

                                <input type="text" name="phone" class="form-control form-control-lg"
                                    placeholder="Please Enter Your Mobile number" />

                            </div>
                        </div>

                        <hr class="mx-n3">

                        <div class="px-5 py-4">
                            <button type="submit" class="btn btn-primary btn-lg">Send application</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
