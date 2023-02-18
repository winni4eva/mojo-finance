<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account;
use App\Traits\HttpResponses;
use Auth;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use HttpResponses;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request, Account $account)
    {
        logger('Account => ' . $account->id);
        logger($request->all());
        // Does auth user own debit account
        logger('User ID => ' . $account->user()->first()->id);
        if ($account->user_id != Auth::user()->id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
        //$currentUserOwnsAccount = $account->where('user_id', Auth::user()->id)->get();
        //logger('User Accounts ', $currentUserOwnsAccount->toArray());
        
        // Does credit accounts exist

        // Does debit account hold enough balance

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
