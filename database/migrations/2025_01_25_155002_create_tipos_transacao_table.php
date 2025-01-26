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
        Schema::create('tipos_transacao', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->timestamps();
            
            $table->unique('code');
        });

        //Facilidade em adicionar novos tipos.(pagamento parcelado, pagamento de boleto....)
        DB::table('tipos_transacao')->insert([
            ['name' => 'Pagamento', 'code' => '1'],
            ['name' => 'Recebimento', 'code' => '2']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_transacao');
    }
};
