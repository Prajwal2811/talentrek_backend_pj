<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body onload="document.forms[0].submit()">
    <form method="POST" action="https://securepayments.neoleap.com.sa/pg/payment/hosted.htm">
        <input type="hidden" name="id" value="{{ $tranportalId }}">
        <input type="hidden" name="trandata" value="{{ $trandata }}">
        <input type="hidden" name="responseURL" value="{{ route('payment.callback') }}">
        <input type="hidden" name="errorURL" value="{{ route('payment.error') }}">
        <noscript>
            <button type="submit">Click here if not redirected</button>
        </noscript>
    </form>
</body>
</html>
