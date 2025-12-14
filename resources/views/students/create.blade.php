@extends('layouts.admin')

@section('content')
<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-image me-2"></i>Foto Profil</h6>
                </div>
                <div class="card-body text-center p-4">
                    <div class="rounded-3 bg-light border border-dashed d-flex flex-column align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="bi bi-person-bounding-box display-4 text-secondary opacity-50"></i>
                        <small class="text-muted mt-2">Preview Foto</small>
                    </div>
                    
                    <div class="text-start">
                        <label for="photo" class="form-label small fw-bold text-secondary">Upload Foto Siswa</label>
                        <input type="file" name="photo" id="photo" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text text-xs mt-2">
                            <i class="bi bi-info-circle me-1"></i> Format: JPG/PNG. Maks: 2MB.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-person-lines-fill me-2"></i>Informasi Siswa</h6>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-light text-muted border-0">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
                
                <div class="card-body p-3 p-md-4">
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nama Siswa" required>
                        <label for="name" class="text-secondary">Nama Lengkap Siswa</label>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="parent_id" class="form-select" id="parent_id" required>
                                    <option value="" selected disabled>Pilih Orang Tua</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->phone }})</option>
                                    @endforeach
                                </select>
                                <label for="parent_id" class="text-secondary">Orang Tua / Wali</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="complex_id" class="form-select" id="complex_id" required>
                                    <option value="" selected disabled>Pilih Komplek</option>
                                    @foreach($complexes as $complex)
                                        <option value="{{ $complex->id }}">{{ $complex->name }}</option>
                                    @endforeach
                                </select>
                                <label for="complex_id" class="text-secondary">Lokasi Jemputan</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address_note" class="form-label text-secondary small fw-bold">Detail Alamat Lengkap</label>
                        <textarea name="address_note" class="form-control" id="address_note" rows="3" placeholder="Contoh: Blok A5 No. 12, Pagar Hitam"></textarea>
                        
                    </div>

                    <div class="d-grid d-md-flex justify-content-md-end gap-2 pt-3 border-top">
                        <button type="reset" class="btn btn-light px-4 mb-2 mb-md-0">Reset</button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection