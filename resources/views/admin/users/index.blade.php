<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Users</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p><a href="{{ route('admin.users.create') }}">Create User</a></p>

    <ul>
        @forelse ($users as $user)
            <li>{{ $user->name }} ({{ $user->username }})</li>
        @empty
            <li>No users found.</li>
        @endforelse
    </ul>

    <p><a href="{{ route('admin.dashboard') }}">Back to Dashboard</a></p>
</body>
</html>
