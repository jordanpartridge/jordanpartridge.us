<!DOCTYPE html>
<html>
<head>
    <title>Blackjack Setup</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>Blackjack Setup</h1>
    <form action="{{ route('blackjack.setup.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="players">Select Players:</label>
            <select name="players[]" id="players" multiple class="form-control">
                <!-- Example player options, replace with dynamic data if needed -->
                <option value="player1">Player 1</option>
                <option value="player2">Player 2</option>
                <option value="player3">Player 3</option>
            </select>
        </div>

        <div class="form-group">
            <label for="defaults">Set Defaults:</label>
            <input type="text" name="defaults[example]" id="defaults" class="form-control" placeholder="Example default value">
        </div>

        <button type="submit" class="btn btn-primary">Start Game</button>
    </form>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
