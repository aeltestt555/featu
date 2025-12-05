<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thank You</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #f0f4f8, #d9e2ec);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem;
    color: #1f2937;
}

.thank-card {
    background: white;
    padding: 3rem 2rem;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.thank-card h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #3b82f6;
}

.thank-card p {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 1.5rem;
}

</style>
</head>
<body>
<div class="thank-card">
    <h1>Thank You!</h1>
    <p>We received your details. We'll contact you soon.</p>
</div>

@if(env('FB_PIXEL_ID'))
<script>
if(window.fbq) {
    fbq('track', 'Lead');
}
</script>
@endif
</body>
</html>
