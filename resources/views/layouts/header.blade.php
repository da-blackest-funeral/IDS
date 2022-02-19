@section('header')
    <div class="margin1200">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1"
                    aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar1">
                <ul class="navbar-nav mr-auto">

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
                            <a class="nav-link" href="https://moskva.03-okna.ru/total_order.php">IDS&nbsp;msk</a>
                        </li>
                    @endcan

                    @can('addOrders')

                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link dropdown-toggle"
                               type="button"
                               id="navbarDropdown1"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">Заказы</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item"
                                   href="/add_order.php">Добавить
                                    заказ</a>
                                <a class="dropdown-item" href="/load.php?route=admin/order/all">Предыдущие заказы</a>
                                <a class="dropdown-item" href="/load.php?route=admin/order/calc">Предыдущие расчеты</a>
                                <a
                                    class="dropdown-item"
                                    href="/load.php?route=admin/plan/steklopaket">Заказы
                                    по стеклопакетам</a>
                                <a
                                    class="dropdown-item"
                                    href="/load.php?route=admin/plan/podokonnik">Заказы
                                    по подоконникам</a>
                                <a
                                    class="dropdown-item"
                                    href="/load.php?route=admin/stat/control">Контроль
                                    качества на заказах</a>
                            </div>
                        </li>
                    @endcan

                    @can('seeManagement')
                        <li class="nav-item dropdown my-auto"> {{-- nav-item dropdown my-auto --}}
                            <a class="nav-link dropdown-toggle" id="navbarDropdown1" role="button"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">Управление</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="/load.php?route=admin/price/index">Цены на товары и
                                    услуги</a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/load.php?route=admin/phone/zadarma">Звонки</a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/load.php?route=admin/sborshik/graph">График
                                    работы сотрудников</a>

                                <a class="dropdown-item"
                                   href="/load.php?route=admin/stat/index&type=stat">Статистика</a>
                                <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=plan">План + стат.
                                    менеджеров</a>
                                <a class="dropdown-item"
                                   href="/load.php?route=admin/stat/index&type=proisvodstvo">Производство</a>
                                <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=phone">Настройка
                                    звонков</a>
                                <a class="dropdown-item"
                                   href="/load.php?route=admin/stat/index&type=user">Пользователи</a>
                                <a class="dropdown-item" href="/load.php?route=admin/stat/index&type=money">Деньги</a>
                                <a class="dropdown-item"
                                   href="/load.php?route=admin/stat/index&type=other">Прочее</a>
                            </div>
                        </li>
                    @endcan
                    @can('seeDocuments')
                        <li class="nav-item my-auto">
                            <a class="nav-link" href="#">Документы</a>
                        </li>
                    @endcan

                    @can('seePlan')
                        <li class="nav-item my-auto">
                            <a class="nav-link" href="/load.php?route=admin/plan/my">План<span
                                    id="plan_not_readed_hd"></span></a>
                        </li>
                    @endcan
                    @can('seeSpecs')
                        <li class="nav-item my-auto">
                            <a class="nav-link" href="#">Спецификации</a>
                        </li>
                    @endcan

                    @can('seeTodaysGraph')
                        <li class="nav-item my-auto">
                            <a class="nav-link" href="/load.php?route=admin/graph/deliv&date=<?= date('d.m.Y'); ?>">График
                                на
                                сегодня</a>
                        </li>
                    @endcan
                    @can('seeExtendedPlan')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">План
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="/load.php?route=admin/plan/my">Мой план</a>
                                <a class="dropdown-item" href="/load.php?route=admin/plan/all">План всех
                                    сотрудников</a>
                                <a class="dropdown-item" href="/load.php?route=admin/plan/dolgo">Долгосрочный план
                                    всех</a>
                                <a class="dropdown-item" href="/load.php?route=admin/plan/add_plan">Добавить и ред.
                                    задания</a>
                            </div>
                        </li>
                    @endcan
                    @can('seeNotificationsForInstallers')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="/news.php">Увед. для монт.</a>
                        </li>
                    @endcan
                    @can('seeWages')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">Зарплаты</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="/load.php?route=admin/master/zp">Зарплаты
                                    монтажникам</a>
                                <a class="dropdown-item" href="/load.php?route=admin/sborshik/graph">Зарплаты
                                    менеджерам</a>
                                <a class="dropdown-item" href="/load.php?route=admin/master/history">Бонусы
                                    для монтажников</a>
                            </div>
                        </li>
                    @endcan

                    @can('canCalculate')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="#">Рассчитать</a>
                        </li>
                    @endcan

                    @can('seeHisGraph')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="/load.php?route=admin/graph/mont">График</a>
                        </li>
                    @endcan

                    @can('seeGraphs')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="/load.php?route=admin/graph/all">Графики</a>
                        </li>
                    @endcan

                    @can('seeMap')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="/maps.php">Карта</a>
                        </li>
                    @endcan

                    @can('seeReceipts')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link" href="/load.php?route=admin/prihod/prihod">Поступления</a>
                        </li>
                    @endcan

                    @can('seeWarehouse')
                        <li class="nav-item dropdown my-auto">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Склад</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="#">Остатки на складе</a>
                                <a class="dropdown-item"
                                   href="#">История
                                    перемещений</a>
                            </div>
                        </li>
                    @endcan
                </ul>

                @auth()
                    <form method="POST" action="/logout" class="form-inline my-2 my-lg-0">
                        @csrf
                        <button class="btn btn-outline-info my-2 my-sm-0" name="sb_out" type="submit">Выйти</button>
                    </form>
                @endauth
                @guest()
                    <div class="form-inline my-2 my-lg-0">
                        <a class="btn btn-outline-info my-2 my-sm-0" href="/login">Войти</a>
                    </div>
                @endauth

            </div>
        </nav>
@show
