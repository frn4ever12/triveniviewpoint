@extends('admin.includes.main')

@section('title', 'Recipes')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Recipes" route="admin.recipes.create" button="Add New Recipe" icon="bi-plus-circle" />

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Menu Item</th>
                                <th>Recipe Cost</th>
                                <th>Selling Price</th>
                                <th>Food Cost %</th>
                                <th>Gross Profit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recipes as $recipe)
                                <tr>
                                    <td>{{ $recipe->id }}</td>
                                    <td>{{ $recipe->menuItem->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($recipe->recipe_cost, 2) }}</td>
                                    <td>{{ number_format($recipe->selling_price, 2) }}</td>
                                    <td>{{ number_format($recipe->food_cost_percent, 2) }}%</td>
                                    <td>{{ number_format($recipe->gross_profit, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $recipe->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($recipe->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.recipes.show', $recipe->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.recipes.edit', $recipe->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No recipes found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
