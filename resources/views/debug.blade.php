<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Debug Database Connection</h1>
        
        @if(isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> {{ $error }}
            </div>
        @endif
        
        @if(isset($status))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $status }}
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Database Stats</h2>
                <p><strong>Tables Count:</strong> {{ $table_count ?? 0 }}</p>
                <p><strong>Penjualan Records:</strong> {{ $penjualan_count ?? 0 }}</p>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">Connection Config</h2>
                <pre class="text-xs bg-gray-100 p-2 rounded overflow-auto">{{ json_encode($connection_config ?? [], JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        
        @if(isset($sample_data) && $sample_data->isNotEmpty())
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Sample Data (5 records)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Nomor Nota</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Grand Total</th>
                                <th class="px-4 py-2">Tipe Pelanggan</th>
                                <th class="px-4 py-2">isPosting</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sample_data as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->nomor_nota }}</td>
                                <td class="px-4 py-2">{{ $item->tgl }}</td>
                                <td class="px-4 py-2">{{ number_format($item->grand_total) }}</td>
                                <td class="px-4 py-2">{{ $item->tipe_pelanggan }}</td>
                                <td class="px-4 py-2">{{ $item->isPosting }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        @if(isset($tables))
            <div>
                <h2 class="text-lg font-semibold mb-2">Available Tables</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($tables as $table)
                        @php
                            $tableName = array_values((array)$table)[0];
                        @endphp
                        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
                            {{ $tableName }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="/" class="text-blue-600 hover:text-blue-800">← Back to Dashboard</a>
            @if(isset($penjualan_count) && $penjualan_count == 0)
                <a href="/generate-dummy" class="ml-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Generate Dummy Data
                </a>
            @endif
        </div>
    </div>
</body>
</html>