<html>
<head>
    @include('inc.head')

</head>
<body>
<script src="/scripts/utils.js"></script>

@include('inc.navbar')

<div class="container">
    @yield('content')
</div>

<script src="/scripts/materialize.min.js"></script>
<script src="/scripts/analytics.js"></script>
</body>
</html>