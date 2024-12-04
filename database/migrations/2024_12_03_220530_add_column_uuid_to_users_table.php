<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddColumnUuidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid("external_id")
                ->after("id")
                ->nullable()->unique();
        });

        \App\Models\User::query()->orderBy("id")
            ->chunk(500, function ($users) {
                /** @var \App\Models\User $user */
                foreach ($users as $user) {
                    $user->update([
                        "external_id" => Str::uuid()
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("external_id");
        });
    }
}
