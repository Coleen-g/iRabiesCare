<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Models\Patient;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index()
    {
        $q = request('q');

        $vaccinations = Vaccination::with('patient')
            ->when($q, function($query, $q) {
                $query->where('vaccine', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%")
                      ->orWhereHas('patient', function($q2) use ($q) {
                          $q2->where('name', 'like', "%{$q}%");
                      });
            })
            ->latest()->paginate(15)->withQueryString();
        
        return view('admin.vaccinations', compact('vaccinations'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.vaccinations-create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_given' => 'nullable|date',
            'vaccine' => 'nullable|string|max:255',
            'dose' => 'nullable|string|max:100',
            'administered_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Vaccination::create($data);

        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination recorded');
    }

    public function edit(Vaccination $vaccination)
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.vaccinations-edit', compact('vaccination', 'patients'));
    }

    public function update(Request $request, Vaccination $vaccination)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_given' => 'nullable|date',
            'vaccine' => 'nullable|string|max:255',
            'dose' => 'nullable|string|max:100',
            'administered_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $vaccination->update($data);

        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination updated');
    }

    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();
        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination deleted');
    }
}
