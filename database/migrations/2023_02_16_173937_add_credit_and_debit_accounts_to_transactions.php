<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** TODO 
         * TEST ROLLBACK
         */
        Schema::table('transactions', function (Blueprint $table) {
            $table->comment('Stores user account debit and credit transactions');
            $table->renameColumn('account_id', 'credit_account_id');
            $table->foreignId('debit_account_id')
                ->constrained('accounts', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->after('credit_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('credit_account_id', 'account_id');
            $table->dropForeign(['debit_account_id']);
            $table->dropColumn('debit_account_id');
        });
    }
};
