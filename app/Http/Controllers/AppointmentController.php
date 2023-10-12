<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_old()
    {
        //
        $appointment = Appointment::latest()->paginate(5);
        return view('absents.index', compact('absents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query_data = new Appointment();
            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data->where(function ($query) use ($search_value) {
                    $query->where('id_appointment', 'like', $search_value)
                        ->orWhere('name', 'like', $search_value);
                });
            }

            $data = $query_data->orderBy('id_appointment', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                
                ->addColumn('action', function ($data) {
                    // Tambahkan logika untuk kolom 'action' sesuai kebutuhan
                })
                ->make(true);
        }
        return view('absents.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('absents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        return redirect()->route('absents.index')
            ->with('success', 'Absent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
        return view('absents.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
        return view('absents.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
        $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        return redirect()->route('absents.index')
            ->with('success', 'Absents updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
        $appointment->delete();

        return redirect()->route('absents.index')
            ->with('success', 'Absent deleted successfully');
    }
}
