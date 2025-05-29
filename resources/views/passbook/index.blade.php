<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apple Wallet Pass</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-6">
        <div class="text-center">
            <img src="{{ asset('images/apple-wallet-icon.svg') }}" 
                 alt="Apple Wallet" 
                 class="w-24 h-24 mx-auto cursor-pointer hover:scale-110 transition-transform duration-200"
                 onclick="showPassCard()">
            <h1 class="mt-4 text-2xl font-bold text-gray-800">Add to Apple Wallet</h1>
        </div>

        <!-- Pass Card (Hidden by default) -->
        <div id="passCard" class="hidden mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800">{{ $eventName }}</h2>
                <p class="mt-2 text-gray-600">{{ $location }}</p>
                <p class="mt-1 text-gray-600">{{ $dateTime }}</p>
                
                <a href="{{ route('passbook.download') }}" 
                   class="mt-6 block w-full bg-black text-white text-center py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    Download Pass
                </a>
            </div>
        </div>
    </div>

    <script>
        function showPassCard() {
            const passCard = document.getElementById('passCard');
            passCard.classList.toggle('hidden');
        }
    </script>
</body>
</html> 