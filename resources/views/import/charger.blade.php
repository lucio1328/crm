@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Importer un fichier CSV</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">Selectionnez un fichier CSV :</label>
            <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">
            <i class="fa fa-upload"></i> Importer
        </button>
    </form>
</div>
@endsection
