@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-signpost-2-fill me-2"></i>Tambah Rute Baru
                </h5>
                <a href="{{ route('routes.index') }}" class="btn btn-sm btn-light text-muted border-0">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('routes.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-uppercase text-secondary text-xs fw-bolder opacity-7 mb-3">Informasi Wilayah</h6>

                    <div class="row">
                        <div class="col-md-6 col-lg-5">
                            <div class="mb-4">
                                <label class="form-label text-secondary fw-bold small">Nama Wilayah / Rute</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-primary">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 bg-light" 
                                           name="name" 
                                           placeholder="Contoh: Batam Center (Zona A)" 
                                           required 
                                           autofocus>
                                </div>
                                <div class="form-text mt-2 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Nama ini akan muncul di pilihan jemputan siswa.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3 border-top">
                        <button type="reset" class="btn btn-light px-4 text-muted">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Rute
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection