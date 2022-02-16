@extends('layouts.app')
@section('title', 'ИДС')
@section('content')
    <select name="test" id="categories">
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
    <div id="items">

    </div>
@endsection
@include('layouts.scripts')
