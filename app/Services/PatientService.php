<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

class PatientService
{

    public function getPatients($validated)
    {
        $query = Patient::query()
        ->select([
            'id',
            'ci',
            'name',
            'last_name',
            'phone_number',
            'sex',
            'date_birth',
            'municipality_id',
            'parish_id',
            'address',
            'age',
            'email',
        ]);

        if (!empty($validated['search'])) {
        $query->where(function($q) use ($validated) {
            $q->where('name', 'like', "%{$validated['search']}%")
              ->orWhere('last_name', 'like', "%{$validated['search']}%")
              ->orWhere('ci', 'like', "%{$validated['search']}%")
              ->orWhere('phone_number', 'like', "%{$validated['search']}%")
              ->orWhere('search', 'like', "%{$validated['search']}%");
            });
        }

        if (!empty($validated['ci'])) {
            $query->where('ci', $validated['ci']);
        }

        $query->orderBy($validated['sort_by'], $validated['sort_direction']);

        $patients = $query->paginate($validated['per_page']);

        return[
            'data' => $patients->items(),
            'meta' => [
                'current_page' => $patients->currentPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
                'last_page' => $patients->lastPage(),
            ]
        ];

    }

    public function create($data)
    {

        $data['age'] = $this->calculateAge($data['date_birth']);
        $data['search'] = $data['name'] . ' ' . $data['last_name'] . $data['ci'];

        Patient::create($data);
        return 0;

    }




    function calculateAge($dateBirth): int
    {
        $date = Carbon::parse($dateBirth);
        $today = Carbon::now();

        return $today->diffInYears($date);

    }

}
