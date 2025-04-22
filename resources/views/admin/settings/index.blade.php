@extends('admin.layout')

@section('content-admin')
    <div class="container">
        <h4>Pengaturan Umum</h4>
        <ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button"
                    role="tab">Umum</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button"
                    role="tab">SEO</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button"
                    role="tab">Media</button>
            </li>
        </ul>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="tab-content" id="settingsTabContent">
                {{-- Tab Umum --}}
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="mb-3">
                        <label for="site_name">Nama Toko</label>
                        <input type="text" name="site_name" class="form-control"
                            value="{{ $settings['site_name'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="contact_email">Email Kontak</label>
                        <input type="email" name="contact_email" class="form-control"
                            value="{{ $settings['contact_email'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="site_description">Deskripsi Toko</label>
                        <textarea name="site_description" class="form-control">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Tab SEO --}}
                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <div class="mb-3">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control"
                            value="{{ $settings['meta_keywords'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="meta_description">Meta Description</label>
                        <textarea name="meta_description" class="form-control">{{ $settings['meta_description'] ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Tab Media --}}
                <div class="tab-pane fade" id="media" role="tabpanel">
                    <div class="mb-3">
                        <label for="logo">Logo</label><br>
                        @if (!empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" height="50"><br><br>
                        @endif
                        <input type="file" name="logo" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="favicon">Favicon</label><br>
                        @if (!empty($settings['favicon']))
                            <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" height="30"><br><br>
                        @endif
                        <input type="file" name="favicon" class="form-control">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
        </form>

    </div>
@endsection
