@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-bus-front-fill me-2"></i>Tambah Armada Baru
                </h5>
                <a href="{{ route('shuttles.index') }}" class="btn btn-sm btn-light text-muted border-0">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('shuttles.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-uppercase text-secondary text-xs fw-bolder opacity-7 mb-3">Identitas Kendaraan</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" name="plate_number" class="form-control" id="plate_number" placeholder="BP 1234 XY" style="text-transform: uppercase" required>
                                <label for="plate_number" class="text-secondary">
                                    <i class="bi bi-123 me-1"></i> Plat Nomor
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" name="car_model" class="form-control" id="car_model" placeholder="Toyota Hiace" required>
                                <label for="car_model" class="text-secondary">
                                    <i class="bi bi-car-front me-1"></i> Model / Merk Mobil
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" name="capacity" class="form-control" id="capacity" placeholder="15" min="1" required>
                                <label for="capacity" class="text-secondary">
                                    <i class="bi bi-people me-1"></i> Kapasitas Kursi
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border-0 bg-light d-flex align-items-center mb-0" role="alert">
                        <i class="bi bi-info-circle-fill text-primary me-2 fs-5"></i>
                        <div class="small text-muted">
                            Pastikan <strong>Plat Nomor</strong> sesuai dengan STNK. Kapasitas kursi dihitung untuk <strong>jumlah siswa</strong> yang dapat diangkut (tidak termasuk supir).
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-light px-4 text-muted">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection