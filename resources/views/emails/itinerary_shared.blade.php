<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itinerary Shared</title>
    <style>
        /* Add custom styles to your email */
        .email-container {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-header {
            background-color: #0066cc;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .email-body {
            background-color: white;
            padding: 20px;
            margin: 10px 0;
        }
        .email-footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            padding: 10px;
        }
        .btn {
            background-color: #0066cc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Itinerary Shared with You</h2>
        </div>
        <div class="email-body">
            <p>Hi there,</p>
            <p>Your friend has shared a new itinerary with you!</p>
            <p><strong>Destination:</strong> {{ $itinerary->destination }}</p>
            <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($itinerary->start_date)->format('F d, Y') }}</p>
            <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($itinerary->end_date)->format('F d, Y') }}</p>
            <p><strong>Budget:</strong> ${{ number_format($itinerary->budget, 2) }}</p>
            <p><strong>Description:</strong> {{ $itinerary->description }}</p>

            <a href="{{ $url }}" class="btn">View the Itinerary</a>
        </div>
        <div class="email-footer">
            <p>This email was sent by Travel Companion App.</p>
        </div>
    </div>
</body>
</html>
