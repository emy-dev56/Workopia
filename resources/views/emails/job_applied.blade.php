<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>New job applied email {{ $application->full_name }}</p>
    <p>Job Title: {{ $job->title }}</p>
    <p>Job Description: {{ $job->description }}</p>
    <p>Details: </p>
    <p>Full name: {{ $application->full_name }}</p>
    <p>Contact phone: {{ $application->contact_phone }}</p>
    <p>Contact email: {{ $application->contact_email }}</p>
    <p>Message: {{ $application->message }}</p>
    <p>Location: {{ $application->location }}</p>
    <p>Login Here <a href="{{ route('login') }}">Login</a></p>
</body>

</html>
