<!DOCTYPE html>
<html>
<head>
    <title>SMSURWAY Credit Request</title>
</head>
<body>
    <h1>
        Hi Admin,
    </h1>
        New Credit Request Received. <br>

        {!! $user !!}
            Has sent a request for new credit of ₦
        {!! $data !!}
        <br>
        <a type="button" href="{{ route('managetransactions')}}" class="btn btn-primary">
            View Request
        </a>
        <br>
        Thanks,<br>
        Team <a href="https://smsurway.com.ng">{{ config('app.name') }}</a>
</body>
</html>