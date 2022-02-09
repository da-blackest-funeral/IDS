<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    protected $prefix = "mosquito_systems";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("{$this->prefix}_types", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('yandex');
            $table->string('page_link');
            $table->string('measure_link')
                ->comment('Ссылка на страницу замера');
            $table->float('salary')
                ->comment('Доп. зарплата монтажнику');
            $table->float('price');
            $table->text('description');
            $table->string('img');
            $table->integer('measure_time')
                ->comment('Время замера в часах');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_types", 'Тип москитных систем');

        Schema::create("{$this->prefix}_tissues", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link_page');
            $table->text('description');
            $table->float('cut_width')
                ->comment('Ширина отреза, м.');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_tissues", 'Ткани');

        Schema::create("{$this->prefix}_profiles", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('service_id')
                ->constrained('services');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_profiles", 'Профили москитных систем');

        Schema::create("{$this->prefix}_products", function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                ->constrained("{$this->prefix}_types");
            $table->foreignId('tissue_id')
                ->constrained("{$this->prefix}_tissues");
            $table->foreignId('profile_id')
                ->constrained("{$this->prefix}_profiles");
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->float('price');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_products", 'Москитная сетка. Цена характеризуется тремя полями: тип, профиль, ткань.');

        Schema::create("{$this->prefix}_groups", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_additional", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->foreignId('group_id')
                ->constrained("{$this->prefix}_groups");
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_additional", 'Обобщенная таблица с добавочными условиями');

        Schema::create("{$this->prefix}_type_group", function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                ->constrained("{$this->prefix}_types");
            $table->foreignId('group_id')
                ->constrained("{$this->prefix}_groups");
            $table->integer('sort');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_type_additional", function (Blueprint $table) {
            $table->id();
            $table->foreignId('additional_id')
                ->constrained("{$this->prefix}_additional");
            $table->foreignId('type_id')
                ->constrained("{$this->prefix}_types");
            $table->float('price');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_type_additional", 'Таблица, определяющая цены добавочных условий при определенном типе изделия');

        Schema::create("{$this->prefix}_ttp_additional", function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained("{$this->prefix}_products");
            $table->foreignId('type_additional_id')
                ->constrained("{$this->prefix}_type_additional");
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_ttp_additional", 'Таблица, определяющая, есть ли у товара добавочная опция');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("{$this->prefix}_types");
        Schema::dropIfExists("{$this->prefix}_tissues");
        Schema::dropIfExists("{$this->prefix}_profiles");
        Schema::dropIfExists("{$this->prefix}_products");
        Schema::dropIfExists("{$this->prefix}_groups");
        Schema::dropIfExists("{$this->prefix}_additional");
        Schema::dropIfExists("{$this->prefix}_type_group");
        Schema::dropIfExists("{$this->prefix}_type_additional");
        Schema::dropIfExists("{$this->prefix}_product_has_additional");
    }
};
