@extends('layouts.app')

@section('title-block', config('app.name') . ' - Объявления')
@section('description-block', 'Объявления о поиске нянь для детей, домработниц, сиделок, репетиторов')

@section('breadcrumbs', Breadcrumbs::render('announcements'))
@section('content')

<div class="my-3 container-lg main">
  <div class="row">

    <div class="col-md-4 col-xl-3 mb-3 my-md-0 order-md-last">
      @include('inc.aside')
    </div>
    <div class="col-md-8 col-xl-9">
      <div id="articles" class="">
        @include('announcement.list')
      </div>
    </div>

  </div>

</div>

@endsection