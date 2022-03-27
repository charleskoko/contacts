<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        return $this->success(ContactResource::collection($user->contacts), 'success', 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
        ]);

       $contact = Contact::create($validatedData);

       return $this->success(ContactResource::make($contact),'success',201);
    }


    public function show(Contact $contact): JsonResponse
    {
        return $this->success(ContactResource::make($contact),'success', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Contact $contact
     * @return JsonResponse
     */
    public function update(Request $request, Contact $contact): JsonResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
        ]);
        $contact->update($validatedData);
        return $this->success(ContactResource::make($contact->refresh()),'success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @return Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        $this->success('','success',200);
    }
}
