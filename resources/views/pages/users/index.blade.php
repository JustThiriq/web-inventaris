@extends('adminlte::page')

@section('title', 'Manajemen Users')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Pengguna
                            </h4>
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Pengguna
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Flash Messages --}}
                        @include('components.flash-message')

                        <!-- Filter & Search -->
                        @include('pages.users.searchable', [
                            'route' => 'users.index',
                            'roles' => $roles,
                            'statuses' => [
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                            ],
                        ])

                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                            </td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                                @if ($user->id === auth()->id())
                                                    <span class="badge badge-info badge-sm">You</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                {{ ucfirst($user->role?->name ?? '') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $user->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('users.edit', $user) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    @if ($user->id !== auth()->id())
                                                        @if ($user->is_active)
                                                            <form id="form-destroy{{ $user->id }}"
                                                                action="{{ route('users.destroy', $user) }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <button class="btn btn-danger btn-sm" title="Nonaktifkan"
                                                                onclick="event.preventDefault(); if(confirm('Yakin ingin menonaktifkan user ini?')) { document.getElementById('form-destroy{{ $user->id }}').submit(); }">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        @else
                                                            <form id="form-activate{{ $user->id }}"
                                                                action="{{ route('users.activate', $user) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                            </form>
                                                            <button class="btn btn-success btn-sm" title="Aktifkan"
                                                                onclick="event.preventDefault(); if(confirm('Yakin ingin mengaktifkan user ini?')) { document.getElementById('form-activate{{ $user->id }}').submit(); }">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data user.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @include('components.pagination', ['pagination' => $users])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                // Filter functionality
                $('#roleFilter, #statusFilter').change(function() {
                    filterUsers();
                });

                $('#searchBtn').click(function() {
                    filterUsers();
                });

                $('#searchInput').keypress(function(e) {
                    if (e.which == 13) {
                        filterUsers();
                    }
                });

                function filterUsers() {
                    let params = new URLSearchParams();

                    let role = $('#roleFilter').val();
                    let status = $('#statusFilter').val();
                    let search = $('#searchInput').val();

                    if (role) params.append('role', role);
                    if (status) params.append('status', status);
                    if (search) params.append('search', search);

                    window.location.href = '{{ route('users.index') }}?' + params.toString();
                }
            });
        </script>
    @endpush
@endsection
