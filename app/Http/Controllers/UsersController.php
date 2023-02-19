<?php

namespace App\Http\Controllers;

use SNMP;
use Generator;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Reviews;
use App\Models\UserDetails;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Exception as GlobalException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = array();
        $user = Auth::user();
        $doctor = User::where('type','doctor')->get();
        $details = $user->user_details;
        $doctorData = Doctor::all();

        /// GET TODAY'S APPOINTMENTS
        $date = now()->format('F d, Y');
        $appointments = Appointments::where('status','upcoming')->where('date',$date)->first();

        foreach ($doctorData as $data) {
            foreach($doctor as $info){
                if($data['doc_id'] == $info['id']){
                    $data['doctor_name'] = $info['name'];
                    $data['doctor_profile'] = $info['profile_photo_url']; 

                    if(isset($appointments) && $appointments['doc_id'] == $info['id']){
                        $data['appointments'] = $appointments;
                    }
                }          
            }
        
            $reviews = Reviews::where('doc_id',$data->doc_id)->where('status','active')->get();
            if(isset($reviews)){
                $counts = count($reviews);
                $rating = 0;
                $total = 0;
                
                if($counts != 0){
                    foreach($reviews as $review){
                        $total += $review['ratings'];
                    }
                    $rating = $total / $counts;
                }else{
                    $rating = 0.0;
                }
            }
            $data['average_ratings'] = $rating;
            $data['reviews'] = $reviews;
        }

        $user['doctors'] = $doctorData;
        $user['details'] = $details;

        return $user;
    }

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

        $user = User::where('email',$request->email)->first();

        if(!$user) {
            return response()->json(['success'=>false, 'message' => 'There is no user register using this email.']);
         }
        if(!Hash::check($request->password, $user->password)){
           return response()->json(['success'=>false, 'message' => 'Passsword you entered is incorrect.']);
        }

        return  response()->json(['success'=>true, 'message' => $user->createToken($request->email)->plainTextToken]);
    }

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
                'name'=>'required|string',
                'email'=>'required|email',
                'password'=>'required',
            ]);

            $user = User::where('email',$request->email)->first();

            if($user){
                return response()->json([
                    'success'  => false,
                    'message' => 'The email has already been taken.',
                ],422);
            }

            try{

                $user = User::create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'type'=>'user',
                    'password'=>Hash::make($request->password),
                ]);
        
                $userInfo = UserDetails::create([
                    'user_id'=>$user->id,
                    'status'=>'active',
                ]);

                // $userInfo->save();
        
                return response()->json([        
                    'success'  => true,
                    'message' => 'Account created succefully.',
                    'data' => $user,
                ]);
            }
            catch(GlobalException $exception){

                return response()->json([
                    'success'  => false,
                    'message' => $exception->getMessage(),
                ]);
            }



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
        //
    }


        /**
     * Store a favorite doctor List.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFavoriteDoctor(Request $request)
    {
        /// STORE FAVORITE DOCTOR LIST
        $saveFavortie = UserDetails::where('user_id',Auth::user()->id)->first();

        $doctorList = json_encode($request->get('favoriteDoctorList'));

        $saveFavortie->favorite = $doctorList;
        $saveFavortie->save();

        return response()->json([
            'success'=> true, 
            'message'=> 'The favorite doctor list is updated.',
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
