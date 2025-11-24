@extends('app')

@section('panel')

    @can('add_users')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">Dodaj użytkownika</button>
    @endcan

    <div class="table-responsive mt-3">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię i nazwisko</th>
                    <th>Nazwa użytkownika</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Status</th>
                    <th>Data utworzenia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>

                @foreach($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user->getRoleNames()->first() }}</td>
                        <td>
                            @if(!(is_null($user['email_verified_at'])))
                                <span class="badge text-bg-success">Zweryfikowany</span>
                            @else
                                <span class="badge text-bg-warning">Niezweryfikowany</span>
                            @endif
                        </td>
                        <td>{{ $user['created_at'] }}</td>
                        <td>
                            <i class="fa-solid fa-user-pen @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-warning js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="edit" style="cursor: pointer"></i>
                            <i class="fa-solid fa-trash @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-danger js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="remove" style="cursor: pointer"></i>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{--   Modal Add User   --}}
    <div class="modal fade" id="addUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Dodaj użytkownika</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('users.add') }}" id="addUserForm" class="form-control">
                    @csrf
                    <div class="row modal-body">
                        <div class="col-6 mb-3">
                            <label for="first_name">Imię:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="last_name">Nazwisko:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="name">Nazwa użytkownika:</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="role">Rola:</label>
                            <select name="role" id="role" class="form-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="password">Hasło:</label>
                            <input type="text" name="password" id="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="verifyCheck" name="verifyCheck">
                                <label class="form-check-label" for="verifyCheck">
                                    Zweryfikuj
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </div>
                </form>
            </div>
        </div>
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
                    <div class="row modal-body">
                            <input type="hidden" name="id" id="id">
                        <div class="col-6 mb-3">
                            <label for="first_name">Imię:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="last_name">Nazwisko:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        @can('edit_users')
                        <div class="mb-3">
                            <label for="role">Rola:</label>
                            <select name="role" id="role" class="form-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endcan
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--   Modal Delete User   --}}
    <div class="modal fade" id="deleteUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Czy jesteś pewny usunięcia użytkownika?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="deleteUserForm" class="form-control">
                    @csrf
                    @method('DELETE')
                    <div class="row modal-body">
                        <span>
                            UWAGA!<br/>
                            Ta operacja jest nie odwracalna.<br/><br/>
                            Czy jesteś pewny tego posunięcia?!
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            $('.js-edit-user').on('click', function(e) {
                e.preventDefault();
                const user_id = $(this).closest('tr').data('id');
                const action = $(this).data('action');

                if(action === 'edit') {

                    $.ajax({
                        url: `/users/${user_id}/edit`,
                        method: 'GET',
                        success: function (user) {
                            $('#editUserForm [name="id"]').val(user.id);
                            $('#editUserForm [name="first_name"]').val(user.first_name);
                            $('#editUserForm [name="last_name"]').val(user.last_name);
                            $('#editUserForm [name="email"]').val(user.email);
                            $('#editUserForm [name="role"]').val(user.roles[0].name);

                            $('#editUserForm').attr('action', `/users/${user.id}`);

                            $('#editUser').modal('show');
                        },
                        error: function () {
                            console.log('Błąd podczas pobierania danych użytkownika.');
                        }
                    });

                } else if (action === 'remove') {
                    $('#deleteUserForm').attr('action', `/users/${user_id}`);
                    $('#deleteUser').modal('show');
                }
            });
        });
    </script>

@endpush

