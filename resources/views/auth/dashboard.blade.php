@extends('layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h4>Welcome, {{ Auth::user()->name }}</h4>
                        <p>NIP: {{ Auth::user()->nip }}</p>
                        <!-- <p>Jabatan: {{ Auth::user()->id_appointment }}</p> -->
                        <div class="text-center">
                            <img src="{{ asset('profile/' . Auth::user()->image) }}" alt="Profile Image" width="100px">
                        </div>
                        <h5>Status:
                            @if ($absent->status == 1)
                                <span class="text-success">Hadir</span>
                            @else
                                <span class="text-danger">Tidak Hadir</span>
                            @endif
                        </h5>

                        <form action="{{ route('absents.store') }}" method="post" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label for="absentStatus">Change Status:</label>
                                <select class="form-control" name="absentStatus" id="absentStatus">
                                    <option value="1">Hadir</option>
                                    <option value="0">Tidak Hadir</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
