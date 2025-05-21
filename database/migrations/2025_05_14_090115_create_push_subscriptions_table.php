<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('webpush.database_connection'))->create(config('webpush.table_name'), function (Blueprint $table) {
            // id
            $table->bigIncrements('id');

            // morphs для связывания с любым моделям (например, User)
            $table->morphs('subscribable'); // это создаст два поля: subscribable_id и subscribable_type

            // endpoint подписки
            $table->string('endpoint', 500)->unique();

            // дополнительные поля для хранения данных подписки
            $table->string('public_key')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('content_encoding')->nullable();

            // временные метки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('webpush.database_connection'))->dropIfExists(config('webpush.table_name'));
    }
};
