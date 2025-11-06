@extends('health_staff.layout')

@section('title', 'Edit Vaccination Schedule')

@section('content')
<div class="container mt-4">
    <h2>Edit Vaccination Schedule for {{ $user->name }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('health_staff.vaccination-schedule.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="schedule_1" class="form-label">Next Vaccination #1</label>
            <input type="date" class="form-control" id="schedule_1" name="schedule_1" value="{{ $schedule->schedule_1 }}">
        </div>
        <div class="mb-3">
            <label for="schedule_2" class="form-label">Next Vaccination #2</label>
            <input type="date" class="form-control" id="schedule_2" name="schedule_2" value="{{ $schedule->schedule_2 }}">
        </div>
        <div class="mb-3">
            <label for="schedule_3" class="form-label">Next Vaccination #3</label>
            <input type="date" class="form-control" id="schedule_3" name="schedule_3" value="{{ $schedule->schedule_3 }}">
        </div>
        <button type="submit" class="btn btn-success">Save Schedule</button>
    </form>
</div>
@endsection
