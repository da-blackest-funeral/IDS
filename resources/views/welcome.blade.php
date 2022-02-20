@extends('layouts.app')
@section('title', 'ИДС')
@section('content')
    <div class="container mt-4">
        <h1 class="h1">
            Расчёт
        </h1>
        <div class="container-fluid mb-4" style="padding: 5px;">
            <a href="https://03-okna.ru/offer.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Комм.предл.
            </a>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084&nosign=no" class="btn btn-secondary btn-sm">
                Комм.предл БП
            </a>
            <a href="https://03-okna.ru/invoice.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Накладная
            </a>
            <a href="https://03-okna.ru/act.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Акт
            </a>
            <a href="https://03-okna.ru/pko.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Чек ПКО
            </a>
            <a href="https://03-okna.ru/exel.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Stis excel
            </a>
            <a href="https://03-okna.ru/moedelo.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Счет моедело
            </a>
            <a href="/load.php?route=admin/calc/history&num_rasch=34084" class="btn btn-secondary btn-sm">
                Логи
            </a>
            <a href="/load.php?route=admin/master/sebestoimost&num_rasch=34084" class="btn btn-secondary btn-sm">
                Себестоимость
            </a>
            <a href="/load.php?route=admin/sklad/order&num_rasch=34084" class="btn btn-danger btn-sm">
                Создать списание
            </a>
        </div>
        <div class="container-fluid bg-light">
            <h4 class="h4">Добавить товар</h4>
            <form action="" id="form_add_new_product" method='POST' class='form-group form_add_new_product'
                  enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="add_tov_tit">Добавить товар</div>
                    </div>
                </div>
                <div class="row mt-2" id="place_with_width">
                    <div class="col-12 col-md-3">
                        <div class="mb-1 mt-2 mt-md-0">Высота (длина)</div>
                        <input type="text" value="" id="height" name="height" placeholder="Высота (габаритн.) в мм."
                               class="form-control"/>
                    </div>

                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <div class="mb-1 mt-2 mt-md-0">Ширина (глубина)</div>
                        <input type="text" value="" id="width" name="width" placeholder="Ширина (габаритн.) в мм."
                               class="form-control"/>
                    </div>

                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <div class="mb-1 mt-2 mt-md-0">Количество</div>
                        <input type="text" value="" id="col" name="col" placeholder="Количество (шт)"
                               class="form-control"/>
                    </div>

                    <div class="col-12 col-md-3 d-none d-md-block">

                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-10 col-md-3 mt-2 mt-md-0">


                    </div>

                    <div class="col-2 d-block d-md-none">
                        <div class="pt-3 text-center"><a style="display:none;" id="link_information" target="_blank"
                                                         href=""><span style="font-size: 18px;"
                                                                       class="fa fa-info-circle"></span></a></div>
                    </div>

                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <div id="place_tissue"></div>
                    </div>

                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <div id="place_profil"></div>
                    </div>
                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <div id="place_uslugi"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 mt-2 mt-md-0">
                        <div id="place_additional"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="container-fluid bg-light">
            <form action method="POST" class="form-group">
                <div class="row">
                    <div class="col-12">
                        <h6 class="h6">Добавить товар</h6>
                    </div>
                    <div class="col-12"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height">Высота (длина)</label>
                        <input name="height" id="height" placeholder="Высота (габаритн.) в мм." type="text"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height">Ширина (глубина)</label>
                        <input name="height" id="height" placeholder="Ширина (габаритн.) в мм." type="text"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height">Количество (шт)</label>
                        <input name="height" id="height" placeholder="Количество (шт)" type="text" class="form-control">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10 col-md-3 mt-2 mt-md-0">
                        <label class="mb-1 mt-2 mt-md-0" for="categories">Тип изделия</label>
                        <select name="categories" id="categories" class="form-control">
                            <option>Тип изделия</option>
                            @foreach($superCategories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($data as $item)
                                        @if($item->parent_id == $category->id)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-4"></div>
            </form>
        </div>
    </div>
@endsection
