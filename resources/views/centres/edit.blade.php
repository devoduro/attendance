@extends('layouts.app')

@section('title', 'Edit Centre')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Centre</h1>
        <a href="{{ route('centres.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Centres
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Centre Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('centres.update', $centre->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $centre->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="location" class="col-sm-2 col-form-label">Location <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $centre->location) }}" required>
                        @error('location')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $centre->address) }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="contact_number" class="col-sm-2 col-form-label">Contact Number</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $centre->contact_number) }}">
                        @error('contact_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $centre->email) }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="is_active" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $centre->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Centre
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
