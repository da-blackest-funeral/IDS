<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $prefix = 'slopes';

//    protected function comment(string $table, string $comment) {
//        DB::statement("ALTER TABLE $table comment '$comment'");
//    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort');
            $table->boolean('status');
            $table->foreignId('category_id');
            $table->timestamps();
        });
//        $this->comment("{$this->prefix}", 'Конфигурация откоса');

        Schema::create("{$this->prefix}_colors", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_width", function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_montage_prices", function (Blueprint $table) {
            $table->id();
            $table->foreignId('slope_id');
            $table->foreignId('width_id')
                ->constrained("{$this->prefix}_width");
            $table->float('price');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_prices", function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')
                ->constrained("{$this->prefix}_colors");
            $table->foreignId('width_id')
                ->constrained("{$this->prefix}_width");
            $table->float('price');
            $table->timestamps();
        });
//        $this->comment("{$this->prefix}_prices", 'Цена определяется по ширине и цвету');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("{$this->prefix}");
        Schema::dropIfExists("{$this->prefix}_colors");
        Schema::dropIfExists("{$this->prefix}_width");
        Schema::dropIfExists("{$this->prefix}_montage_prices");
        Schema::dropIfExists("{$this->prefix}_prices");
    }
};
