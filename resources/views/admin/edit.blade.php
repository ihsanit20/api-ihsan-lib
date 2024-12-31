@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="container">
    <h1>Edit Client</h1>

    <!-- Form for Editing an Existing Client -->
    <form action="{{ route('clients.update', $client->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Client Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $client->name }}" required>
        </div>
        <div class="form-group">
            <label for="domain">Domain:</label>
            <input type="text" name="domain" id="domain" class="form-control" value="{{ $client->domain }}" required>
        </div>
        <div class="form-group">
            <label for="database">Database Name:</label>
            <input type="text" name="database" id="database" class="form-control" value="{{ $client->database }}" required>
        </div>
        <div class="form-group">
            <label for="username">Database Username:</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ $client->username }}" required>
        </div>
        <div class="form-group">
            <label for="password">Database Password:</label>
            <input type="password" name="password" id="password" class="form-control" value="{{ $client->password }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Update Client</button>
    </form>
</div>
@endsection
