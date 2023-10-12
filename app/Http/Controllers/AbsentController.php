<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class  AbsentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_old()
    {
        //
        $absent = Absent::latest()->paginate(5);
        return view('absents.index', compact('absents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Appointment::select('*');
            $query_data = new Absent();
            if ($request->sSearch) {
                $search_value = '%' . $request->sSearch . '%';
                $query_data = $query_data->where(function ($query) use ($search_value) {
                    $query->where('user_id', 'like', $search_value)
                        ->orWhere('status', 'like', $search_value);
                });
            }

            $data = $query_data->orderBy('user_id', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function (Absent $ce) {
                    return $ce->User->name;
                })->addColumn('status_name', function (Absent $absent) {
                    if ($absent->status == 1) {
                        return 'Hadir';
                    } elseif ($absent->status == 0) {
                        return 'Tidak Hadir';
                    } else {
                        return 'Tidak Hadir';
                    }
                })
                ->addColumn('status_jabatan', function (Absent $ce) {
                    if ($ce->User->id_appointment == 1) {
                        return 'head of office';
                    }
                    return 'employee';
                })
                
                ->rawColumns(['action'])
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
    public function store()
    {
        // $user = Absent::where('user_id',Auth::user()->id)->update();
        $user = Absent::where('user_id', Auth::user()->id)->first(); // Retrieve the specific record

        if ($user) {
            if ($user->status == '0') {
                $user->update([
                    'status' => '1'
                ]);
            } else {
                // Handle the case where 'status' is already '1'
            }

            return redirect()->route('absents.index')
                ->with('success', 'Absent successfully');
        } else {
            // Handle the case where the record wasn't found
            return redirect()->route('absents.index')
                ->with('error', 'Absent not found');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Absent $absent)
    {
        //
        return view('absents.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absent $absent)
    {
        //
        return view('absents.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absent $absent)
    {
        //
        $request->validate([
            'name' => 'required',
            'nip' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        //proses upload
        if ($image = $request->file('image')) {
            //menentukan dimana foto tersebut akan disimpan
            $destinationPath = 'image/';
            //nama file baru
            $nama_baru = date('YmdHis') . "." . $image->getClientOriginalExtension();
            //proses menyimpan
            $image->move($destinationPath, $nama_baru);
            $input['image'] = "$nama_baru";
        } else {
            unset($input['image']);
        }

        Absent::create($input);

        $absent->update($request->all());

        return redirect()->route('absents.index')
            ->with('success', 'Absent updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absent $absent)
    {
        //
        $absent->delete();

        return redirect()->route('absents.index')
            ->with('success', 'Absent deleted successfully');
    }
}
