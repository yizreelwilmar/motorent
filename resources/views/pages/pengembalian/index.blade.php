@extends('layouts.dashboard')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengembalian</h1>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Sewa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>UID</th>
                            <th>ID Penyewa</th>
                            <th>ID Motor</th>
                            <th>Tanggal Sewa</th>
                            <th>Tanggal Kembali</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sewas as $sewa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sewa->sewa_uuid }}</td>
                            <td>{!! isset($sewa->penyewa->restuid)? $sewa->penyewa->restuid : '<small>Data penyewa telah
                                    dihapus!</small>' !!}</td>
                            <td>{!! isset($sewa->motor->mtruid) ? $sewa->motor->mtruid : '<small>Data motor telah
                                    dihapus!</small>' !!}</td>
                            <td>{{ date('d-F-Y', strtotime($sewa->tanggal_sewa)) }}</td>
                            <td>{{ date('d-F-Y', strtotime($sewa->tanggal_kembali)) }}</td>
                            <td>Rp. {{ $sewa->total_biaya}}</td>
                            <td>
                                @if(!$sewa->status)
                                <span class="badge badge-secondary badge-pill">Masa Sewa
                                    @if ( date(('Y-m-d')) > $sewa->tanggal_kembali)
                                    - <span class="text-danger">Terlambat</span>
                                    @elseif(date(('Y-m-d')) === $sewa->tanggal_kembali)
                                    - <span class="text-warning">Hari terakhir</span>
                                    @endif
                                </span>
                                @else
                                <span class="badge badge-success badge-pill">
                                    Kembali
                                </span>
                                @endif
                                <br>
                                @if($sewa->status_bayar)
                                <span class="badge badge-pill badge-success">Telah Terbayar</span>
                                @else
                                <span class="badge badge-pill badge-danger">Belum Membayar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('penyewaan.show',$sewa->id) }}"
                                    class="btn btn-info btn-sm ">Detail</a>
                                @if(!$sewa->status_bayar)
                                <a href="{{ route('pengembalian.edit',$sewa->id) }}"
                                    class="btn btn-sm btn-warning ">Bayar</a>
                                @endif
                                @if(!$sewa->status)
                                <form action="{{ route('pengembalian.destroy',$sewa->id) }}" method="post" class="mt-1">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-sm btn-primary">Kembali</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@push('addon-style')
<link href="{{ url('') }}/dashboard/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@push('addon-script')
<!-- Page level plugins -->
<script src="{{ url('') }}/dashboard/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('') }}/dashboard/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="{{ url('') }}/dashboard/js/demo/datatables-demo.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.13/dist/sweetalert2.all.min.js"></script>

<script>
    function addConfirm(){
        Swal.fire(
            'Berhasil',
            'Anda berhasil menambah penyewaan baru!',
            'success'
        )
    }
    function failConfirm(){
        Swal.fire(
            'Gagal',
            'Motor sedang digunakan!',
            'error'
        )
    }
    function editConfirm(){
        Swal.fire(
            'Berhasil',
            'Anda berhasil mengubah penyewaan ini!',
            'success'
        )
    }
    function destroyMessage(){
        Swal.fire(
            'Berhasil',
            'Anda berhasil membatalkan penyewaan ini!',
            'success'
        )
    }
    
    {!! session('success') !!}
</script>
@endpush