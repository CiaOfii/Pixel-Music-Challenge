<?php
require_once 'config/koneksi.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Ambil data dari localStorage via JS
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil - Pixel Music Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .result-box {
            background: rgba(26, 26, 58, 0.95);
            border: 4px solid #00ffcc;
            padding: 40px;
            text-align: center;
            margin-top: 50px;
        }
        
        .final-score {
            font-size: 48px;
            color: #ffcc00;
            margin: 20px 0;
            text-shadow: 4px 4px 0 #ff66cc;
        }
        
        .grade {
            font-size: 64px;
            margin: 20px 0;
        }
        
        .stats {
            background: #1a1a3a;
            border: 2px solid #00ffcc;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .stats p {
            margin: 10px 0;
            font-size: 10px;
        }
        
        .level-cleared {
            background: #0a2a1a;
            border: 2px solid #00ff66;
            padding: 15px;
            margin: 20px 0;
            color: #00ff66;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .pixel-btn {
            font-family: 'Press Start 2P', monospace;
            background: #2a2a4a;
            border: 4px solid #00ffcc;
            color: #00ffcc;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.1s ease;
            box-shadow: 4px 4px 0 #008866;
            font-size: 9px;
            text-decoration: none;
        }
        
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        
        .grade-S { color: gold; text-shadow: 0 0 10px gold; }
        .grade-A { color: #00ff66; }
        .grade-B { color: #00ffcc; }
        .grade-C { color: #ffcc00; }
        .grade-D { color: #ff6666; }
        
        @media (max-width: 768px) {
            .result-box { padding: 25px; margin: 20px; }
            .final-score { font-size: 32px; }
            .stats p { font-size: 8px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-box">
            <h1>⚔️ GAME COMPLETE ⚔️</h1>
            
            <div class="final-score" id="finalScore">0</div>
            
            <div class="grade" id="grade">?</div>
            
            <div class="stats">
                <p>✅ JAWABAN BENAR: <span id="correctCount">0</span> / <span id="totalQuestions">0</span></p>
                <p>❌ JAWABAN SALAH: <span id="wrongCount">0</span></p>
                <p>🔥 STREAK TERPANJANG: <span id="bestStreak">0</span></p>
                <p>⭐ XP DIDAPAT: <span id="xpGained">0</span></p>
                <p>🪙 KOIN DIDAPAT: <span id="coinsGained">0</span></p>
                <p>🎮 LEVEL: <span id="levelName"></span></p>
            </div>
            
            <div id="unlockMessage" style="display:none;" class="level-cleared">
                🎉 SELAMAT! 🎉<br>
                Level baru telah terbuka! <span id="newLevelName"></span>
            </div>
            
            <div class="btn-group">
                <a href="dashboard.php" class="pixel-btn">🏠 MENU UTAMA</a>
                <a href="game.php" class="pixel-btn" id="replayBtn">🔄 MAIN LAGI</a>
                <a href="leaderboard.php" class="pixel-btn">🏆 LEADERBOARD</a>
            </div>
        </div>
    </div>
    
    <script>
        const finalScore = localStorage.getItem('finalScore') || 0;
        const totalCorrect = parseInt(localStorage.getItem('totalCorrect') || 0);
        const totalWrong = parseInt(localStorage.getItem('totalWrong') || 0);
        const bestStreak = parseInt(localStorage.getItem('bestStreak') || 0);
        const selectedLevel = localStorage.getItem('selectedLevel') || 'Pemula';
        const levelCleared = localStorage.getItem('levelCleared') === 'true';
        const xpGained = localStorage.getItem('xpGained') || 0;
        const coinsGained = localStorage.getItem('coinsGained') || 0;
        const unlockedNewLevel = localStorage.getItem('unlockedNewLevel') || '';
        
        const totalQuestions = 
            selectedLevel === 'Pemula' ? 8 :
            selectedLevel === 'Normal' ? 10 :
            selectedLevel === 'Hard' ? 12 : 15;
        
        document.getElementById('finalScore').innerText = finalScore;
        document.getElementById('correctCount').innerText = totalCorrect;
        document.getElementById('totalQuestions').innerText = totalQuestions;
        document.getElementById('wrongCount').innerText = totalWrong;
        document.getElementById('bestStreak').innerText = bestStreak;
        document.getElementById('xpGained').innerText = xpGained;
        document.getElementById('coinsGained').innerText = coinsGained;
        
        const levelNames = {
            'Pemula': '🌱 PEMULA',
            'Normal': '⚡ NORMAL',
            'Hard': '🔥 HARD',
            'Expert': '💀 EXPERT'
        };
        document.getElementById('levelName').innerText = levelNames[selectedLevel];
        
        // Grade calculation
        let grade = '';
        let gradeClass = '';
        const percentage = (totalCorrect / totalQuestions) * 100;
        
        if (percentage >= 90) { grade = 'S'; gradeClass = 'grade-S'; }
        else if (percentage >= 75) { grade = 'A'; gradeClass = 'grade-A'; }
        else if (percentage >= 60) { grade = 'B'; gradeClass = 'grade-B'; }
        else if (percentage >= 45) { grade = 'C'; gradeClass = 'grade-C'; }
        else { grade = 'D'; gradeClass = 'grade-D'; }
        
        const gradeEl = document.getElementById('grade');
        gradeEl.innerText = grade;
        gradeEl.className = `grade ${gradeClass}`;
        
        if (levelCleared && unlockedNewLevel) {
            const unlockDiv = document.getElementById('unlockMessage');
            document.getElementById('newLevelName').innerText = levelNames[unlockedNewLevel];
            unlockDiv.style.display = 'block';
        }
        
        // Replay button
        document.getElementById('replayBtn').href = `game.php`;
        
        // Clear localStorage
        localStorage.removeItem('finalScore');
        localStorage.removeItem('totalCorrect');
        localStorage.removeItem('totalWrong');
        localStorage.removeItem('bestStreak');
        localStorage.removeItem('selectedLevel');
        localStorage.removeItem('levelCleared');
        localStorage.removeItem('xpGained');
        localStorage.removeItem('coinsGained');
        localStorage.removeItem('unlockedNewLevel');
    </script>
</body>
</html>