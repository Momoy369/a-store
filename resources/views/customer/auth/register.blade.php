<h2>Register Customer</h2>
<form method="POST" action="{{ route('customer.register') }}">
    @csrf
    <input type="text" name="name" placeholder="Nama" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
