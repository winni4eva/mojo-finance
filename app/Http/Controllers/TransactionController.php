<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use App\Traits\HttpResponses;
use Auth;
use DB;
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
     * @return \App\Http\Resources\TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreTransactionRequest $request, Account $account)
    {
        // Does auth user own debit account
        if ($account->user_id != Auth::user()->id) {
            return $this->error('', 'You are not authorized to make this request', self::ERROR_RESPONSE_CODE);
        }
        
        // Does credit accounts exist
        $creditAccount = Account::find($request->credit_account);

        if (!$creditAccount) {
            return $this->error('', 'Credit account does not exist', self::ERROR_RESPONSE_CODE);
        }

        // Is credit account and debit account same
        if ($creditAccount->id == $account->id) {
            return $this->error('', 'Debit and credit accounts are the same', self::ERROR_RESPONSE_CODE);
        }

        // Does debit account hold enough balance
        if (($request->amount / 100) > $account->amount) {
            return $this->error('', 'You do not have sufficient balance to perform this transaction', self::ERROR_RESPONSE_CODE);
        }

        DB::beginTransaction();

        $account->update([
            'amount' => $account->amount - $request->amount,
        ]);
        $creditAccount->update([
            'amount' => $creditAccount->amount + $request->amount
        ]);
        $transaction = Transaction::create([
            'credit_account_id' => $creditAccount->id,
            'debit_account_id' => $account->id,
            'amount' => $request->amount
        ]);

        if($transaction) {
            DB::commit();
            return new TransactionResource($transaction);
        }
        
        DB::rollBack();

        return $this->error('', 'Error saving transaction', 403);
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
