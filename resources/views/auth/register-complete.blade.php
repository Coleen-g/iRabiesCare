@extends('layouts.app')

@section('title', 'Registration Complete')

@section('content')
<style>
    /* Internal CSS for registration complete page */
    .rc-root { min-height: 70vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 48px 16px; }
    .rc-card { width:100%; max-width:900px; background: #fff; border-radius:16px; box-shadow: 0 8px 24px rgba(2,6,23,0.08); padding:28px; font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#0f172a }
    .rc-header { display:flex; gap:16px; align-items:center; margin-bottom:18px }
    .rc-badge { width:64px; height:64px; border-radius:50%; background: linear-gradient(135deg,#0ea5e9,#0369a1); display:flex; align-items:center; justify-content:center; color:white; box-shadow: 0 6px 18px rgba(3,105,161,0.18) }
    .rc-title { font-size:22px; font-weight:700; margin:0; color:#0f172a }
    .rc-sub { margin:0; color:#475569; font-size:14px }

    .rc-grid { display:grid; grid-template-columns: 1fr; gap:18px }
    @media(min-width:720px) { .rc-grid { grid-template-columns: 2fr 1fr } }

    .rc-panel { background:#f8fafc; border:1px solid #eef2ff; padding:14px; border-radius:10px }
    .rc-label { font-size:13px; color:#475569; margin-bottom:8px }
    .rc-input { width:100%; padding:11px 12px; border:1px solid #e6edf3; border-radius:8px; font-size:16px; color:#0f172a; background:#fff }
    .rc-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; cursor:pointer; border:none }
    .rc-btn-primary { background:#0369a1; color:#fff }
    .rc-btn-secondary { background:#fff; border:1px solid #e6edf3; color:#0f172a }
    .rc-small { font-size:13px; color:#64748b }

    .rc-box { background:#fff; border:1px solid #eef2ff; padding:12px; border-radius:8px }
    .rc-quick a{ display:block; text-decoration:none; margin-bottom:8px; padding:10px; border-radius:8px; text-align:center }
    .rc-quick .primary { background:#0369a1; color:#fff; font-weight:600 }
    .rc-quick .ghost { border:1px solid #e6edf3; color:#0f172a; background:#fff }

    .rc-help { text-align:center; color:#64748b; font-size:13px; margin-top:12px }
</style>

<div class="rc-root">
    <div class="rc-card">
        <div class="rc-header">
            <div class="rc-badge" aria-hidden="true">
                <svg width="28" height="28" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <div>
                <h1 class="rc-title">Registration Complete</h1>
                <p class="rc-sub">Thank you — your registration has been recorded.</p>
            </div>
        </div>

        <div class="rc-grid">
            <div>
                <div class="rc-panel">
                    <div class="rc-label">Patient ID</div>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <input id="patientIdInput" class="rc-input" readonly value="{{ $patient->id }}" />
                        <button id="copyBtn" class="rc-btn rc-btn-primary" type="button">Copy</button>
                        <button id="printBtn" class="rc-btn rc-btn-secondary" type="button">Print</button>
                    </div>
                    <p class="rc-small" style="margin-top:10px">Please keep this Patient ID safe. Your account credentials will be provided by the clinic administrator soon.</p>
                </div>

                <div style="margin-top:12px" class="rc-box">
                    <h3 style="margin:0 0 8px 0; font-size:15px; color:#0f172a">What happens next?</h3>
                    <ul style="margin:0;padding-left:18px;color:#475569;font-size:14px;line-height:1.5">
                        <li>An administrator will create your login credentials and notify you.</li>
                        <li>If you need urgent assistance, contact the clinic reception.</li>
                        <li>Bring a valid ID and this Patient ID during your first visit.</li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="rc-box rc-quick">
                    <div class="rc-label">Quick Links</div>
                    <a href="{{ route('login') }}" class="primary">Back to Login</a>
                    <a href="{{ url('/') }}" class="ghost">Return to Home</a>
                </div>

                <div class="rc-help">
                    <p>Need help?</p>
                    <p><a href="tel:+000000000" style="color:#0369a1; text-decoration:none">(000) 000-0000</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const copyBtn = document.getElementById('copyBtn');
        const printBtn = document.getElementById('printBtn');
        const patientInput = document.getElementById('patientIdInput');

        if (copyBtn) {
            copyBtn.addEventListener('click', function(){
                navigator.clipboard.writeText(patientInput.value).then(function(){
                    const prev = copyBtn.innerText;
                    copyBtn.innerText = 'Copied';
                    setTimeout(()=>{ copyBtn.innerText = prev; }, 1400);
                }).catch(function(){
                    alert('Unable to copy. Please copy manually.');
                });
            });
        }

        if (printBtn) {
            printBtn.addEventListener('click', function(){
                const content = `<div style="font-family:Arial, Helvetica, sans-serif;padding:20px;"><h2>Patient ID</h2><p style="font-size:24px;font-weight:700;">${patientInput.value}</p></div>`;
                const w = window.open('', '_blank');
                w.document.write(content);
                w.document.close();
                w.focus();
                w.print();
                w.close();
            });
        }
    })();
</script>

@endsection
