<!doctype html>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Landing Page</title>

@if(!empty($fbPixelId))
<script>
!(function(f,b,e,v,n,t,s){
if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s);
})(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '{{ $fbPixelId }}');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"
/></noscript>
@endif

<style>
/* Reset */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #f0f4f8, #d9e2ec);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

.container {
    max-width: 400px;
    width: 100%;
}

.card {
    background: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

h1 {
    font-size: 1.8rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

p {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 1.5rem;
}

label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

input {
    width: 100%;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 1rem;
    transition: border 0.2s;
}

input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
}

button {
    width: 100%;
    padding: 0.75rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

button:hover {
    background-color: #2563eb;
}
</style>

<body>
<div class="container">
    <div class="card">
        <h1>Get our offer</h1>
        <p>Enter your details and we will contact you.</p>

        <form method="POST" action="https://supernormally-unturnable-shavonne.ngrok-free.dev/submit">
            @csrf
            @method("post")
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}">

            <label for="email">Email *</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}">

            <button type="submit">Send</button>
        </form>
    </div>
</div>

<!-- Optional client-side FB event -->
<script>
// Example: fire custom event after submission on thank-you page
// fbq('track','Lead');
</script>
</body>
</html>
