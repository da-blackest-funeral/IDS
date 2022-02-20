@extends('layouts.app')
@section('title', 'ИДС')
@section('content')
    {{--
    <div class="conteiner-fluid" style="padding: 5px;">
        <div>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Комм.предл.</div>
            </a>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084&nosign=no" title="" target="_blank">
                <div class="btn_kp">Комм.предл БП</div>
            </a>
            <a href="https://03-okna.ru/invoice.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Накладная</div>
            </a>
            <a href="https://03-okna.ru/act.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Акт</div>
            </a>
            <a href="https://03-okna.ru/pko.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Чек ПКО</div>
            </a>
            <a href="https://03-okna.ru/exel.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Stis excel</div>
            </a>
            <a href="https://03-okna.ru/moedelo.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Счет моедело</div>
            </a>
            <a href="/load.php?route=admin/calc/history&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Логи</div>
            </a>
            <a href="/load.php?route=admin/master/sebestoimost&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Себестоимость</div>
            </a>
            <a href="/load.php?route=admin/sklad/order&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp_red">Создать списание</div>
            </a>
        </div>
        <div style="font-size: 13px; color: rgb(120,120,120);" class="mt-2">


        </div>
        <form action="" id="form_add_new_product" method='POST' class='form-group form_add_new_product'
              enctype="multipart/form-data">
            <div class="row">
                <div class="col-12">
                    <div class="add_tov_tit">Добавить товар</div>
                </div>
                <div class="col-12">
                    <div id="master_plenka_vid_raschet" style="display:none; ">
                        <div style="padding: 1px 6px;" class="mt-2 btn btn-warning"
                             onclick="master_plenka_vid_raschet();">Ввести в п.м.
                        </div> &nbsp; <span style='color: red; display:inline-block; margin-top: 4px;'>Длина должна быть кратна 500 мм!</span>
                    </div>
                    <div id="master_setka_raschet" style="display:none; ">
                        <div style="padding: 1px 6px;" class="mt-2 btn btn-warning"
                             onclick="master_setka_raschet($('#id_us').val());">Ввести в п.м.
                        </div> &nbsp; <span style='color: red; display:inline-block; margin-top: 4px;'>Длина должна быть кратна 1 метру!</span>
                    </div>
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
                    <input type="text" value="" id="col" name="col" placeholder="Количество (шт)" class="form-control"/>
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
        <br/>
        <br/>
    </div>
--}}
    <div class="container-fluid" style="padding: 5px;">
        <div>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Комм.предл.</div>
            </a>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084&nosign=no" title="" target="_blank">
                <div class="btn_kp">Комм.предл БП</div>
            </a>
            <a href="https://03-okna.ru/invoice.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Накладная</div>
            </a>
            <a href="https://03-okna.ru/act.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Акт</div>
            </a>
            <a href="https://03-okna.ru/pko.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Чек ПКО</div>
            </a>
            <a href="https://03-okna.ru/exel.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Stis excel</div>
            </a>
            <a href="https://03-okna.ru/moedelo.php?num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Счет моедело</div>
            </a>
            <a href="/load.php?route=admin/calc/history&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Логи</div>
            </a>
            <a href="/load.php?route=admin/master/sebestoimost&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp">Себестоимость</div>
            </a>
            <a href="/load.php?route=admin/sklad/order&num_rasch=34084" title="" target="_blank">
                <div class="btn_kp_red">Создать списание</div>
            </a>
        </div>
        <div style="font-size: 13px; color: rgb(120,120,120);" class="mt-2">


        </div>
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
                    <input type="text" value="" id="col" name="col" placeholder="Количество (шт)" class="form-control"/>
                </div>

                <div class="col-12 col-md-3 d-none d-md-block">

                </div>
            </div>

            <div class="row mt-4">

                <select class="form-control w-25" name="categories" id="categories">
                    <option>Категория</option>
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
        {{--            <div class="form-row">--}}

        <div id="items" class="form-row">

        </div>
    </div>
    </div>
    </div>
@endsection
