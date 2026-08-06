<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
        }
        .header {
            border-bottom: 2px solid #14b8a6;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #0f766e;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th,
        table td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            text-align: left;
        }
        thead th {
            background: #f0fdfa;
            color: #0f766e;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }
        .name-col {
            font-weight: 600;
            width: 30%;
        }
        .date-col {
            width: 14%;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        tr:nth-child(even) td {
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HAHA Inventory System &mdash; All Products</h1>
        <p>Generated on {{ now()->format('F j, Y') }} &middot; {{ $products->count() }} products</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="name-col">Product Name</th>
                @for ($i = 1; $i <= 5; $i++)
                    <th class="date-col">date:...../....../.......</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $index => $product)
                <tr>
                    <td class="name-col">{{ $product->name }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                        <td class="date-col"></td>
                    @endfor
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#6b7280; padding:16px;">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
