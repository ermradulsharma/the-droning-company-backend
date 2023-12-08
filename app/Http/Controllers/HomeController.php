<?php

namespace App\Http\Controllers;

use App\Mail\contactForm;
use Illuminate\Http\Request;
use App\Models\AwardCompany;
use App\Models\AwardCompanyCategory;
use App\Models\AwardCategory;
use App\Models\Voter;
use App\Models\AwardVote;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function awardVoter(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                // 'suite' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip_code' => 'required',
                'phone' => 'required',
                'email' => 'email|required|unique:voters,email',
            ]);
            // $check = Voter::where(['name' => $request->name, 'address' => $request->address, 'suite' => $request->suite ?? '', 'city' => $request->city, 'state' => $request->state, 'zip_code' => $request->zip_code])->first();
            // if ($check) {
            //     return redirect()->back()->with('error', "Sorry! you have already voted. You can only vote once");
            //     //return redirect()->route('award.voting', $voter->id)->withFlashSuccess(__('Sorry! you have already voted.'));
            // }
            // $voter = Voter::updateOrCreate(['email' => $request->email], $request->all());
            $voter = Voter::create($request->all());
            return redirect()->route('award.voting.poll', $voter->id)->withFlashSuccess(__('Please vote for each category.'));
        }
        return view('awardVoter');
    }

    public function awardVoting(Request $request, $voter)
    {
        if ($request->isMethod('post')) {
            foreach ($request->award_vote as $key => $value) {
                AwardVote::create(['category_id' => $key, 'company_id' => $value, 'voter_id' => $voter]);
                // AwardVote::updateOrCreate(
                //     ['category_id' => $key, 'company_id' => $value, 'voter_id' => $voter],
                //     ['category_id' => $key, 'company_id' => $value, 'voter_id' => $voter]
                // );
            }
            return redirect()->route('award.voting.success', $voter)->withFlashSuccess(__('Thanks! Your vote has been submitted successfully.'));
        }

        $categories = AwardCategory::with('companies', 'companies.company_detail')->orderBy('title', 'ASC')->get();
        return view('awardVoting', compact('categories', 'voter'));
    }

    public function awardVotingSuccess(Request $request, Voter $voter)
    {
        return view('awardVotingSuccess', compact('voter'));
    }

    public function contactForm(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $data = [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'company' => $request->company,
                    'address' => $request->address,
                    'phone_type' => $request->phone_type,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'comment' => $request->comment
                ];
                // $recipient = 'abaanoutsourcing@yopmail.com';
                $recipient = 'abaanoutsourcing@gmail.com';
                Mail::send(new contactForm($data, $recipient));
                return redirect()->route('contact-us')->with('success', 'Thank you! Your message has been sent.');
            }
            return view('contact_us');
        } catch (Exception $e) {
            Log::error([
                'message' => $e->getMessage()
            ]);
            return response()->json(['status' => 500, 'message' => $e->getMessage(), 'data' => $e->getMessage(),])->setStatusCode(500);
        }
    }
}
