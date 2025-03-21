<x-layout>
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <h2>Register</h2>

        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label for="email">Email address:</label>
        <input type="email" name="email" value="{{ old('name') }}" required>

        <label for="password">Enter password</label>
        <input type="password" name="password" required>

        <label for="password_confirmation">Confirm password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit" class="btn">Register</button>

        <!-- validation errors -->
    </form>
</x-layout>
