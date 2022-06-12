<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
//        protected function comment(string $table, string $comment) {
//            DB::statement("ALTER TABLE $table comment '$comment'");
//        }

        protected $prefix = "mosquito_systems";

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::create("{$this->prefix}_italian", function (Blueprint $table) {
                $table->id();
                $table->integer('height');
                $table->integer('width');
                $table->float('price')->comment('Цена в долларах');
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create("{$this->prefix}_types", function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id');
                $table->string('yandex')
                    ->default('');
                $table->string('page_link')
                    ->default('');
                $table->string('measure_link')
                    ->default('')
                    ->comment('Ссылка на страницу замера');
                $table->float('salary')
                    ->default(0)
                    ->comment('Доп. зарплата монтажнику');
                $table->float('delivery')
                    ->comment('Цена за доставку');
                $table->text('description');
                $table->string('img')
                    ->default('');
                $table->integer('measure_time')
                    ->comment('Время замера в часах');
                $table->timestamps();
            });
//            $this->comment("{$this->prefix}_types", 'Тип москитных систем');
            Schema::create("{$this->prefix}_tissues", function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('link_page')->default('');
                $table->text('description');
                $table->float('cut_width')
                    ->comment('Ширина отреза, м.');
                $table->timestamps();
            });
//            $this->comment("{$this->prefix}_tissues", 'Ткани');

            Schema::create("{$this->prefix}_profiles", function (Blueprint $table) {
                $table->id();
                $table->string('name');
//            $table->foreignId('service_id');
                $table->softDeletes();
                $table->timestamps();
            });
//            $this->comment("{$this->prefix}_profiles", 'Профили москитных систем');

            Schema::create("{$this->prefix}_products", function (Blueprint $table) {
                $table->id();
                $table->foreignId('type_id')
                    ->constrained("{$this->prefix}_types");
                $table->foreignId('tissue_id')
                    ->constrained("{$this->prefix}_tissues");
                $table->foreignId('profile_id')
                    ->constrained("{$this->prefix}_profiles");
//            $table->foreignId('category_id');
                $table->float('price')
                    ->default(0);
                $table->timestamps();
            });
//            $this->comment("{$this->prefix}_products", 'Москитная сетка. Цена характеризуется тремя полями: тип, профиль, ткань.');

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
//            $this->comment("{$this->prefix}_additional", 'Обобщенная таблица с добавочными условиями');

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
//            $this->comment("{$this->prefix}_type_additional", 'Таблица, определяющая цены добавочных условий при определенном типе изделия');

            Schema::create("{$this->prefix}_product_additional", function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')
                    ->constrained("{$this->prefix}_products");
                $table->foreignId('additional_id')
                    ->constrained("{$this->prefix}_additional");
                $table->timestamps();
            });
//            $this->comment("{$this->prefix}_product_additional", 'Таблица, определяющая, есть ли у товара добавочная опция');

            Schema::create("{$this->prefix}_type_salary", function (Blueprint $table) {
                $table->id();
                $table->foreignId('type_id')
                    ->constrained("{$this->prefix}_types");
                $table->foreignId('additional_id')
                    ->constrained("{$this->prefix}_additional");
                $table->integer('count')
                    ->default(1);
                $table->float('salary');
                $table->float('salary_for_count')
                    ->default(0);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists("{$this->prefix}_types");
            Schema::dropIfExists("{$this->prefix}_tissues");
            Schema::dropIfExists("{$this->prefix}_profiles");
            Schema::dropIfExists("{$this->prefix}_products");
            Schema::dropIfExists("{$this->prefix}_groups");
            Schema::dropIfExists("{$this->prefix}_additional");
            Schema::dropIfExists("{$this->prefix}_type_group");
            Schema::dropIfExists("{$this->prefix}_type_additional");
            Schema::dropIfExists("{$this->prefix}_product_has_additional");
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    };
