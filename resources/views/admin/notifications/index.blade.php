@extends('admin.layout')

@section('title', 'Notifikasi')

@section('content-admin')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Notifikasi</h4>
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-secondary">Tandai Semua Dibaca</button>
            </form>
        </div>
        <div class="card-body">
            @if ($notifications->isEmpty())
                <p>Tidak ada notifikasi.</p>
            @else
                <ul class="list-group">
                    @foreach ($notifications as $notif)
                        <li class="list-group-item {{ $notif->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        @if (isset($notif->data['url']))
                                            <a href="{{ $notif->data['url'] }}"
                                                class="text-decoration-none">{{ $notif->data['title'] ?? 'Notifikasi Baru' }}</a>
                                        @else
                                            {{ $notif->data['title'] ?? 'Notifikasi Baru' }}
                                        @endif

                                        @if (is_null($notif->read_at))
                                            <span class="badge bg-warning text-dark ms-2">Baru</span>
                                        @endif
                                    </strong><br>
                                    <small>{{ $notif->data['message'] ?? ($notif->data['body'] ?? '-') }}</small>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">Tandai Dibaca</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
