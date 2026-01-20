<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UsersTableColChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop old enum check constraints that might persist
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_yes_send_email_check');
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_yes_i_agree_check');
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_active_status_check');

            DB::statement('ALTER TABLE users ALTER COLUMN yes_send_email TYPE BOOLEAN USING (yes_send_email::text::boolean)');
            DB::statement('ALTER TABLE users ALTER COLUMN yes_i_agree TYPE BOOLEAN USING (yes_i_agree::text::boolean)');

            Schema::table('users', function (Blueprint $table) {
                $table->boolean('yes_send_email')->default(false)->change();
                $table->boolean('yes_i_agree')->default(false)->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('yes_send_email')->default(false)->change();
                $table->boolean('yes_i_agree')->default(false)->change();
            });
        }
    }
}
