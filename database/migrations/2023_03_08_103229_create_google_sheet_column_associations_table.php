<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleSheetColumnAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_sheet_column_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('google_sheet_id');
            $table->string('gs_column');
            $table->string('db_column');
            $table->timestamps();

            $table->foreign('google_sheet_id')
                ->references('id')
                ->on('google_sheets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_sheet_column_associations');
    }
}
