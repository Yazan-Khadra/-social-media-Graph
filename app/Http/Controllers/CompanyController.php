<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    use JsonResponseTrait;

    //Get all companies
   
    public function show_all_company()
    {
        $companies = Company::select(['company_name','email','mobile_number', 'description', 'logo_url'])->get();
        return $this->JsonResponseWithData('All Companies : ', $companies, 200);
    }

    //Get specific company by ID
    public function show_one_company($id)
    {
        $company = Company::findOrFail($id);
        return $this->JsonResponseWithData('Company retrieved successfully', $company, 200);
    }

    public function Fill_Company_Info(Request $request)
    {
        $user = Auth::user();

        $validation = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'logo_url' => 'image|mimes:jpg,png',
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }

                // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please register as company first.", 404);
        }

        // Update company information
        $company->update([
            'description' => $request->description ?: null,
        ]);

        // Handle company logo
        if ($request->hasFile("logo_url")) {
            $path = $request->logo_url->store('logo_url', 'public');
            $company->logo_url = '/storage/' . $path;
        }

        $company->save();

        return $this->JsonResponse('Company information added successfully', 201);
    }

    public function Set_Social_Links(Request $request) {

        $user = Auth::user();

        $validation = Validator::make($request->all(), [
            "links" => "array",
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 422);
        }

        // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please create company info first.", 404);
        }

        $company->social_links = $request->links;
        $company->save();

        return $this->JsonResponse("Social links added successfully", 201);
    }

    public function Update_Social_Links(Request $request) {

        $user = Auth::user();

        $validation = Validator::make($request->all(), [
            "links" => "array",
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 422);
        }

        // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please create company info first.", 404);
        }

        $company->social_links = $request->links;
        $company->save();

        return $this->JsonResponse("Social links updated successfully", 200);
    }

            // Update company info for the authenticated user
    public function update_company_info(Request $request)
    {
        $user = Auth::user();

        // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please create company info first.", 404);
        }

        $validation = Validator::make($request->all(), [
            'company_name' => 'string|max:255',
            'description' => 'nullable|string',
            'email' => 'email|unique:companies,email,' . $company->id,
            'mobile_number' => 'numeric|regex:/^09\d{8}$/|unique:companies,mobile_number,' . $company->id,
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }

        $company->company_name = $request->company_name;
        $company->description = $request->description;
        if ($request->email) {
            $company->email = $request->email;
        }
        if ($request->mobile_number) {
            $company->mobile_number = $request->mobile_number;
        }
        $company->save();

        return $this->JsonResponse('Company information updated successfully', 200);
    }

            public function update_logo_url(Request $request) {
        $user = Auth::user();

        // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please create company info first.", 404);
        }

        $validation = Validator::make($request->all(), [
            'logo_url' => 'image|mimes:png,jpg',
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 422);
        }

        // Delete the previous image from file system
        if ($company->logo_url) {
            $previous_image_url = public_path($company->logo_url);
            if (File::exists($previous_image_url)) {
                File::delete($previous_image_url);
            }
        }

        // Store the new image
        if ($request->hasFile('logo_url')) {
            $path = $request->logo_url->store("logo_url", "public");
            $company->logo_url = '/storage/' . $path;
        }

        $company->save();
        return $this->JsonResponse("Logo updated successfully", 200);
    }

    public function delete_logo_url() {
        $user = Auth::user();

        // Find existing company record for this user
        $company = Company::where('email', $user->email)->first();

        if (!$company) {
            return $this->JsonResponse("Company not found. Please create company info first.", 404);
        }

        // Delete the previous logo from file system
        if ($company->logo_url) {
            $previous_image_url = public_path($company->logo_url);
            if (File::exists($previous_image_url)) {
                File::delete($previous_image_url);
            }
        }

        // Set null in DB after delete
        $company->logo_url = null;
        $company->save();

        return $this->JsonResponse("Logo deleted successfully", 200);
    }

    // Delete the company account
    public function destroy()
    {
        $user = Auth::user();

        // Get company record for this user
        $company = Company::where('email', $user->email)->first();

        if ($company) {
            $company->delete();
        }

        $user->delete();
        return $this->JsonResponse('Company account deleted successfully', 200);
    }

    // Search companies by name
    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'company' => 'required|string'
        ]);

        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(), 400);
        }

        $company = $request->input('company');
        $companies = Company::where('company_name', 'LIKE', "%{$company}%")
                           ->orWhere('description', 'LIKE', "%{$company}%")
                           ->get();

        return $this->JsonResponseWithData('Search completed successfully', $companies, 200);
    }
}
