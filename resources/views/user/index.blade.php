@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
                <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
                <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

            </div>  
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger"{{ session('error') }}></div>
            @endif
            <div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <label for="level_id" class="col-1 col-form-label col-form-label-sm">Filter:</label>
            <div class="col-3">
                <select class="form-control form-control-sm" id="level_id" name="level_id" required>
                    <option value="">- Semua -</option>
                    @foreach($level as $item)
                        <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Level Pengguna</small>
            </div>
        </div>
    </div>
</div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr><th>ID</th><th>Username</th><th>Nama</th><th>Level Pengguna</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
</div> @endsection

@push('css') @endpush
@push('js')
<script>
    function modalAction(url = ''){
$('#myModal').load(url,function(){
$('#myModal').modal('show');
});
}

    $(document).ready(function() {
        var dataUser = $('#table_user').DataTable({
            serverSide: true, // serverside: true, jika ingin menggunakan server side processing
            ajax: {
                url: "{{ url('user/list') }}",
                dataType: "json",
                type: "POST",
                data: function (d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // nomor urut dari laravel datatable addIndexColumn()
                { data: 'username', name: 'username' },
                { data: 'nama', name: 'nama' },
                { data: 'level_nama', name: 'level.level_nama' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });

        $('#level_id').on('change', function() {
            dataUser.ajax.reload();
        });
    });
</script>
@endpush    