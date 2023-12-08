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
				<div class="jumbotron text-center">
					<h1 class="display-6">Dear {{ $voter->name }}, Your vote has been submitted.</h1>
					<hr class="my-4">
					<p class="lead">Please wait! we will notify you with the voting results.</p>
				</div>
			</div>

			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
			<script>
				function resize() {
					var height = 300; //document.getElementsByTagName("html")[0].scrollHeight;
					window.parent.postMessage(height, "https://www.thedroningcompany.com");
				}
			</script>
		</body>
		</html>