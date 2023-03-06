<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain', 255)->default('');
            $table->string('niche', 255)->nullable();
            $table->string('lang', 32)->nullable();
            $table->string('title', 1023)->nullable();

            $table->unsignedInteger('ahrefs_dr')->nullable();
            $table->unsignedInteger('ahrefs_traffic')->nullable();
            $table->unsignedInteger('linked_domains')->nullable();
            $table->unsignedInteger('ref_domains')->nullable();
            $table->unsignedInteger('num_organic_keywords_top_10')->nullable();

            $table->string('article_by', 255)->nullable();
            $table->decimal('price', 7, 2)->nullable();
            $table->string('sponsored_label', 255)->nullable();
            $table->string('type_of_publication', 255)->nullable();
            $table->string('type_of_link', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->text('contact_form_link')->nullable();
            $table->string('contact_name', 255)->nullable();
            $table->text('additional_notes')->nullable();
        });

        Schema::create('domain_temp_imports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('domain', 255)->default('');
            $table->string('niche', 255)->nullable();
            $table->string('lang', 32)->nullable();
            $table->string('title', 1023)->nullable();

            $table->unsignedInteger('ahrefs_dr')->nullable();
            $table->unsignedInteger('ahrefs_traffic')->nullable();
            $table->unsignedInteger('linked_domains')->nullable();
            $table->unsignedInteger('ref_domains')->nullable();
            $table->unsignedInteger('num_organic_keywords_top_10')->nullable();

            $table->string('article_by', 255)->nullable();
            $table->decimal('price', 7, 2)->nullable();
            $table->string('sponsored_label', 255)->nullable();
            $table->string('type_of_publication', 255)->nullable();
            $table->string('type_of_link', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->text('contact_form_link')->nullable();
            $table->string('contact_name', 255)->nullable();
            $table->text('additional_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
        Schema::dropIfExists('domain_temp_import');
    }
}
