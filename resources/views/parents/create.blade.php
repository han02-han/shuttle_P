@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h5 class="mb-0 text-primary fw-bold">
                    <i class="bi bi-people-fill me-2"></i>Tambah Wali Murid
                </h5>
                <a href="{{ route('parents.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('parents.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-uppercase text-secondary text-xs fw-bolder opacity-7 mb-3">Data Akun & Kontak</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" required>
                                <label for="name" class="text-secondary">
                                    <i class="bi bi-person me-1"></i> Nama Wali Murid
                                </label>
                            </div>

                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                                <label for="email" class="text-secondary">
                                    <i class="bi bi-envelope me-1"></i> Alamat Email
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="No HP" required>
                                <label for="phone" class="text-secondary">
                                    <i class="bi bi-telephone me-1"></i> No. HP / WhatsApp
                                </label>
                            </div>

                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                <label for="password" class="text-secondary">
                                    <i class="bi bi-lock me-1"></i> Password
                                </label>
                            </div>
                            <div class="form-text ms-1 mt-1">
                                <small><i class="bi bi-shield-lock"></i> Password digunakan untuk login aplikasi orang tua.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-light me-md-2 text-muted">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Data Wali
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection