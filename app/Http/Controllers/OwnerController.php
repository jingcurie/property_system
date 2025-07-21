<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\PropertyOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'ownership_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'property_id' => 'required|exists:properties,id',
        ]);

        DB::transaction(function () use ($validated) {
            $owner = Owner::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
            ]);

            PropertyOwnership::create([
                'property_id' => $validated['property_id'],
                'owner_id' => $owner->id,
                'ownership_percentage' => $validated['ownership_percentage'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);
        });

        return redirect()->back()->with('success', '业主添加成功');
    }
}
