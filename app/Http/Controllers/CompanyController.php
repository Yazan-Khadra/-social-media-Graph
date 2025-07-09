<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use JsonResponseTrait;

    //Get all companies
    public function show_all_company()
    {
        $companies = Company::select(['company_name', 'description', 'logo_url'])->get();
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
        $validation = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'phone' => 'required|regex:/^[1-9]{9}$/',
            'logo_url' => 'image|mimes:jpg,png',
        ]);
        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(),400);
        }
        $company_data = User::findOrFail(Auth::user()->id);

        $company_data->phone= $request->phone?:null;
        $company_data->description= $request->description?:null;
        // company image
        if($request->hasFile("logo_url")){
            $path = $request->logo_url->store('logo_url','public');
            $company_data->logo_url = '/storage/' . $path;
           }
           $company_data->save();

        return $this->JsonResponse('data added sucsessfully', 201);
    }
    public function Set_Social_Links(Request $request) {
        $validation = Validator::make($request->all(),[
            "links" => "array",
        ]);
        if($validation->fails()) {
            return $this->JsonResponse($validation->errors(),422);
        }
        $user = User::findOrFail(Auth::user()->id)->update([
            "social_links" => $request->links,
        ]);
        return $this->JsonResponse("social links added sucssesfully",201);
     }
     public function Update_Social_Links(Request $request) {

            $validation = Validator::make($request->all(),[
                "links" => "required|array",
            ]);
            if($validation->fails()){
                return $this->JsonResponse($validation->errors(),422);
            }
            //get the user
            $user = User::findOrFail(Auth::user()->id);

            $user->social_links = $request->links;
            $user->save();
            return$this->JsonResponse("data updated sucsessfully",200);
            }


    //update company info

    public function update_company_info(Request $request, $id)
    {
        $company = User::findOrFail($id);
        $validation = Validator::make($request->all(), [
            'company_name'=>'string',
            'phone'=>'regex:/^[1-9]{9}$/',
            'description' => 'string',
        ]);
        if ($validation->fails()) {
            return $this->JsonResponse($validation->errors(),400);
        }
        $companyData = $request->only(['company_name', 'description', 'email', 'phone', '']);
        $company->update($companyData);

        return $this->JsonResponse('Company updated successfully', 201);

    }
    public function update_logo_url(Request $request) {
              $validation = Validator::make($request->all(),[
             'logo_url' =>'required|image|mimes:png,jpg',
              ]);

              if($validation->fails()) {
                 return $this->JsonResponse($validation->errors(),422);
             }

              $user = User::findOrFail(Auth::user()->id);
              //update company image
             if($request->hasFile('logo_url')){
              //delete the previus image from file system
                 $previus_image_url = public_path($user->profile_image_url);
             if(File::exists($previus_image_url)){
                 File::delete($previus_image_url);
              // store the new image in file system and DB
             }
             $path = $request->profile_image->store("logo_url","public");
                 $user->logo_url = '/storage/' . $path ;

          }
             $user->save();
             return $this->JsonResponse("profile Image Updated Sucssesfully",202);

      }
      public function delete_logo_url(){

        $user = User::findOrFail(Auth::user()->id);
        //get the privous logo_url
        $previus_image_url = public_path($user->logo_url);
        if(File::exists($previus_image_url)){
          File::delete($previus_image_url);
        }
        // set null in DB after delete
        $user->logo_url = null;
            $user->save();
            return $this->JsonResponse("Profile Image Deleted Successfully",200);

    }

    //delete the company dashboard "بدها تعديل وقت الداشبورد "
    public function destroy($id)
    {
        // delete the phptp also
        $company = User::findOrFail($id);
        $company->delete();
        return $this->JsonResponse('Company deleted successfully', 201);

    }


     //Search companies by name
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
