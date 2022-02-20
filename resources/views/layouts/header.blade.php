@section('header')
    <div>
        {{-- Вывод сообщений об ошибках и об успехе --}}
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbar1"
                    aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar1">
                <ul class="navbar-nav mr-auto">
                    {{-- Выбирается какую ссылку выводить разным пользователям на кнопку IDS --}}
                    {{--<!--                    --><?php--}}
                    {{--//                    if ($_SESSION['priority'] == 3) $url_main = "/load.php?route=admin/sborshik/list";--}}
                    {{--//                    else $url_main = "/load.php?route=admin/order/all";--}}
                    {{--//                    ?>--}}

                    <li class="nav-item active">
                        <a class="nav-link" href="#">
                            <span style="font-size: 16px; font-weight: 600;">
                                IDS
                            </span>
                            <span style='font-size: 12px;'>
                  {{ auth()->user()->name ?? 'Вы не вошли в систему' }}
                </span>
                            <br/>
                            {{--                            <span style="font-size: 12px;">--}}
                            {{--                  Пр-во: <?= $col_day_proizvodstvo['price']; ?> дн.--}}
                            {{--                </span>--}}
                        </a>
                    </li>
                    {{-- Если у пользователя номер роли равен 2 или 7 --}}
                    {{--<!--                    --><?php //if (in_array($_SESSION['priority'], [2, 7])) { ?>--}}
                    @can('canLoginToIdsMsk')
                        <li class="nav-item my-auto">
                            <a class="btn nav-link" href="https://moskva.03-okna.ru/total_order.php">IDS&nbsp;msk</a>
                        </li>
                    @endcan

                    {{--<!--                --><?php //} ?>--}}
                    {{-- Для монтажников выводим следующее (роль №3) --}}
                    {{--                    <?php if ($_SESSION['priority'] == 3) { ?>--}}

                    {{--                    <li class="nav-item my-auto">--}}
                    {{--                        <a class="nav-link" href="/load.php?route=admin/sborshik/list">Список сеток на сборку</a>--}}
                    {{--                    </li>--}}

                    {{--<!--                --><?php //} ?>--}}


                    @can('addOrders')
                        {{--<!--                    --><?php //if (in_array($_SESSION['priority'], [0, 1, 2, 4, 6, 7])) { ?>--}}
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
                                <li>
                                    <a class="dropdown-item"
                                       href="/add_order.php">Добавить
                                        заказ
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/load.php?route=admin/order/all">Предыдущие заказы
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/load.php?route=admin/order/calc">Предыдущие
                                        расчеты
                                    </a>
                                </li>
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
                            </ul>
                        </li>
                    @endcan

                    @can('seeManagement')
                        <li class="nav-item dropdown my-auto"> {{-- nav-item dropdown my-auto --}}
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

                    @can('seeGraphs')
                        <li class="nav-item dropdown my-auto">
                            <a class="btn nav-link" href="/load.php?route=admin/graph/all">Графики</a>
                        </li>
                    @endcan

                    @can('seeMap')
                        <li class="nav-item dropdown my-auto">
                            <a class="btn nav-link" href="/maps.php">Карта</a>
                        </li>
                    @endcan

                    @can('seeReceipts')
                        <li class="nav-item dropdown my-auto">
                            <a class="btn nav-link" href="/load.php?route=admin/prihod/prihod">Поступления</a>
                        </li>
                    @endcan

                    @can('seeWarehouse')
                        <li class="nav-item dropdown my-auto">
                            <a class="btn nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Склад
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="#">Остатки на складе</a>
                                <a class="dropdown-item"
                                   href="#">История
                                    перемещений
                                </a>
                            </div>
                        </li>
                    @endcan

                    {{--                    <li class="nav-item dropdown my-auto">--}}
                    {{--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"--}}
                    {{--                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Информация</a>--}}
                    {{--                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">--}}
                    {{--                            <a class="dropdown-item" href="/maps.php">Карта</a>--}}
                    {{--                            <a class="dropdown-item" href="/info.php">Информация</a>--}}
                    {{--                            <?php--}}
                    {{--                            $sql_type_header = $mysqli->query("SELECT * FROM `type` ORDER BY `id_type`");--}}
                    {{--                            while ($res_type_hreader = $sql_type_header->fetch_assoc()) {--}}
                    {{--                            ?>--}}
                    {{--                            <a class="dropdown-item" target="_blank"--}}
                    {{--                               href="<?= $res_type_hreader['link_zamer']; ?>"><?= $res_type_hreader['name']; ?>--}}
                    {{--                                (замер)</a>--}}
                    {{--                            <?php--}}
                    {{--                            }--}}
                    {{--                            ?>--}}
                    {{--                            <a class="dropdown-item" target="_blank"--}}
                    {{--                               href="https://03-okna.ru/moskitnye-setki-na-zacepax/">Москитная--}}
                    {{--                                сетка на зацепах</a>--}}

                    {{--                        </div>--}}
                    {{--                    </li>--}}


                    {{--                    <?php--}}
                    {{--                    if (isset($_SESSION['priority']) and $_SESSION['priority'] == 1) {--}}
                    {{--                    $summ_payment = 0;--}}
                    {{--                    $sql_zp = $mysqli->query("SELECT `order_mont_zp`.`id_user_created`,`order_mont_zp`.`date`,`order_mont_zp`.`status`,`order_mont_zp`.`ai`,`order_mont_zp`.`id_order`, `order_mont_zp`.`id_user`, `order_mont_zp`.`price`, `order_mont_zp`.`comment`, `order_mont_zp`.`ai_order`, `order_mont_zp`.`price_change` FROM `order_mont_zp` INNER JOIN `clientbase` ON `clientbase`.`id_order` = `order_mont_zp`.`id_order` and `clientbase`.`status`='Выполнен' WHERE 1=1 and `order_mont_zp`.`id_order`> 0  and `order_mont_zp`.`id_user`=" . $_SESSION['id_user'] . " and `order_mont_zp`.`status`=0");--}}
                    {{--                    while ($res_zp = $sql_zp->fetch_assoc()) {--}}
                    {{--                    if ($res_zp['price_change'] != 0) {--}}
                    {{--                    $summ_payment = $summ_payment + $res_zp['price_change'];--}}
                    {{--                    } else {--}}
                    {{--                    $summ_payment = $summ_payment + $res_zp['price'];--}}
                    {{--                    }--}}
                    {{--                    }--}}
                    {{--                    $sql_zp = $mysqli->query("SELECT * FROM `order_mont_zp` WHERE `order_mont_zp`.`id_order`=0 and `order_mont_zp`.`id_user`=" . $_SESSION['id_user'] . " and `order_mont_zp`.`status`=0");--}}
                    {{--                    while ($res_zp = $sql_zp->fetch_assoc()) {--}}
                    {{--                    if ($res_zp['price_change'] != 0) {--}}
                    {{--                    $summ_payment = $summ_payment + $res_zp['price_change'];--}}
                    {{--                    } else {--}}
                    {{--                    $summ_payment = $summ_payment + $res_zp['price'];--}}
                    {{--                    }--}}
                    {{--                    }--}}
                    {{--                    }--}}
                    {{--                    ?>--}}

                    {{--                    <li class="nav-item my-auto">--}}
                    {{--                        <a class="nav-link" href="">Сумма к выплате: <span--}}
                    {{--                                id="summ_to_payment"><?= $summ_payment; ?></span>--}}
                    {{--                            руб</a>--}}
                    {{--                    </li>--}}

                    {{--                    <li class="nav-item my-auto">--}}
                    {{--                        <a href="/load.php?route=admin/stat/ratings" class="btn nav-tab">--}}
                    {{--                        <span style="color: goldenrod">--}}
                    {{--                          Ваш рейтинг: <?php if ($rate) echo $rate; else echo 'нет данных'; ?>--}}
                    {{--                      </span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}

                    {{--                    <?php if ($res_bonus_summ = get_summ_bonus_master($_SESSION['id_user'], $mysqli)) { ?>--}}

                    {{--                    <li class="nav-item dropdown my-auto">--}}
                    {{--                        <a class="nav-link" href="/load.php?route=admin/master/history">Бонусная часть:--}}
                    {{--                            <span><?= $res_bonus_summ['summ']; ?></span> руб</a>--}}
                    {{--                    </li>--}}


                    {{--                    <?php } ?>--}}
                    {{--                    <?php } ?>--}}


                    {{--                    <?php } ?>--}}


                    {{--                    <?php } ?>--}}




                    {{--                    <?php if (in_array($_SESSION['priority'], [0, 2, 3, 4, 7])) { ?>--}}

                    {{--                    <li class="nav-item dropdown my-auto">--}}
                    {{--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"--}}
                    {{--                           data-bs-toggle="dropdown"--}}
                    {{--                           aria-haspopup="true" aria-expanded="false">Склад</a>--}}
                    {{--                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">--}}
                    {{--                            <a class="dropdown-item" href="/load.php?route=admin/sklad/sklad">Склады</a>--}}
                    {{--                            <a class="dropdown-item" href="/load.php?route=admin/sklad/plenka">Склад пленок</a>--}}
                    {{--                            <a class="dropdown-item" href="/sklad.php">Шаблон склада</a>--}}
                    {{--                            <?php if (in_array($_SESSION['priority'], [0, 2, 7])) { ?>--}}
                    {{--                            <a class="dropdown-item" href="/load.php?route=admin/sklad/inventory">Инвенатризации</a>--}}
                    {{--                            <?php } ?>--}}
                    {{--                            <a class="dropdown-item" href="/load.php?route=admin/sklad/vidat_now">Текущие выдачи</a>--}}
                    {{--                        </div>--}}
                    {{--                    </li>--}}

                    {{--                    <?php } ?>--}}

                    {{--                    <?php if (in_array($_SESSION['priority'], [3, 5, 6])) { ?>--}}

                    {{--                    <li class="nav-item dropdown my-auto">--}}
                    {{--                        <a class="nav-link" href="/load.php?route=admin/sborshik/graph">Заработок</a>--}}
                    {{--                    </li>--}}

                    {{--                    <?php } ?>--}}


                </ul>
                <!--<form class="form-inline mr-4 my-2 my-lg-0">
                            <input class="form-control mr-sm-2" type="search" placeholder="Поиск по номеру заказа" aria-label="Search">
                            <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Поиск по номеру заказа</button>
                        </form>-->

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
    </div>


    {{--        <?php if (in_array($_SESSION['priority'], [0, 2, 4, 7])) { ?>--}}
    {{--        <div id="get_phone_pause"></div>--}}
    {{--        <?php } ?>--}}


    {{--        <?php--}}

    {{--        $array_url_not_show_plan = [--}}
    {{--        '/statistic.php',--}}
    {{--        '/report_master_zp.php',--}}
    {{--        ];--}}

    {{--        $array_url_not_show_plan_full = [--}}
    {{--        '/load.php?route=admin/stat/type_traff',--}}
    {{--        ];--}}

    {{--        $REQUEST_URI = $_SERVER['REQUEST_URI'];--}}
    {{--        $REQUEST_URI_PART = explode('?', $_SERVER['REQUEST_URI']);--}}
    {{--        $REQUEST_URI_PART = $REQUEST_URI_PART[0];--}}

    {{--        if (!in_array($REQUEST_URI_PART, $array_url_not_show_plan)) {--}}

    {{--        if (function_exists('get_user_plan_stat')) {--}}
    {{--        $res_user = get_user($_SESSION['id_user'], $mysqli);--}}
    {{--        $id_user_clientbase = $res_user['id_user_clientbase'];--}}
    {{--        //$res_pmu = get_user_plan_stat(1,4,date('m'),date('Y'),$mysqli,$id_user_clientbase);--}}
    {{--        $res_pmu = get_array_plan_new($_SESSION['id_user'], date('m'), date('Y'), $mysqli);--}}

    {{--        /*echo "<pre>";--}}
    {{--                    print_r($res_pmu);--}}
    {{--                    echo "</pre>";*/--}}


    {{--        if (isset($res_pmu['summ_at_day'])) {--}}
    {{--        $total_summ_today = 0;--}}
    {{--        $total_summ_end_week = 0;--}}
    {{--        $num_week_today = date("N", time());--}}

    {{--        if ($num_week_today == 5) $date_end_week = date("Y-m-d");--}}
    {{--        if ($num_week_today == 4) $date_end_week = date("Y-m-d", strtotime('+1 day'));--}}
    {{--        if ($num_week_today == 3) $date_end_week = date("Y-m-d", strtotime('+2 day'));--}}
    {{--        if ($num_week_today == 2) $date_end_week = date("Y-m-d", strtotime('+3 day'));--}}
    {{--        if ($num_week_today == 1) $date_end_week = date("Y-m-d", strtotime('+4 day'));--}}
    {{--        if ($num_week_today == 6) $date_end_week = date("Y-m-d", strtotime('-1 day'));--}}
    {{--        if ($num_week_today == 7) $date_end_week = date("Y-m-d", strtotime('-2 day'));--}}


    {{--        foreach ($res_pmu['array_work_date'] as $one_work_date) {--}}

    {{--        if ($one_work_date <= date("Y-m-d")) {--}}
    {{--        $total_summ_today = $total_summ_today + $res_pmu['summ_at_day'];--}}
    {{--        }--}}
    {{--        if ($one_work_date <= $date_end_week) {--}}
    {{--        $total_summ_end_week = $total_summ_end_week + $res_pmu['summ_at_day'];--}}
    {{--        }--}}
    {{--        if ($one_work_date >= $date_end_week) break;--}}

    {{--        }--}}

    {{--        }--}}

    {{--        if (isset($res_pmu)) {--}}
    {{--        if (isset($res_pmu['real_plan']) and $res_pmu['real_plan'] > 0) {--}}
    {{--        ?>--}}
    {{--        <a href="https://03-okna.ru/load.php?route=admin/stat/all_new" style="text-decoration: none;">--}}
    {{--            <div class="plan_prodaj">--}}
    {{--                <div class="plan_prodaj_info">План продаж на <?= $array_month[date('m')]; ?>--}}
    {{--                    : <?= to_summ($res_pmu['real_plan']); ?> руб--}}
    {{--                </div>--}}
    {{--                <div class="plan_prodaj_info">Выполнено сейчас: <?= to_summ($res_pmu['summ']); ?> руб--}}
    {{--                    (<?= round((1 - ($res_pmu['real_plan'] - $res_pmu['summ']) / $res_pmu['real_plan']) * 100); ?>--}}
    {{--                    % от плана)--}}
    {{--                </div>--}}

    {{--                <div class="plan_prodaj_info">План на конец дня: <?= to_summ($total_summ_today); ?> руб</div>--}}
    {{--                <div class="plan_prodaj_info">План на конец недели: <?= to_summ($total_summ_end_week); ?>--}}
    {{--                    руб--}}
    {{--                </div>--}}

    {{--                <div class="plan_prodaj_info" style="display:none;">Процент выполнения плана на текущий--}}
    {{--                    день: <?= round(($res_pmu['summ'] / (($res_pmu['real_plan'] / 22) * date('d') * 0.76)) * 100); ?>--}}
    {{--                    %--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </a>--}}
    {{--        <?php--}}
    {{--        }--}}
    {{--        }--}}
    {{--        }--}}

    {{--        }--}}
    {{--        ?>--}}

    {{--        <?php--}}

    {{--        if (!in_array($REQUEST_URI, $array_url_not_show_plan_full)) {--}}

    {{--        $arr_date = get_date_start_end();--}}
    {{--        $col_plan_dop_baza = get_plan_week_dop_basa($_SESSION['id_user'], $arr_date[0], $arr_date[1], $mysqli, 'plan');--}}
    {{--        $col_plan_vhod = get_plan_user_col_vhod($_SESSION['id_user'], date('Y-m-d'), $mysqli);--}}
    {{--        if ($col_plan_vhod > 0 or $col_plan_dop_baza > 0) {--}}
    {{--        ?>--}}
    {{--        <div class="plan_prodaj_vhod">--}}
    {{--            <?php--}}

    {{--            if ($col_plan_vhod > 0) {--}}
    {{--            $col_added = get_col_added_order($_SESSION['id_user'], date('Y-m-d'), $mysqli);--}}
    {{--            $col_vhod = get_col_user_stat_vhod($_SESSION['id_user'], date('Y-m-d'), $mysqli);--}}
    {{--            ?>--}}
    {{--            План по заказам + входящим на сегодня: <?= $col_plan_vhod; ?> шт--}}
    {{--            &nbsp;&nbsp;--}}
    {{--            (Добавлено заказов: <?= $col_added; ?> шт--}}
    {{--            &nbsp;&nbsp;--}}
    {{--            Добавлено входящих: <?= $col_vhod; ?> шт)--}}
    {{--            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}

    {{--            <?php--}}
    {{--            }--}}

    {{--            if ($col_plan_dop_baza > 0) {--}}

    {{--            $col_done_mont = get_plan_week_dop_basa($_SESSION['id_user'], $arr_date[0], $arr_date[1], $mysqli, 'col');--}}
    {{--            ?>--}}
    {{--            План по доп.базе на эту неделю: <?= $col_plan_dop_baza; ?> шт--}}
    {{--            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
    {{--            (Добавлено: <?= $col_done_mont; ?> шт)--}}
    {{--            <?php--}}
    {{--            }--}}
    {{--            ?>--}}
    {{--        </div>--}}
    {{--        <?php--}}
    {{--        }--}}
    {{--        }--}}
    {{--        ?>--}}


    {{--        <?php--}}
    {{--        if (!in_array($REQUEST_URI, $array_url_not_show_plan_full) and in_array($_SESSION['priority'], [0])) {--}}
    {{--        $array_param_temp = [--}}
    {{--        'date_start' => date('Y-m-01'),--}}
    {{--        'date_end' => date('Y-m-d'),--}}
    {{--        ];--}}
    {{--        $array_sale_tovari_in_order = get_array_sale_tovari_in_order($array_param_temp, $array_us_for_sale_m0, $mysqli);--}}
    {{--        ?>--}}
    {{--        <div class="plan_prodaj_dop_sale">--}}

    {{--            <div>--}}
    {{--                Кол-во самовывозов в этом--}}
    {{--                месяце: <?= ($array_sale_tovari_in_order[$_SESSION['id_user']]['col_m0'] ?? 0); ?>--}}
    {{--                &nbsp;--}}
    {{--                &nbsp;--}}
    {{--                &nbsp;--}}
    {{--                &nbsp;--}}
    {{--                &nbsp;--}}
    {{--                Кол-во самовывозов в этом месяце с доп.--}}
    {{--                продажей: <?= ($array_sale_tovari_in_order[$_SESSION['id_user']]['col_sale_m0'] ?? 0); ?>--}}
    {{--            </div>--}}

    {{--        </div>--}}
    {{--        <?php--}}
    {{--        }--}}
    {{--        ?>--}}


    {{--        <br/>--}}

    {{--        <?php } ?>--}}
@show
