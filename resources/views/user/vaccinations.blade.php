@extends('user.layout')

@section('title','My Vaccinations')

@section('content')
    <style>
        .vaccination-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .vaccination-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
        }

        .vaccination-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .vaccination-item:last-child {
            border-bottom: none;
        }

        .vaccine-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .vaccine-icon {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.6rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vaccine-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .vaccine-date {
            color: #475569;
            font-size: 0.875rem;
        }
        .vaccine-notes {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .vaccine-dose {
            background: #eff6ff;
            color: #1e40af;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .page-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }
        .page-header .icon {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.6rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .muted {
            color: #6b7280;
        }
    </style>

    <div class="card vaccination-card">
        <div class="page-header">
            <div class="icon">
                <i class="fas fa-syringe"></i>
            </div>
            <h2>My Vaccinations</h2>
        </div>

        {{-- Vaccination Schedule Table --}}
        @php
            $schedule = \App\Models\VaccinationSchedule::where('user_id', auth()->id())->first();
        @endphp
        <div style="background:#ffffff;border-radius:12px;padding:1.2rem;margin-bottom:1.5rem;border:1px solid #e5e7eb;">
            <div style="margin-bottom:1rem;">
                <strong style="color:#065f46;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-calendar-check"></i> Your Vaccination Schedule
                </strong>
            </div>
            @if($schedule && ($schedule->schedule_1 || $schedule->schedule_2 || $schedule->schedule_3))
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">First Schedule</th>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">Second Schedule</th>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">Third Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($schedule->schedule_1)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ \Illuminate\Support\Carbon::parse($schedule->schedule_1)->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                                <td style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($schedule->schedule_2)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ \Illuminate\Support\Carbon::parse($schedule->schedule_2)->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                                <td style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($schedule->schedule_3)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ \Illuminate\Support\Carbon::parse($schedule->schedule_3)->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center;padding:1.5rem;color:#6b7280;">
                    <i class="fas fa-calendar-times" style="font-size:1.5rem;margin-bottom:0.5rem;"></i>
                    <p style="margin:0;">No vaccination schedule has been set yet.</p>
                </div>
            @endif
        </div>

        @if(is_countable($vaccinations) && count($vaccinations))
            @foreach($vaccinations as $v)
                <div class="vaccination-item">
                    <div class="vaccine-info">
                        <div class="vaccine-icon">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <div>
                            <div class="vaccine-title">
                                {{ $v->vaccine ?? 'Unknown Vaccine' }}
                            </div>
                            <div class="vaccine-date">
                                {{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('M d, Y') : 'No Date Recorded' }}
                            </div>
                            @if($v->notes)
                                <div class="vaccine-notes">
                                    {{ Str::limit($v->notes, 120) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="vaccine-dose">
                        Dose {{ $v->dose ?? '—' }}
                    </div>
                </div>
            @endforeach

            @if(method_exists($vaccinations, 'links'))
                <div style="margin-top:1rem">
                    {{ $vaccinations->links() }}
                </div>
            @endif
        @else
            <p class="muted">No vaccination records found.</p>
        @endif
    </div>

    {{-- Font Awesome for icons --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
