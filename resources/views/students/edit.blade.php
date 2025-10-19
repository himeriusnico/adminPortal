@extends('students.layout')

@section('student-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Edit Data Mahasiswa</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Mahasiswa</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('students.show', $student->id) }}">{{ $student->user->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Form edit mahasiswa akan segera diimplementasikan.
            </div>
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Detail
            </a>
        </div>
    </div>
@endsection
