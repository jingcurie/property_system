<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RentalApplication;
use Illuminate\Http\Request;

class RentalApplicationController extends Controller
{
    public function create(Property $property)
    {
        return view('applications.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'applicant_name' => 'required',
            'phone' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'message' => 'nullable|string',
        ]);

        $property->rentalApplications()->create($request->only([
            'applicant_name',
            'phone',
            'start_date',
            'end_date',
            'message',
        ]));

        return redirect()->route('properties.show', $property)->with('success', '申请已提交，我们将尽快联系您。');
    }

    public function index(Request $request)
    {
        $query = \App\Models\RentalApplication::with('property')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->paginate(10)->withQueryString();

        return view('applications.index', compact('applications'));
    }

    public function updateStatus(Request $request, RentalApplication $application)
    {
        $application->update(['status' => $request->status]);

        return redirect()->back()->with('success', '状态已更新');
    }

    public function destroy(RentalApplication $application)
    {
        $application->delete();

        return redirect()->back()->with('success', '申请已删除');
    }
}
