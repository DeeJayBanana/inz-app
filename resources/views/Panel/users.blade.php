@extends('app')

@section('panel')


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię i nazwisko</th>
                    <th>Nazwa użytkownika</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Weryfikacja</th>
                    <th>Data utworzenia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>

                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                        <td>
                            @if(!(is_null($user['email_verified_at'])))
                                <span class="badge text-bg-success">Zweryfikowany</span>
                            @else
                                <span class="badge text-bg-warning">Niezweryfikowany</span>
                            @endif
                        </td>
                        <td>{{ $user['created_at'] }}</td>
                        <td>
                            <i class="fa-solid fa-user-pen text-warning js-edit-user" data-bs-toggle="modal" data-bs-target="#editUser" data-id="{{ $user['id'] }}" style="cursor: pointer"></i>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{--   Modal Edit User   --}}
    <div class="modal fade" id="editUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edytujesz użytkownika</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="editUserForm" class="form-control">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="first_name">Imię:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="last_name">Nazwisko:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label for="role">Rola:</label>
                            <select name="role" id="role" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            $('.js-edit-user').on('click', function() {
                const user_id = $(this).data('id');

                $.ajax({
                    url: `/users/${user_id}/edit`,
                    method: 'GET',
                    success: function (user) {
                        $('#editUserForm [name="id"]').val(user.id);
                        $('#editUserForm [name="first_name"]').val(user.first_name);
                        $('#editUserForm [name="last_name"]').val(user.last_name);
                        $('#editUserForm [name="email"]').val(user.email);
                        $('#editUserForm [name="role"]').val(user.role);

                        $('#editUserForm').attr('action', `/users/${user.id}`);
                    },
                    error: function () {
                        console.log('Błąd podczas pobierania danych użytkownika.');
                    }
                });
            });
        });
    </script>

@endpush

