<form action="{{  $route }}" method="GET" class="form-inline">
    <label for="keyword" class="sr-only"></label>
    <input type="text" name="keyword" class="form-control w-50 mr-3" id="keyword"
        placeholder="Cari berdasarkan nama,tahun,plat nomor" value="{{ request()->keyword }}">
    <div class="form-group">
        <select name="status" class="form-control status-select2" style="width: 12em">
            <option></option>
            <option value='AVAILABLE' {{ request()->status == 'AVAILABLE' ? 'selected' : '' }}>
                Tersedia
            </option>
            <option value='NOT AVAILABLE' {{ request()->status == 'NOT AVAILABLE' ? 'selected' : '' }}>
                Disewa
            </option>
        </select>
    </div>
    <button class="btn btn-primary mx-2 ml-3" type="submit" id="button-addon2">{{ __('Search') }}</button>
    @if ($type === 'index')
    <a href="{{ route('cars.index', ['status' => 'AVAILABLE']) }}" class="btn btn-dark d-inline-block"
        id="button-addon2">Reset</a>
    @else
    <a href="{{ route('cars.trash') }}" class="btn btn-dark d-inline-block" id="button-addon2">Reset</a>
    @endif
</form>
