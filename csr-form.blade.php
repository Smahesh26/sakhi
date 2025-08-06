@extends('frontend.frontend-page-master')

@section('site-title', 'CSR Submission Form')

@section('page-title', 'CSR Submission')

@section('content')
<div class="container padding-100">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('frontend.csr.submit') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label>Company Name *</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Contact Person *</label>
                    <input type="text" name="contact_person" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label>Project Details *</label>
                    <textarea name="project_details" rows="5" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit CSR Proposal</button>
            </form>
        </div>
    </div>
</div>
@endsection
