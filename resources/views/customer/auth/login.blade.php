<h2>Login Customer</h2>
<form method="POST" action="{{ route('customer.login') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
