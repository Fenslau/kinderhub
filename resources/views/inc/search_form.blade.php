<form class="" action="{{ route('search') }}" method="get">
    <div class="input-group mb-3">
        <input required minlength="3" maxlength="255" type="text" name="q" class="form-control border-warning" placeholder="Ключевое слово или несколько" value="{{ $q ?? '' }}">
        <button class="btn btn-outline-warning" type="submit"><i class="fa fa-search"></i> Найти</button>
    </div>
</form>
