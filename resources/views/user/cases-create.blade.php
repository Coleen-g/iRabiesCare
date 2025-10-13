@extends('user.layout')

@section('title','Report Case')

@section('content')
    <h1>Report Case</h1>
    <div class="card">
        <form method="POST" action="{{ route('user.cases.store') }}">
            @csrf
            <div>
                <label>Patient</label>
                <div>{{ $patient->name }}</div>
            </div>
            <div>
                <label>Date Reported</label>
                <input name="date_reported" type="date" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Status</label>
                <input name="status" value="open" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" style="width:100%;padding:.5rem;"></textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Submit</button>
                <a href="{{ route('user.cases') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
