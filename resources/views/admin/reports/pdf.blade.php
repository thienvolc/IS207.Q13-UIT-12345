<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Báo cáo doanh thu</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #6610f2;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            margin-bottom: 30px;
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .summary th {
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #6610f2;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PINKCAPY - BÁO CÁO DOANH THU</h1>
        <p>Thời gian báo cáo: {{ $days }} ngày gần nhất</p>
        <p>Ngày xuất: {{ $date }}</p>
    </div>

    <table class="summary">
        <thead>
            <tr>
                <th>Tổng doanh thu</th>
                <th>Tổng đơn hàng</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 18px; font-weight: bold; color: #6610f2;">
                    {{ number_format(array_sum($revenue), 0, ',', '.') }} ₫
                </td>
                <td style="font-size: 18px; font-weight: bold;">
                    {{ number_format(array_sum($orders)) }}
                </td>
            </tr>
        </tbody>
    </table>

    <h3>Chi tiết theo ngày</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Ngày</th>
                <th class="text-right">Đơn hàng</th>
                <th class="text-right">Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labels as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">{{ $orders[$index] }}</td>
                    <td class="text-right">{{ number_format($revenue[$index], 0, ',', '.') }} ₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Người lập: Admin - PinkCapy System</p>
    </div>
</body>

</html>