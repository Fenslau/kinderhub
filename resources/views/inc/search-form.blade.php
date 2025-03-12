<form class="my-md-0 my-1 mx-2" action="{{ route('search') }}" method="get">
    <div class="input-group">
        <input required minlength="3" maxlength="255" type="text" name="q"
            class="form-control border border-light" placeholder="Поиск..." value="{{ request()->q ?? '' }}">
        <button class="btn btn-outline-light" type="submit"><i class="fa fa-search"></i>
            <span class="d-none d-xl-inline">Найти</span></button>
    </div>
</form>