@extends('layouts.sb-admin-2.master')

@section('title', 'Halaman Admin')

@section('content')
<h1>Data Admin</h1>


<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row mb-2">
            <div class="col-sm-8">
                <div class="row justify-content-start">
                    <div class="col-sm-8">
                        <form action="{{ route('admins.index') }}">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari berdasarkan nama atau email" value="{{ request()->search }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit"
                                        id="button-addon2">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ route('admins.index') }}" class="btn btn-dark">Reset</a>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('admins.create') }}" class="btn btn-primary btn-block mb-1">Tambah Admin</a>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center mt-2">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                    <tr>
                        <th scope="row">{{ $admins->currentPage() * 10 - 10 + $loop->iteration }}</th>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->phone }}</td>
                        <td>{{ $admin->address }}</td>
                        <td>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteadmin{{ $admin->phone }}modal">Hapus</a>
                        </td>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteadmin{{ $admin->phone }}modal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Admin</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Apakah anda yakin ingin menghapus admin
                                            <strong>{{ $admin->name }}</strong> ?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <form action="{{ route('admins.destroy', $admin) }}" method="post" id="myfr">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" id="btnfr">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <p class="font-weight-bold text-center text-monospace">Data admin tidak tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Paginate Links --}}
            <div class="mt-4">
                {{ $admins->withQueryString()->onEachSide(2)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
