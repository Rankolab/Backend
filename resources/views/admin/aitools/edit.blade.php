@extends('layouts.app')
@section('title', isset($aitool) ? 'Edit Tool' : 'Add Tool')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ isset($aitool) ? 'Edit Tool' : 'Add New AI Tool' }}</h1>
    <form method="POST" action="{{ isset($aitool) ? route('admin.aitools.update', $aitool) : route('admin.aitools.store') }}">
        @csrf
        @if(isset($aitool)) @method('PUT') @endif
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $aitool->name ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label>URL</label>
            <input type="text" name="url" class="form-control" value="{{ $aitool->url ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ $aitool->category ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $aitool->description ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ isset($aitool) && $aitool->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ isset($aitool) && $aitool->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
