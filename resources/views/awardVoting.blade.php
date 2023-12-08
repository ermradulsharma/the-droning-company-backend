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
	<body onLoad="resize();">

		<div class="container">
			{{-- <h4 class="text-center mb-2">If you win our sweepstakes, please tell us where to send your prizes:</h4> --}}
			<h4 class="text-center mb-2">Thank you for voting. Your Awards ballot has been received.</h4>
			<hr>
			<form method="post" class="form-horizontal" enctype="multipart/form-data">
				@csrf

				@foreach($categories as $category)
				<div class="card mb-3">
					<div class="card-header">{{ $category->title }}</div>
					<div class="card-body">

						@foreach($category->companies as $company)
						<div class="form-check">
							<input class="form-check-input" type="radio" value="{{ $company->id }}" id="award_cat_{{ $category->id }}_{{ $company->id }}" name="award_vote[{{ $category->id }}]">
							<label class="form-check-label" for="award_cat_{{ $category->id }}_{{ $company->id }}">
								{{ $company->company_detail->title }} <a href="{{ $company->company_detail->url }}" target="_blank">[view]</a>
							</label>
						</div>
						@endforeach
					</div>
				</div>
				@endforeach

				<button type="submit" class="btn btn-warning my-3"><h5 class="mb-0">Submit</h5></button>
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
