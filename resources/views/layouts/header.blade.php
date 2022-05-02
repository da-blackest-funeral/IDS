<div>
    <nav class="px-3http://10.0.2.15:3000 navbar d-flex navbar-expand-lg navbar-dark bg-dark">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbar1"
                aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between px-4" id="navbar1">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">
                            <span style="font-size: 16px; font-weight: 600;">
                                IDS
                            </span>
                        <span style='font-size: 12px;'>
                  {{ auth()->user()->name ?? 'Вы не вошли в систему' }}
                </span>
                        <br/>
                    </a>
                </li>
                @can('loginToIdsMsk')
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
                                       href="{{ route('new-order') }}">Добавить
                                        заказ
                                    </a>
                                </li>
                            @endcan
                            <li>
                                <a class="dropdown-item" href="{{ route('all-orders') }}">Предыдущие заказы
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('all-calculations') }}">Предыдущие
                                    расчеты
                                </a>
                            </li>
                            @can('addOrders')
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="{{ route('glazed-windows-orders') }}">Заказы
                                        по стеклопакетам
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="{{ route('windowsills-orders') }}">Заказы
                                        по подоконникам
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="{{ route('control') }}">Контроль
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
                            <a class="dropdown-item" href="{{ route('prices') }}">Цены на товары и
                                услуги
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('calls') }}">Звонки</a>
                            <div class="dropdown-divider"></div>
                            @can('seeAllGraph')
                                <a class="dropdown-item" href="{{ route('all-graph') }}">График
                                    работы сотрудников
                                </a>
                            @endcan

                            <a class="dropdown-item"
                               href="{{ route('statistics') }}">Статистика
                            </a>
                            <a class="dropdown-item" href="{{ route('statistics-plan') }}">План + стат.
                                менеджеров
                            </a>
                            <a class="dropdown-item"
                               href="{{ route('production') }}">Производство
                            </a>
                            <a class="dropdown-item" href="{{ route('calls-settings') }}">Настройка
                                звонков
                            </a>
                            <a class="dropdown-item"
                               href="{{ route('users') }}">Пользователи
                            </a>
                            <a class="dropdown-item" href="{{ route('money') }}">Деньги</a>
                            @can('seeAdditional')
                                <a class="dropdown-item"
                                   href="{{ route('management-additional') }}">Прочее
                                </a>
                            @endcan
                        </div>
                    </li>
                @endcan
                @auth
                    @if(auth()->user()->isCollector())
                        <li class="nav-item my-auto">
                            <a class="btn nav-link" href="#">Список сеток на сборку</a>
                        </li>
                    @endif
                @endauth

                @can('seeDocuments')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="{{ route('documents') }}">Документы</a>
                    </li>
                @endcan

                @can('seePlan')
                    <li class="nav-item my-auto">
                        <a class="btn nav-link" href="{{ route('my-plan') }}">План<span
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
                            <a class="dropdown-item" href="{{ route('my-plan') }}">Мой план</a>
                            <a class="dropdown-item" href="{{ route('all-plan') }}">План всех
                                сотрудников
                            </a>
                            <a class="dropdown-item" href="{{ route('long-plan') }}">Долгосрочный план
                                всех
                            </a>
                            <a class="dropdown-item" href="{{ route('add-plan') }}">Добавить и ред.
                                задания
                            </a>
                        </div>
                    </li>
                @endcan

                @can('seeNotificationsForInstallers')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('news') }}">Увед. для монт.</a>
                    </li>
                @endcan

                @can('seeWages')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Зарплаты
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                            @if(auth()->user()->isEveningManager())
                                <a class="dropdown-item" href="{{ route('managers-wages') }}">Зарплаты
                                    менеджерам
                                </a>
                            @else
                                <a class="dropdown-item" href="{{ route('installers-wages') }}">Зарплаты
                                    монтажникам
                                </a>
                                <a class="dropdown-item" href="{{ route('managers-wages') }}">Зарплаты
                                    менеджерам
                                </a>
                                <a class="dropdown-item" href="{{ route('bonuses') }}">Бонусы
                                    для монтажников
                                </a>
                            @endif
                        </div>
                    </li>
                @endcan

                @can('canCalculate')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('new-order') }}">Рассчитать</a>
                    </li>
                @endcan

                @can('seeHisGraph')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('my-graph') }}">График</a>
                    </li>
                @endcan

                @can('seeGraphsMaps')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('installers-graphs') }}">Графики</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('map') }}">Карта</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('entrances') }}">Поступления</a>
                    </li>
                @endcan

                @can('seeWarehouse')
                    <li class="nav-item dropdown my-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Склад
                        </a>
                        @if(auth()->user()->isInstaller())
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="{{ route('remains') }}">Остатки на складе</a>
                                <a class="dropdown-item" href="{{ route('movements-history') }}">История перемещений</a>
                            </div>
                        @else
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="{{ route('warehouses') }}">Склады</a>
                                <a class="dropdown-item" href="{{ route('warehouse-wraps') }}">Склад пленок</a>
                                <a class="dropdown-item" href="{{ route('warehouse-template') }}">Шаблон склада</a>

                                @can('seeInventory')
                                    <a class="dropdown-item"
                                       href="{{ route('inventory') }}">Инвентаризации
                                    </a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('issuance') }}">Текущие выдачи</a>
                            </div>
                        @endif
                    </li>
                @endcan

                @can('seeEarning')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('earning') }}">Заработок</a>
                    </li>
                @endcan

                @can('seeInfoForInstallers')
                    <li class="nav-item dropdown my-auto">
                        <a class="btn nav-link" href="{{ route('notifications') }}">Уведомления</a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Информация
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                            <a class="dropdown-item" href="{{ route('info-map') }}">Карта</a>
                            <a class="dropdown-item" href="{{ route('info') }}">Информация</a>
                            <a class="dropdown-item" href="{{ route('info-framed-mosquito-nets') }}">
                                Рамные москитные сетки (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-mosquito-doors') }}">
                                Москитные двери (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-sliding-nets') }}">
                                Раздвижные сетки (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-rolled-nets') }}">
                                Рулонные сетки(замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-pleat-grids-italy') }}">
                                Сетки плиссе Италия (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-grids-wing') }}">
                                Сетки крыло (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-trapezoidal-grid') }}">
                                Сетка трапециевидная(замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-pluggable-grids-vsn') }}">
                                Вставные сетки VSN (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-pleat-grids-rus') }}">
                                Сетки плиссе Россия (замер)
                            </a>
                            <a class="dropdown-item" href="{{ route('info-hooked-grids') }}">
                                Москитная сетка на зацепах
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a href="{{ route('earning') }}" class="btn nav-link">
                            Сумма к выплате: {{ $earning ?? '10.000 рублей' }}
                        </a>
                    </li>
                    <li class="nav-item dropdown my-auto">
                        <a href="{{ route('ratings') }}" class="btn nav-link">
                            <span style="color: #ffc107">
                                Ваш рейтинг: {{ $ratins ?? '10' }}
                            </span>
                        </a>
                    </li>
                @endcan

            </ul>

            @auth()
                <form method="POST" action="{{ route('logout') }}" class="form-inline">
                    @csrf
                    <button class="btn btn-outline-info my-2 my-sm-0" name="sb_out" type="submit">
                        Выйти
                    </button>
                </form>
            @endauth
            @guest()
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="{{ route('login') }}">
                        Войти
                    </a>
                </div>
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="{{ route('register') }}">
                        Зарегистрироваться
                    </a>
                </div>
            @endauth

        </div>
    </nav>
</div>
