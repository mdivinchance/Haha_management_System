<x-app-layout>
    <div class="p-6 max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">Edit Report</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $product->name }} — {{ $dailyReport->report_date->format('M d, Y') }}</p>
        </div>

        <form method="POST" action="{{ route('daily-reports.update', [$product, $dailyReport]) }}"
              class="bg-neutral-900 rounded-xl p-6 space-y-5"
              x-data="{
                quantity: {{ old('quantity_sold', $dailyReport->quantity_sold) }},
                sellingPrice: '{{ old('selling_price', $dailyReport->selling_price) }}',
                get total() { return (parseFloat(this.quantity) || 0) * (parseFloat(this.sellingPrice) || 0); }
              }">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="form-label">Product</label>
                    <p class="text-white font-medium">{{ $product->name }}</p>
                </div>

                <div>
                    <label for="report_date" class="form-label">Report Date</label>
                    <input id="report_date" type="date" name="report_date"
                           value="{{ old('report_date', $dailyReport->report_date->format('Y-m-d')) }}" required class="input-field">
                    @error('report_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="quantity_sold" class="form-label">Quantity Sold</label>
                    <input id="quantity_sold" type="number" name="quantity_sold" min="1"
                           value="{{ old('quantity_sold', $dailyReport->quantity_sold) }}" required
                           x-model.number="quantity"
                           class="input-field">
                    @error('quantity_sold') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="selling_price" class="form-label">Selling Price (FRW)</label>
                    <input id="selling_price" type="number" step="0.01" min="0"
                           name="selling_price"
                           value="{{ old('selling_price', $dailyReport->selling_price) }}" required
                           x-model.number="sellingPrice"
                           class="input-field">
                    @error('selling_price') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Total Revenue</label>
                    <p class="text-2xl font-bold text-teal-400" x-text="'FRW ' + total.toFixed(2)">FRW 0.00</p>
                </div>

                <div>
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select id="payment_method" name="payment_method" required class="input-field">
                        <option value="cash" {{ old('payment_method', $dailyReport->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="mobile_money" {{ old('payment_method', $dailyReport->payment_method) == 'mobile_money' ? 'selected' : '' }}>Momo</option>
                    </select>
                    @error('payment_method') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="form-label">Notes <span class="text-neutral-500">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="2" class="input-field" placeholder="Any notes about this sale...">{{ old('notes', $dailyReport->notes) }}</textarea>
                    @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Update Report</button>
                <a href="{{ route('products.show', $product) }}" class="text-sm text-neutral-400 hover:text-neutral-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
