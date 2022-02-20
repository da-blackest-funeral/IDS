<div>
    <nav class="px-3http://10.0.2.15:3000 navbar d-flex navbar-expand-lg navbar-dark bg-dark">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbar1"
                aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav">

                <li class="nav-item active">
                    <a class="nav-link" href="#">
                            <span style="font-size: 16px; font-weight: 600;">
                                IDS
                            </span>
                        <span style='font-size: 12px;'>
                  {{ auth()->user()->name ?? 'Вы не вошли в систему' }}
                </span>
                        <br/>
                    </a>
                </li>
                @can('canLoginToIdsMsk')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="https://moskva.03-okna.ru/total_order.php">IDS&nbsp;msk</a>
                    </li>
                @endcan


                @can('seeOrders')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           id="navBarOrders"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false">Заказы
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navBarOrders">
                            @can('addOrders')
                                <li>
                                    <a class="dropdown-item"
                                       href="/add_order.php">Добавить
                                        заказ
                                    </a>
                                </li>
                            @endcan
                            <li>
                                <a class="dropdown-item" href="/load.php?route=admin/order/all">Предыдущие заказы
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/load.php?route=admin/order/calc">Предыдущие
                                    расчеты
                                </a>
                            </li>
                            @can('addOrders')
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/load.php?route=admin/plan/steklopaket">Заказы
                                        по стеклопакетам
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/load.php?route=admin/plan/podokonnik">Заказы
                                        по подоконникам
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/load.php?route=admin/stat/control">Контроль
                                        качества на заказах
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('seeManagement')
                    <li class="nav-item dropdown my-auto">
                        <a href='#' class="btn nav-link dropdown-toggle" id="navBarManagement" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Управление
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navBarManagement">
                            <a class="dropdown-item" href="/load.php?route=admin/price/index">Цены на товары и
                                услуги
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/load.php?route=admin/phone/zadarma">Звонки</a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="/load.php?route=admin/sborshik/graph">График
                                работы сотрудников
                            </a>

                            <a class="dropdown-item"
                               href="/load.php?route=admin/stat/index&type=stat">Статистика
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=plan">План + стат.
                                менеджеров
                            </a>
                            <a class="dropdown-item"
                               href="/load.php?route=admin/stat/index&type=proisvodstvo">Производство
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=phone">Настройка
                                звонков
                            </a>
                            <a class="dropdown-item"
                               href="/load.php?route=admin/stat/index&type=user">Пользователи
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=money">Деньги</a>
                            <a class="dropdown-item"
                               href="/load.php?route=admin/stat/index&type=other">Прочее
                            </a>
                        </div>
                    </li>
                @endcan

                @can('seeDocuments')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="#">Документы</a>
                    </li>
                @endcan

                @can('seePlan')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/plan/my">План<span
                                id="plan_not_readed_hd"></span></a>
                    </li>
                @endcan
                @can('seeSpecs')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="#">Спецификации</a>
                    </li>
                @endcan

                @can('seeTodaysGraph')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/graph/deliv&date=<?= date('d.m.Y'); ?>">
                            График
                            на
                            сегодня
                        </a>
                    </li>
                @endcan

                @can('seeExtendedPlan')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link dropdown-toggle" href="#" id="navBarPlan" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">План
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navBarPlan">
                            <a class="dropdown-item" href="/load.php?route=admin/plan/my">Мой план</a>
                            <a class="dropdown-item" href="/load.php?route=admin/plan/all">План всех
                                сотрудников
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/plan/dolgo">Долгосрочный план
                                всех
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/plan/add_plan">Добавить и ред.
                                задания
                            </a>
                        </div>
                    </li>
                @endcan

                @can('seeNotificationsForInstallers')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/news.php">Увед. для монт.</a>
                    </li>
                @endcan

                @can('seeWages')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Зарплаты
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                            <a class="dropdown-item" href="/load.php?route=admin/master/zp">Зарплаты
                                монтажникам
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/sborshik/graph">Зарплаты
                                менеджерам
                            </a>
                            <a class="dropdown-item" href="/load.php?route=admin/master/history">Бонусы
                                для монтажников
                            </a>
                        </div>
                    </li>
                @endcan

                @can('canCalculate')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="#">Рассчитать</a>
                    </li>
                @endcan

                @can('seeHisGraph')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/graph/mont">График</a>
                    </li>
                @endcan

                @can('seeGraphsMaps')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/graph/all">Графики</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/maps.php">Карта</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/prihod/prihod">Поступления</a>
                    </li>
                @endcan

                @can('seeWarehouse')
                    <li class="nav-item dropdown my-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Склад</a>
                        @if(auth()->user()->isInstaller())
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="#">Остатки на складе</a>
                                <a class="dropdown-item" href="#">История перемещений</a>
                            </div>
                        @else
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="/load.php?route=admin/sklad/sklad">Склады</a>
                                <a class="dropdown-item" href="/load.php?route=admin/sklad/plenka">Склад пленок</a>
                                <a class="dropdown-item" href="/sklad.php">Шаблон склада</a>

                                @can('seeInventory')
                                    <a class="dropdown-item"
                                       href="/load.php?route=admin/sklad/inventory">Инвентаризации</a>
                                @endcan

                                <a class="dropdown-item" href="/load.php?route=admin/sklad/vidat_now">Текущие выдачи</a>
                            </div>
                        @endif
                    </li>
                @endcan

                @can('seeInfoForInstallers')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="/load.php?route=admin/prihod/prihod">Уведомления</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Информация
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                            <a class="dropdown-item" href="#">Карта</a>
                            <a class="dropdown-item" href="#">Информация</a>
                            <a class="dropdown-item" href="#">Рамные москитные сетки (замер)</a>
                            <a class="dropdown-item" href="#">Москитные двери (замер)</a>
                            <a class="dropdown-item" href="#">Раздвижные сетки (замер)</a>
                            <a class="dropdown-item" href="#">Рулонные сетки(замер)</a>
                            <a class="dropdown-item" href="#">Сетки плиссе Италия (замер)</a>
                            <a class="dropdown-item" href="#">Сетки крыло (замер)</a>
                            <a class="dropdown-item" href="#">Сетка трапециевидная(замер)</a>
                            <a class="dropdown-item" href="#">Вставные сетки VSN (замер)</a>
                            <a class="dropdown-item" href="#">Сетки плиссе Россия (замер)</a>
                            <a class="dropdown-item" href="#">Москитная сетка на зацепах</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a href="#" class="btn nav-link">Сумма к выплате: {{ '10.000 рублей' }}</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a href="#" class="btn nav-link">
                            <span style="color: #ffc107">
                                Ваш рейтинг: {{ '10' }}
                            </span>
                        </a>
                    </li>
                @endcan

            </ul>

            @auth()
                <form method="POST" action="/logout" class="ms-5 form-inline">
                    @csrf
                    <button class="btn btn-outline-info my-2 my-sm-0" name="sb_out" type="submit">Выйти</button>
                </form>
            @endauth
            @guest()
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="/login">Войти</a>
                </div>
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="/register">Зарегистрироваться</a>
                </div>
            @endauth

        </div>
    </nav>
</div>
