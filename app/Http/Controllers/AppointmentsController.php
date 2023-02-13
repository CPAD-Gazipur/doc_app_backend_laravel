<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /// RETRIVING ALL APPOINTMENTS FROM THE USER
        $appontments = Appointments::where('user_id',Auth::user()->id)->get();
        $doctor = User::where('type','doctor')->get();

        /// STORING APPOINTMENTS & DOCTOR DETAILS
        foreach($appontments as $data){
            foreach($doctor as $info){
                $details = $info->doctor;
                if($data['doc_id'] == $info['id']){
                    $data['doctor_name'] = $info['name'];
                    $data['doctor_profile'] = $info['profile_photo_url'];
                    $data['category'] = $details['category'];
                }
            }
        }

        return $appontments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appontments = new Appointments();
        $appontments->user_id = Auth::user()->id;
        $appontments->doc_id = $request->get('doc_id');
        $appontments->date = $request->get('date');
        $appontments->day = $request->get('day');
        $appontments->time = $request->get('time');
        $appontments->status = 'upcoming';
        $appontments->save();

        return response()->json([
            'success'=>true, 
            'message'=>'New Appoinment has been made successfully.',
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
