<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAhrefsSyncQueueToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('ahrefs_sync_queue')->nullable();
            $table->timestamp('ahrefs_sync_queue_at')->nullable();
            $table->index('ahrefs_sync_queue_at');

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
        });
    }
}
