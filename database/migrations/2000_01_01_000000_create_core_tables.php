<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {

//        protected function comment(string $table, string $comment) {
//            DB::statement("ALTER TABLE $table comment '$comment'");
//        }

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::create('system_variables', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('value');
                $table->text('description');
            });

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            Schema::create('salaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->float('sum');
                $table->text('reason');
                $table->timestamps();
            });

            Schema::create('installers_salaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('installer_id');
                $table->foreignId('order_id');
                $table->foreignId('category_id');
                $table->float('sum');
                $table->text('comment');
                $table->boolean('status')
                    ->comment('Спросить что это');
                $table->float('changed_sum')
                    ->comment('Спросить можно ли без этого');
                $table->foreignId('created_user_id');
                $table->enum('type', ['Монтаж', 'Без монтажа'])
                    ->comment('Тип выплаты - за монтаж, за бензин и т.д.');
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });

            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('parent_id')
                    ->comment('Ссылка из подкатегории на супер-категорию')
                    ->nullable();
                $table->timestamps();
            });

            Schema::create('types_windows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('sort')
                    ->default(0);
                $table->timestamps();
            });
//            $this->comment('types_windows', 'Типы окон - Алюминиевые, окна из ПВХ и т.д.');

            Schema::create('category_has_method', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id');
                $table->string('method')->nullable();
                $table->timestamps();
            });

            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained('users');
                $table->integer('delivery')
                    ->default(0);
                $table->boolean('need_delivery')
                    ->default(true);
                $table->integer('installation')
                    ->default(0);
                $table->float('price');
                $table->foreignId('installer_id')
                    ->constrained('users');
                $table->float('discounted_price')
                    ->comment('Цена со скидкой');
                $table->boolean('status')
                    ->default(false)
                    ->comment('Выполнен заказ или нет');
                $table->boolean('measuring')
                    ->comment('Нужен ли замер');
                $table->float('measuring_price')
                    ->default(0);
                $table->float('discounted_measuring_price')
                    ->default(0);
                $table->text('comment');
                $table->float('service_price')
                    ->default(0)
                    ->comment('Цена услуги');
                $table->float('sum_after')
                    ->default(0)
                    ->comment('Спросить что это.');
                $table->integer('products_count')
                    ->comment('Количество товаров');
                $table->float('taken_sum')
                    ->default(0)
                    ->comment('Спросить что это.');
                $table->float('installing_difficult')
                    ->comment('Коэффициент сложности монтажа');
                $table->boolean('is_private_person')
                    ->comment('1 - физическое лицо, 0 - юридическое.');
                $table->boolean('done_status')
                    ->default(false)
                    ->comment('Статус завершения заказа');
                $table->boolean('is_company_car')
                    ->default(false)
                    ->comment('Для доставки: была ли взята машина компании');
                $table->float('prepayment')
                    ->default(0)
                    ->comment('Предоплата');
                $table->boolean('installing_is_done')
                    ->default(false);
                $table->text('structure')
                    ->comment('Текстовое описание всех составляющих заказа');
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id');
                $table->foreignId('user_id');
                $table->foreignId('category_id');
                $table->string('name');
                $table->integer('count');
                $table->string('comment')
                    ->default('Без комментария')
                    ->comment('Примечание к позиции');
                $table->boolean('installation_id')
                    ->default(0);
                $table->json('data')
                    ->comment('Все дополнительные данные о заказе');
                $table->timestamps();
                $table->softDeletes();
            });

//            Schema::create('deliveries', function (Blueprint $table) {
//                $table->id();
//                $table->foreignId('order_id');
//                $table->float('price');
//                $table->float('discounted_price');
//                $table->float('discounted_price_km')
//                    ->comment('Уточнить что это');
//                $table->integer('range')
//                    ->comment('Дальность доставки');
//                $table->integer('loaders_count')
//                    ->comment('Количество грузчиков');
//                $table->integer('additional_visits')
//                    ->comment('Количество дополнительных выездов');
//                $table->float('price_additional_visits')
//                    ->comment('Цена за дополнительные выезды');
//                $table->boolean('discharge')
//                    ->comment('Выгрузка');
//                $table->dateTime('discharged_at')
//                    ->comment('Дата выгрузки');
//                $table->float('gazel_price')
//                    ->comment('Цена за доставку газелью');
//                $table->timestamps();
//                $table->softDeletes();
//            });

            Schema::create('managements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id');
                $table->float('management_wage')
                    ->comment('В прошлой это называлось zp_upravlenie');
                $table->float('type_sale');
                $table->float('type_sale_manager');
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create('wishes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id');
                $table->text('wish');
                $table->boolean('wish_syn')
                    ->comment('Не знаю что это');
                $table->date('wish_syn_at');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists('users');
            Schema::dropIfExists('categories');
            Schema::dropIfExists('services');
            Schema::dropIfExists('types_windows');
            Schema::dropIfExists('orders');
            Schema::dropIfExists('products');
            Schema::dropIfExists('deliveries');
            Schema::dropIfExists('managements');
            Schema::dropIfExists('wishes');
        }
    };
