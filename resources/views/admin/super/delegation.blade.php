@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admin Delegation</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Manage admin roles and permissions from this interface.
                    </div>
                    
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Email</th>
                                    <th>Current Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        @if($admin->roles->isNotEmpty())
                                            {{ $admin->roles->first()->name }}
                                        @else
                                            No role assigned
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $admin->id }}">
                                            Edit Permissions
                                        </button>
                                        
                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $admin->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $admin->id }}">Edit Permissions for {{ $admin->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.updateRole', $admin->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="role">Role</label>
                                                                <select name="role_id" id="role" class="form-control">
                                                                    @foreach($roles as $role)
                                                                        <option value="{{ $role->id }}" {{ $admin->roles->contains($role->id) ? 'selected' : '' }}>
                                                                            {{ $role->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Permissions</label>
                                                                <div class="row">
                                                                    @foreach($permissions as $permission)
                                                                    <div class="col-md-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" 
                                                                                class="custom-control-input" 
                                                                                id="perm{{ $admin->id }}_{{ $permission->id }}" 
                                                                                name="permissions[]" 
                                                                                value="{{ $permission->id }}"
                                                                                {{ $admin->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                                            <label class="custom-control-label" for="perm{{ $admin->id }}_{{ $permission->id }}">
                                                                                {{ $permission->name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
