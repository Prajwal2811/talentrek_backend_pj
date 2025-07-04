<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body>
    <h2>Hello {{ $user->company_name }},</h2>

    @if($userType === 'company')
        <p>Thank you for registering as a <strong>Recruiter</strong>.</p>
        <p>Your company and recruiter profile have been successfully created.</p>
    @elseif($userType === 'jobseeker')
        <p>Welcome to our job portal as a <strong>Jobseeker</strong>.</p>
        <p>Your profile has been created. Start exploring job opportunities!</p>
    @endif

    <br>
    <p>Regards,<br>Support Team</p>
</body>
</html>
