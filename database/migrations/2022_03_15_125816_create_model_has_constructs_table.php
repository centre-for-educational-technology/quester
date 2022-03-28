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
        Schema::create('model_has_constructs', function (Blueprint $table) {
            $table->unsignedBigInteger('construct_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_constructs_model_id_model_type_index');
            $table->foreign('construct_id')
                ->references('id')
                ->on('constructs')
                ->onDelete('cascade');
            $table->primary(['construct_id', 'model_id', 'model_type'],
                'model_has_constructs_construct_model_type_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_constructs');
    }
};
