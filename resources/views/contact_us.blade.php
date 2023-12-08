<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="https://www.thedroningcompany.com">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Contact Us</title>
</head>

<body onLoad="resize();" onresize="resize();">
    <div class="container">
        @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif
        <form method="post" action="{{ route('contact-us') }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" id="first_name" name="first_name" placeholder="">
                        <label for="floatingInputGrid">First Name <em class="text-danger small">{{ $errors->has('first_name') ? $errors->first('first_name') : '' }}</em></label>
                    </div>
                </div>
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" value="{{ old('last_name') }}" id="last_name" name="last_name" placeholder="">
                        <label for="floatingInputGrid">Last Name <em class="text-danger small">{{ $errors->has('last_name') ? $errors->first('last_name') : '' }}</em></label>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" value="{{ old('company') }}" id="company" name="company" placeholder="">
                        <label for="floatingInputGrid">Company <em class="text-danger small">{{ $errors->has('company') ? $errors->first('company') : '' }}</em></label>
                    </div>
                </div>
            </div>


            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" id="address" name="address" placeholder="">
                        <label for="floatingInputGrid">Address <em class="text-danger small">{{ $errors->has('address') ? $errors->first('address') : '' }}</em></label>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <div class="form-floating">
                                <select class="form-select" name="phone_type" id="phone_type" aria-label="Phone Type">
                                    <option value="work" selected>Work</option>
                                    <option value="mobile">Mobile</option>
                                </select>
                                <label for="floatingSelect">Type <em class="text-danger small">{{ $errors->has('phone_type') ? $errors->first('phone_type') : '' }}</em></label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" id="phone" name="phone" placeholder="">
                                <label for="phone">Phone <em class="text-danger small">{{ $errors->has('phone') ? $errors->first('phone') : '' }}</em></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="">
                        <label for="email">Email address <em class="text-danger small">{{ $errors->has('email') ? $errors->first('email') : '' }}</em></label>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md mb-2">
                    <div class="form-floating">
                        <textarea class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}" value="{{ old('comment') }}" id="comment" name="comment" placeholder="" style="height: 100px"></textarea>
                        <label for="comment">Comment <em class="text-danger small">{{ $errors->has('comment') ? $errors->first('comment') : '' }}</em></label>
                    </div>
                </div>
            </div>

            <center class="mt-4">
                <button type="submit" class="btn btn-warning my-3">
                    <h5 class="mb-0">Submit</h5>
                </button>
            </center>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function resize() {
            var height = document.getElementsByTagName("html")[0].scrollHeight;
            window.parent.postMessage(height, "https://www.thedroningcompany.com");
        }
    </script>
</body>
</html>
