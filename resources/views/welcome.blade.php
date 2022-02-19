@extends('layouts.app')
@section('title', 'ИДС')
@section('content')
    <div class="container">
        <div class="container-fluid form-add" style="margin-top: 50px">
            <div class="form-row">
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
                <div id="items" class="form-row">

                </div>
            </div>
        </div>
    </div>
@endsection
