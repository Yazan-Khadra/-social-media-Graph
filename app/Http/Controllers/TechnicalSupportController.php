<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechSupportResource;
use App\JsonResponseTrait;
use App\Mail\IssueResponseMail;
use App\Models\TechnicalSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TechnicalSupportController extends Controller
{
    use JsonResponseTrait;
   public function Set_issue(Request $request) {
    $validation = Validator::make($request->all(),[
        'name' => 'required|string',
        'email' => 'required|email',
        'issue' => 'required',
        'description' => 'required|string'
    ]);

    if($validation->fails()){
        return $this->JsonResponse($validation->errors(),422);
    }

    
    $data = $validation->validated();

    
    TechnicalSupport::create($data);

    return $this->JsonResponse(['message' => 'Issue created successfully'], 201);
}
  public function Get_issues() {
    $tech_issues =TechnicalSupport::where('status','pending')->get();
    return TechSupportResource::collection($tech_issues);
  }
  

public function respondToIssue(Request $request, $id)
{
    $issue = TechnicalSupport::findOrFail($id);
    

    $responseText = $request->input('response');
    $issue->status = "done";

    // Save response in DB (optional)
    

    // Send email
    Mail::to($issue->email)->send(new IssueResponseMail($issue, $responseText));

    return response()->json(['message' => 'Response sent successfully!']);
}

}
