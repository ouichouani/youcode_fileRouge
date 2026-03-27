<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('difficulty' , [ "xxs" , "xs" , "s" , "m" , "l" , "xl" , "xxl"])->default('m');
            $table->enum('priority' , [ "xxs" , "xs" , "s" , "m" , "l" , "xl" , "xxl"])->default('m');
            $table->timestamp("deadline")->nullable();
            $table->boolean('done')->default(false) ;
            $table->boolean('is_task')->default(false) ;


            // combine tasks and habites in one table 'tasks'

            $table->integer('streaks')->default(0) ;
            $table->json('frequency')->default(json_encode(['OneTime']));
            // $table->json('frequency')->default(json_encode(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']));


            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete() ;
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete() ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
