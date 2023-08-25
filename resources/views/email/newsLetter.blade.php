<!DOCTYPE html>
<html>
<head>
    <title>SMSURWAY NewsFeed</title>
</head>
<body>
    <h1>
        Hi, 
        {{-- {{ $user->name }} --}}
    </h1>
        {!! $news->msg !!}
        Thanks,<br>
        Team <a href="https://smsurway.com.ng">{{ config('app.name') }}</a>
</body>
</html>