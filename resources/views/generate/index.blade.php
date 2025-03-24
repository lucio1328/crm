@extends('layouts.master')

@section("content")
<div class="container mt-5">
    <h2 class="mb-4">Générer des données aléatoires</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @php
            $tables = [
                'leads', 'comments', 'mails', 'tasks', 'projects', 
                'absences', 'contacts', 'invoice_lines', 'appointments', 
                'payements', 'invoices', 'offers', 'clients'
            ];
        @endphp

        @foreach($tables as $table)
        <div class="col-md-6 mb-4">
            <!-- Formulaire pour chaque table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ ucfirst(str_replace('_', ' ', $table)) }}</h4>
                    <form action="{{ route('generate.generate') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="{{ $table }}" class="form-label text-capitalize">
                                Nombre de {{ str_replace('_', ' ', $table) }} à générer :
                            </label>
                            <input type="number" class="form-control" id="{{ $table }}" name="tables[{{ $table }}]" min="0" value="10">
                        </div>
                        
                        <button type="submit" name="generate_{{ $table }}" class="btn btn-success mt-3">
                            <i class="fa fa-database"></i> Générer {{ ucfirst($table) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
