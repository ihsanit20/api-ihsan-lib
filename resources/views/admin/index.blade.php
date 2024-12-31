@extends('layouts.app')

@section('title', 'Client List')

@section('content')
<div class="container">
    <h1>Client List</h1>

    <!-- Success/Error Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Add New Client Button -->
    <a href="{{ route('clients.store') }}" class="btn btn-primary mb-3">Add New Client</a>

    <!-- Client List -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Domain</th>
                <th>Database</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->domain }}</td>
                <td>{{ $client->database }}</td>
                <td>
                    <a href="{{ route('clients.update', $client->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <form action="{{ route('clients.migrate', $client->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Migrate</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
