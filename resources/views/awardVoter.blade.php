<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="https://www.thedroningcompany.com">
			<!-- Bootstrap CSS -->
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

			<title>The Droning Award Voting</title>
			</head>
		<body onLoad="resize();" onresize="resize();">

			<div class="container">

				@if(session()->has('error'))
				<div class="alert alert-danger">
					{{ session()->get('error') }}
				</div>
				@endif

				<h4 class="text-center mb-2">If you win our sweepstakes, please tell us where to send your prizes:</h4>
				<hr>
				<form method="post" action="{{ route('award.voting') }}" class="form-horizontal" enctype="multipart/form-data">
					@csrf
					<div class="row g-2">
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" id="name" name="name" placeholder="">
								<label for="floatingInputGrid">Name <em class="text-danger small">{{ $errors->has('name') ? $errors->first('name') : '' }}</em></label>
							</div>
						</div>
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" id="Address" name="address" placeholder="">
								<label for="floatingInputGrid">Street Address <em class="text-danger small">{{ $errors->has('address') ? $errors->first('address') : '' }}</em></label>
							</div>
						</div>
					</div>


					<div class="row g-2">
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('suite') ? 'is-invalid' : '' }}" value="{{ old('suite') }}" id="suite" name="suite" placeholder="">
								<label for="floatingInputGrid">Apt/Suite # <em class="text-danger small">{{ $errors->has('suite') ? $errors->first('suite') : '' }}</em></label>
							</div>
						</div>
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" value="{{ old('city') }}" id="city" name="city" placeholder="">
								<label for="floatingInputGrid">City <em class="text-danger small">{{ $errors->has('city') ? $errors->first('city') : '' }}</em></label>
							</div>
						</div>

						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" value="{{ old('state') }}" id="state" name="state" placeholder="">
								<label for="floatingInputGrid">State <em class="text-danger small">{{ $errors->has('state') ? $errors->first('state') : '' }}</em></label>
							</div>
						</div>

						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}" value="{{ old('zip_code') }}" id="zip_code" name="zip_code" placeholder="">
								<label for="floatingInputGrid">Zip Code <em class="text-danger small">{{ $errors->has('zip_code') ? $errors->first('zip_code') : '' }}</em></label>
							</div>
						</div>
					</div>

					<div class="row g-2">
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" id="phone" name="phone" placeholder="">
								<label for="phone">Phone <em class="text-danger small">{{ $errors->has('phone') ? $errors->first('phone') : '' }}</em></label>
							</div>
						</div>
						<div class="col-md mb-2">
							<div class="form-floating">
								<input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="">
								<label for="email">Email address <em class="text-danger small">{{ $errors->has('email') ? $errors->first('email') : '' }}</em></label>
							</div>
						</div>
					</div>

					<div class="form-floating mb-2">
						<input type="url" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="">
						<label for="instagram">Instagram</label>
					</div>

					<div class="form-floating mb-2">
						<input type="url" class="form-control" id="facebook" name="facebook" value="{{ old('facebook') }}" placeholder="">
						<label for="facebook">Facebook</label>
					</div>

					<div class="form-floating mb-2">
						<input type="url" class="form-control" id="youtube" name="youtube" value="{{ old('youtube') }}" placeholder="">
						<label for="youtube">Youtube</label>
					</div>

					<div class="form-floating mb-2">
						<input type="url" class="form-control" id="webpage" name="webpage" value="{{ old('webpage') }}" placeholder="">
						<label for="webpage">Webpage</label>
					</div>

					<center class="mt-4">
						<h6>No purchase necessary to enter.</h6>
						<h6>Only available to entrants within the USA.</h6>
						<h6>By entering this sweepstakes, you agree to receive promotional emails from The Droning Company or approved sponsors.</h6>
						<button type="submit" class="btn btn-warning my-3"><h5 class="mb-0">CLICK HERE TO VOTE IN THE 2023 DRONING AWARDS AND ENTER THE SWEEPSTAKES</h5></button>
						<h6>Increase your chances of winning.</h6>
						<p>Remember, a vote in each category counts as a separate entry</p>
						<p><a href="https://thedroningcompany.com/page/sweepstakes-rules" target="_blank">(Sweepstake rules and regulations link)</a></p>
					</center>
				</form>
			</div>

			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
			<script>
				function resize() {
					var height = document.getElementsByTagName("html")[0].scrollHeight;
					window.parent.postMessage(height, "https://www.thedroningcompany.com");
				}
			</script>
		</body>
		</html>
