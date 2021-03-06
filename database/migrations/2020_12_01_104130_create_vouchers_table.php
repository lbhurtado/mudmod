<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $voucherTable = config('vouchers.table', 'vouchers');
        $pivotTable = config('vouchers.relation_table', 'contact_voucher');

        Schema::create($voucherTable, function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 32)->unique();
            $table->morphs('model');
            $table->text('data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create($pivotTable, function (Blueprint $table) use ($voucherTable) {
            $table->increments('id');
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('voucher_id');
            $table->timestamp('redeemed_at');
            $table->timestamp('increased_at')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('voucher_id')->references('id')->on($voucherTable);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('vouchers.table', 'vouchers'));
        Schema::dropIfExists(config('vouchers.relation_table', 'contact_voucher'));
    }
}
