<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /// GET DOCTOR APPOINTMENTS, RATING, REVIEW & PATIENTS INFO FOR DASHBOARD
        $doctor = Auth::user();
        $appointments = Appointments::where('doc_id',$doctor->id)->where('status','upcoming')->get();
        $reviews = Reviews::where('doc_id',$doctor->id)->where('status','active')->get();

        return view('dashboard')->with(
            [
                'doctor'=>$doctor,
                'appointments'=>$appointments,
                'reviews'=>$reviews
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /// STORE APPOINTMET REVIEW & UPDATE STATUS
        $reviews = new Reviews();
        $appointments = Appointments::where('id',$request->get('appointment_id'))->first();

        $reviews->user_id = Auth::user()->id;
        $reviews->doc_id = $request->get('doctor_id');
        $reviews->ratings = $request->get('ratings');
        $reviews->reviews = $request->get('reviews');
        $reviews->reviewed_by = Auth::user()->name;
        $reviews->status = 'active';
        $reviews->save();

        $appointments->status = 'complete';
        $appointments->save();

        return response()->json([
            'success'=> true, 
            'message'=> 'The Appoinment has been completed & reviews sucessfully.',
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
