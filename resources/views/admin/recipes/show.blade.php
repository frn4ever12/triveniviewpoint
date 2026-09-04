@extends('admin.includes.main')

@section('title', 'Recipe Details')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Recipe Details" route="admin.recipes.index" button="Back" icon="bi-arrow-left" />

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recipe Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Menu Item:</th>
                                <td>{{ $recipe->menuItem->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Recipe Cost:</th>
                                <td>{{ number_format($recipe->recipe_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Selling Price:</th>
                                <td>{{ number_format($recipe->selling_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Food Cost %:</th>
                                <td>{{ number_format($recipe->food_cost_percent, 2) }}%</td>
                            </tr>
                            <tr>
                                <th>Gross Profit:</th>
                                <td>{{ number_format($recipe->gross_profit, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Gross Margin %:</th>
                                <td>{{ number_format($recipe->gross_margin_percent, 2) }}%</td>
                            </tr>
                            <tr>
                                <th>Preparation Time:</th>
                                <td>{{ $recipe->preparation_time }} minutes</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $recipe->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($recipe->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        @if($recipe->instructions)
                            <div class="mt-3">
                                <strong>Instructions:</strong>
                                <p>{{ $recipe->instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recipe Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Waste %</th>
                                    <th>Cost/Unit</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipe->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->unit->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->wastage_percent, 2) }}%</td>
                                        <td>{{ number_format($item->cost_per_unit, 2) }}</td>
                                        <td>{{ number_format($item->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total Recipe Cost:</th>
                                    <th>{{ number_format($recipe->recipe_cost, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.recipes.edit', $recipe->id) }}" class="btn btn-warning">Edit Recipe</a>
            <a href="{{ route('admin.recipes.update-costs', $recipe->id) }}" class="btn btn-info">Update Costs</a>
            <form action="{{ route('admin.recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Recipe</button>
            </form>
        </div>
    </div>
@endsection
