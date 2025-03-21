<x-layout>
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <h2>Log In</h2>

        <label for="email">Email address:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Enter password:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Log in</button>

<!--        validation errors -->
    </form>
</x-layout>
