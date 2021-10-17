<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
		
		<link href="/css/bootstrap.min.css" rel="stylesheet">
  </head>
    <body>
		<div class="container pt-2">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<a class="navbar-brand" href="/">Navbar</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Lang - {{App::getLocale()}}
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								@foreach(config('langs') as $language)
								<a class="dropdown-item" href="/lang/{{$language}}">{{$language}}</a>
								@endforeach
							</div>
						</li>
						
					</ul>
				</div>
			</nav>
			@yield('content')
		</div>
		<script src="/js/jquery.js"></script>
		<script src="/js/bootstrap.bundle.min.js"></script>
		<script src="/js/ajax.js"></script>
		
		@yield('script')
	</body>
</html>
