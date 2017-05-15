<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function send(Request $request){

        $title = $request->input('name')." would like you to vote for LunchOUT!";
        $content = "Time to get your vote on! Have your say on were to go to lunch by clicking on the link below. Happy LunchOUT";

        // dd($request->all());

        $emails = explode(",", $request->input('emails'));
        $senderEmail = $request->input('senderEmail');
        $name = $request->input('name');

// , 'email' => $email, 'senderEmail' => $senderEmail, 'name' => $name
// FOR TESTING ONLY!!!! REMOVE BEFORE PULLING TO PROD!

        foreach ($emails as $email){
            Mail::send('emails.send', ['title' => $title, 'content' => $content], function ($message)
            {
                $message->from('menani84@gmail.com');
                $message->to('menani84@ufl.edu');
            });
        }

        return response()->json(['message' => 'Request completed']);
    }
}
