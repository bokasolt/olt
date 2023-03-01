<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAhrefsSyncDateToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->timestamp('ahrefs_sync_at')->nullable();
            $table->string('ahrefs_error_message')->nullable();
            $table->index('ahrefs_error_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('ahrefs_sync_queue_at');
            $table->dropColumn('ahrefs_sync_queue');
            $table->dropColumn('ahrefs_error_message');
            $table->dropColumn('ahrefs_sync_at');
        });
    }
}
