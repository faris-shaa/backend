<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <form id="redirect-form" action="{{$url}}" method="POST" style="display: none;">
        @csrf
        @foreach($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <script type="text/javascript">
        document.getElementById('redirect-form').submit();
    </script>
</body>
</html>
