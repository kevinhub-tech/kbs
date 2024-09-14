<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionHandler;
use App\Mail\PartnerShipAcceptanceMail;
use App\Mail\PartnershipRejection;
use App\Models\orders;
use App\Models\roles;
use App\Models\users;
use App\Models\vendorApplication;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class AdminController extends Controller
{
    /**
     * Login logic code starts here
     */

    public function login()
    {
        if (SessionHandler::checkUserSession()) {
            return redirect()->back();
        }
        return view('login');
    }
    public function signin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = users::where('name', $request->name)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->token = Uuid::uuid4()->toString();
                $user->save();
                $user_role = roles::find($user->role_id);
                SessionHandler::storeSessionDetails($user->user_id, $user->name, $user_role->role_name, $user->token);
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.login')->with('message', 'Unmatched password. Please check your password again');
            }
        } else {
            return redirect()->route('admin.login')->with('message', 'You do not have an account on the app. Please create an account or Login with Facebook/Google');
        }
    }

    /**
     * Web logic code starts here
     */

    public function dashboard()
    {
        $vendor_role = roles::where('role_name', '=', 'vendor')->first();
        $user_role = roles::where('role_name', '=', 'user')->first();
        $vendor_count = users::where('role_id', '=', $vendor_role->role_id)->count();
        $user_count = users::where('role_id', "=", $user_role->role_id)->count();
        $vendor_application_count = vendorApplication::all()->count();
        $order_count = orders::all()->count();
        return view('admins.dashboard', compact('vendor_count', 'user_count', 'vendor_application_count', 'order_count'));
    }

    public function logout()
    {
        $user = users::find(session('userId'));
        $user->token = null;
        $user->save();
        if ($user) {
            SessionHandler::removeSessionDetails();
            return redirect()->route('admin.login');
        }
    }

    public function vendors()
    {
        return view('admins.vendors');
    }

    /**
     * API logic code starts here
     */

    /**
     * Book API logic code starts here
     */
    public function updateapplicationstatus(Request $request)
    {
        $request->validate([
            'status' => ['required'],
            'id' => ['required']
        ]);
        $message = '';
        $vendor = vendorApplication::find($request->id);
        if ($request->status === 'accepted') {
            $vendor->status = $request->status;
            $vendor->token = Uuid::uuid4()->toString();
            $vendor->token_expiration = now()->addHours(24);
            $vendor->updated_at = now();
            $vendor->save();

            Mail::to($vendor->email)
                ->send(new PartnerShipAcceptanceMail($vendor->application_id));

            $message = "Application has been approved and mail has been sent to the vendor to fill out their info";
            // Perform mail action and generate new token
        } else {
            $request->validate([
                'rejection_reason' => ['required']
            ]);
            $vendor->status = $request->status;
            $vendor->rejection_reason = $request->rejection_reason;
            $vendor->updated_at = now();
            $vendor->save();
            Mail::to($vendor->email)
                ->send(new PartnershipRejection($vendor->application_id));

            $message = "Application has been approved and mail has been sent to the vendor to fill out their info";
            // Send mail to why it is rejected and update the application status
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'payload' => []
        ], Response::HTTP_OK);
    }
}
