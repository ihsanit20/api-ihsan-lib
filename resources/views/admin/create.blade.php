@extends('layouts.app')

@section('title', 'Add New Client')

@section('content')
<div class="container">
    <h1>Add New Client</h1>

    <!-- Form for Creating a New Client -->
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Client Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="domain">Domain:</label>
            <input type="text" name="domain" id="domain" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="database">Database Name:</label>
            <input type="text" name="database" id="database" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Database Username:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Database Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Client</button>
    </form>
</div>
@endsection
