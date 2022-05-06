<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookAndInfoMail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function sendMail(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required:max:50',
            'purpose'  => 'required',
            'email' => 'required|email',
            'activity' => 'nullable',
            'phone' => 'required',
            'person' => 'nullable',
            'country' => 'required',
            'messege' => 'required',

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $content = [
            'name' => $request->name,
            'email' => $request->email,
            'purpose'=> $request->purpose,
            'activity' => $request->activity,
            'phone' => $request->phone,
            'country' => $request->country,
            'person' => $request->person,
            'messege' => $request->messege,

        ];

        Mail::to('pertiwiadventure@gmail.com')->send(new BookAndInfoMail($content));

        $feedback = [
            'messege' => 'Mail Has Been Sent, Mail will be answered within 24 hours',
            'type'    => 'success',
            'show'  => true
        ];
        $fail_feedback = [
            'messege' => 'Mail Failed, Please Try again',
            'type'    => 'danger',
            'show'  => true
        ];

        if (Mail::failures()) {
            return $fail_feedback;
        }
           
        return $feedback;

    }
}
