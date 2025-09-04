<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - USFTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/crm/assets/style.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DancingScript';
            src: url('../assets/fonts/static/DancingScript-SemiBold.ttf') format('truetype');
        }

        .pdf-head-name {
            font-family: 'DancingScript', sans-serif;
            font-size: 6em;
            font-weight: 600;
            letter-spacing: 2px;
            color: #0d3483;
        }

        .span-blue {
            color: #1a6493;
        }

        .span-red {
            color: #c6265c;
        }

        .pdf-header-w {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 900;
            letter-spacing: -3px;
        }

        .pdf-head-title {
            font-size: 5em;
        }


        .rfq-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 15px;
        }

        .rfq-header {
            border-bottom: 3px solid #397099;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .rfq-table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
        }

        .rfq-table th,
        .rfq-table td {
            padding: 8px;
            vertical-align: top;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .desc-field input {
            flex: 1;
            min-width: 0;
        }

        .desc-field strong {
            display: block;
            color: #397099;
        }
    </style>
</head>

<body class="bg-light">