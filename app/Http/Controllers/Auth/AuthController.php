<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Absent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('nip', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                ->with('success', 'You have successfully logged in');
        }

        return redirect("login")->with('error', 'Oops! Invalid credentials');
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:8',
            'nip' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // $data = $request->all();
        $input = $request->all();
        //proses upload
        if ($image = $request->file('image')) {
            //menentukan dimana foto tersebut akan disimpan
            $destinationPath = 'profile/';
            //nama file baru
            $nama_baru = $input['name'].date('YmdHis') . "." . $image->getClientOriginalExtension();
            //proses menyimpan
            $image->move($destinationPath, $nama_baru);
            $input['image'] = "$nama_baru";
        }
        $input['id_appointment'] = 1;



        $user = User::create($input);
        Absent::create([
            'user_id'=> $user->id,
            'status' => '0'
        ]);

        return redirect("dashboard")->with('success', 'Great! You have successfully registered and logged in');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $absent = Absent::where('user_id','=',Auth::user()->id)->first();

            return view('auth.dashboard',compact('absent'));
        }

        return redirect("login")->with('error', 'Oops! You do not have access');
    }

    // public function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'nip' => $data['nip'],
    //         'password' => Hash::make($data['password']),
    //         'image' => $data['image'],
    //     ]);
    // }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }
}
