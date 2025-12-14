@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-building-add me-2"></i>Tambah Komplek / Perumahan
                </h5>
                <a href="{{ route('complexes.index') }}" class="btn btn-sm btn-light text-muted border-0">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('complexes.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-uppercase text-secondary text-xs fw-bolder opacity-7 mb-3">Detail Lokasi</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Komplek" required>
                                <label for="name" class="text-secondary">
                                    <i class="bi bi-houses me-1"></i> Nama Komplek / Perumahan
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="route_id" class="form-select" id="route_id" required>
                                    <option value="" selected disabled>-- Pilih Rute --</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                                    @endforeach
                                </select>
                                <label for="route_id" class="text-secondary">
                                    <i class="bi bi-signpost-split me-1"></i> Masuk ke Rute Mana?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border-0 bg-light d-flex align-items-center mb-0" role="alert">
                        <i class="bi bi-info-circle text-info me-2 fs-6"></i>
                        <div class="small text-muted">
                            Pastikan Anda memilih <strong>Rute</strong> yang sesuai agar sistem dapat mengatur jadwal penjemputan dengan akurat.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-light px-4 text-muted">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection