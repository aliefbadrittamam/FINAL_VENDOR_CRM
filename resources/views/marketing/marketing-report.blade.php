<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Market Trend Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb; /* bg-gray-50 equivalent */
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-gray-600 {
            color: #4a5568;
        }

        .text-gray-700 {
            color: #2d3748;
        }

        .text-gray-800 {
            color: #1a202c;
        }

        .text-gray-400 {
            color: #e2e8f0;
        }

        .bg-gray-100 {
            background-color: #f7fafc;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .bg-blue-500 {
            background-color: #3b82f6;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-8 {
            margin-top: 2rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .space-y-1 {
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
        }

        th {
            background-color: #3b82f6;
            color: white;
            text-align: left;
        }

        td {
            background-color: white;
            text-align: left;
        }

        tr:hover {
            background-color: #f7fafc;
        }

        .shadow-lg {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .max-w-5xl {
            max-width: 1280px;
            margin: 0 auto;
        }
        .page-break{
            page-break-after: always;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <livewire:marketing.report />
</body>
</html>
