<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Redirecting...</title>
</head>
<body onload="document.forms[0].submit()">
  <form method="POST" action="{{ $paymentUrl }}">
   
     @foreach($payload as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <noscript>
      <button type="submit">Click here if not redirected</button>
    </noscript>
  </form>
</body>
</html>
