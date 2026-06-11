@extends('app')

@section('panel')

    <div class="container-fluid">
        @can('add_users')
            <button class="btn btn-custom mt-3" data-bs-toggle="modal" data-bs-target="#addUser">Dodaj użytkownika</button>
            <button class="btn btn-custom mt-3" data-bs-toggle="modal" data-bs-target="#addRoles">Utwórz role</button>
        @endcan

        <div class="d-flex justify-content-end align-items-center mb-4 mt-3" id="changeSectionUserPermissions">
            <span class="me-3 fw-bold">Użytkownicy</span>
            <div class="form-check form-switch " style="transform: scale(1.5);">
                <input class="form-check-input" type="checkbox" id="viewToggler" role="switch">
            </div>
            <span class="ms-3 fw-bold">Role</span>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-hover table-dark" id="usersSection">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
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
                            <td class="text-center">
                                <img src="{{ asset('storage/' . (!empty($user->avatar) ? $user->avatar : 'avatars/avatar-default-icon.png')) }}" width="50px" height="50px" class="p-0 object-fit-cover rounded-circle">
                            </td>
                            <td class="align-middle">{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                            <td class="align-middle">{{ $user['name'] }}</td>
                            <td class="align-middle">{{ $user['email'] }}</td>
                            <td class="align-middle"><span class="badge @if($user->getRoleNames()->first() === 'administrator')bg-danger @else bg-primary @endif text-capitalize">{{ $user->getRoleNames()->first() }}</span></td>
                            <td class="align-middle">
                                @if(!(is_null($user['email_verified_at'])))
                                    <span class="badge text-bg-success">Zweryfikowany</span>
                                @else
                                    <span class="badge text-bg-warning">Niezweryfikowany</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $user['created_at'] }}</td>
                            <td class="align-middle">
                                <i class="fa-solid fa-user-pen @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-warning js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="edit" style="cursor: pointer"></i>
                                <i class="fa-solid fa-trash @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-danger js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="remove" style="cursor: pointer"></i>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <table class="table table-hover table-dark" id="rolesSection" style="display: none;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Rola</th>
                    <th>Data utworzenia</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>

                @foreach($roles as $role)
                    @if($role['name'] != 'administrator')
                    <tr data-id="{{ $role['id'] }}">
                        <td class="align-middle">{{ $role['id']}}</td>
                        <td class="align-middle text-capitalize">{{ $role['name'] }}</td>
                        <td class="align-middle">{{ $role['created_at'] }}</td>
                        <td class="align-middle">
                            <i class="fa-solid fa-user-pen text-warning @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-warning js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="editRoles" style="cursor: pointer"></i>
                            <i class="fa-solid fa-trash text-danger  @if(auth()->user()->getRoleNames()->contains('admin') || !$user->getRoleNames()->contains('admin')) text-warning js-edit-user @else text-muted @endif" data-bs-toggle="modal" data-action="remove" style="cursor: pointer"></i>
                        </td>
                    </tr>
                    @endif
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
                                <input type="password" name="password" id="password" class="form-control">
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

        {{-- Create Roles  --}}
            <div class="modal fade" id="addRoles">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Stwórz role i przypisz uprawnienia</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('roles.store') }}" id="addUserForm" class="form-control">
                            @csrf
                            <div class="row modal-body">
                                <div class="col-6 mb-3">
                                    <input class="form-control" type="text" name="role_name" placeholder="Nazwa roli: ">
                                </div>
                                <div class="col-12 row mb-3">
                                    <h3>Wybierz uprawnienia:</h3>
                                    @foreach($groupedPermission as $groupName => $permissions)
                                        <div class="col-md-4 mb-3">
                                            <strong>{{ $groupName }}</strong>
                                            @foreach($permissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="checkDefault">
                                                    <label class="form-check-label" for="checkDefault">
                                                       {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
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
            <div class="modal fade" id="editRoles">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edytujesz role i jej uprawnienia</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="" id="editRoleForm" class="form-control">
                            @csrf
                            @method('PUT')
                            <div class="row modal-body">
                                <div class="d-flex gap-3 mb-3">
                                    <div class="col-auto">
                                        <label for="name" class="col-form-label">Rola:</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Nazwa roli:">
                                    </div>
                                </div>
                                <div class="col-12 row mb-3">
                                    <h3>Wybierz uprawnienia:</h3>
                                    @foreach($groupedPermission as $groupName => $permissions)
                                        <div class="col-md-4 mb-3">
                                            <strong>{{ $groupName }}</strong>
                                            @foreach($permissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="checkDefault">
                                                    <label class="form-check-label" for="checkDefault">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Potwierdzenie operacji</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="" id="deleteUserForm" class="form-control">
                            @csrf
                            @method('DELETE')
                            <div class="row modal-body">
                            <span>
                                UWAGA!<br/>
                                Czy na pewno chcesz usunąć ten rekord? <br/>
                                Tej operacji nie można cofnąć
                            </span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                <button type="submit" class="btn btn-danger">Usuń trwale</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            $('#viewToggler').on('change', function() {
                if($(this).is(':checked')) {
                    $('#usersSection').fadeOut(200, function() {
                        $('#rolesSection').fadeIn(200);
                    })
                } else {
                    $('#rolesSection').fadeOut(200, function() {
                       $('#usersSection').fadeIn(200);
                    });
                }
            });

            function fillUserModal(user) {
                $('#editUserForm [name="id"]').val(user.id);
                $('#editUserForm [name="first_name"]').val(user.first_name);
                $('#editUserForm [name="last_name"]').val(user.last_name);
                $('#editUserForm [name="email"]').val(user.email);

                user.roles && user.roles.length > 0 ? $('#editUserForm [name="role"]').val(user.roles[0].name) : $('#editUserForm [name="role"]').val('');

                    $('#editUserForm').attr('action', `/users/${user.id}`);
                $('#editUser').modal('show');
            }

            function fillRoleModal(data) {
                $('#editRoleForm [name="name"]').val(data.name);

                $('#editRoleForm').attr('action', `/roles/${data.id}/update`);

                $('#editRoleForm [name="permissions[]"]').prop('checked', false);
                if(data.permissions) {
                    data.permissions.forEach(function(permission) {
                        $(`#editRoleForm [name="permissions[]"][value="${permission.name}"]`).prop('checked', true);
                    });
                }

                $('#editRoles').modal('show');
            }

            $('.js-edit-user').on('click', function(e) {
                e.preventDefault();
                const id = $(this).closest('tr').data('id');
                const action = $(this).data('action');
                const $tr = $(this).closest('tr');

                if(action === 'edit' || action === 'editRoles') {

                    const url = action === 'edit' ? `/users/${id}/edit` : `/roles/${id}/edit`;

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            if(action === 'edit') {
                                fillUserModal(data);
                            } else {
                                fillRoleModal(data);
                            }
                        },
                        error: function () {
                            console.log('Błąd podczas pobierania danych użytkownika.');
                        }
                    });

                } else if (action === 'remove') {
                    const isRole = $tr.closest('#rolesSection').length > 0;
                    const deleteUrl = isRole ? `/roles/${id}` : `/users/${id}`;

                    $('#deleteUserForm').attr('action', deleteUrl);
                    $('#deleteUser').modal('show');
                }
            });
        });
    </script>

@endpush

