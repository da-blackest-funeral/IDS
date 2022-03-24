@extends('layouts.app')
@section('title', 'ИДС')
@section('content')
    <div class="container mt-4">
        <h1 class="h1">
            Заказ <span style="font-size: 30px">№{{ $orderNumber }}</span>
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
        <div class="container-fluid bg-light" style="min-height: 250px;">
            <form action method="POST" class="form-group">
                @csrf
                <div class="row">
                    <div class="col-12 pt-2 pl-2">
                        <p class="h3">Добавить товар</p>
                    </div>
                    <div class="col-12"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height">Высота (длина)</label>
                        <input name="height" id="height" placeholder="Высота (габаритн.) в мм." type="text"
                               class="form-control" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="width">Ширина (глубина)</label>
                        <input name="width" id="width" placeholder="Ширина (габаритн.) в мм." type="text"
                               class="form-control" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="count">Количество (шт)</label>
                        <input name="count" id="count" placeholder="Количество (шт)" type="text"
                               class="form-control"
                               required>
                    </div>
                </div>
                <div class="row mt-4" id="items-row">
                    <div class="col-10 col-md-3 mt-2 mt-md-0" id="categories-container">
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
                    <div class="col-10 col-md-3 mt-1" id="items">
                        {{-- Сюда грузится второй селект --}}
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="third">
                        {{-- Место для третьего селекта --}}
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="fourth">
                        {{-- Место для четвертого селекта --}}
                    </div>
                </div>
                <div class="mt-4 pb-3" id="additional">
                    {{-- Место для дополнительных опций --}}
                </div>
            </form>
        </div>
    </div>
@endsection
