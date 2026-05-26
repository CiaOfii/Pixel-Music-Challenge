<?php
require_once 'config/koneksi.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$userCoins = $_SESSION['coins'];
$userLevel = $_SESSION['level'];
$unlockedLevels = $_SESSION['unlocked_levels'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Gameplay - Pixel Music Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; user-select: none; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
            overflow-x: hidden;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0,0,0,0.1) 0px, rgba(0,0,0,0.1) 2px, transparent 2px, transparent 4px);
            pointer-events: none;
        }
        .game-container { max-width: 800px; margin: 0 auto; padding: 20px; position: relative; z-index: 1; }
        .score-panel {
            background: rgba(10, 10, 42, 0.95);
            border: 3px solid #ffcc00;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            border-radius: 12px;
        }
        .score-panel > div { background: #1a1a3a; padding: 8px 12px; border-radius: 6px; font-size: 9px; }
        .level-info { text-align: center; margin-bottom: 15px; padding: 10px; background: rgba(0,0,0,0.5); border-radius: 8px; }
        .level-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; font-size: 12px; }
        .question-box {
            background: rgba(10, 10, 42, 0.95);
            border: 3px solid #00ffcc;
            padding: 30px 20px;
            margin: 20px 0;
            text-align: center;
            border-radius: 12px;
        }
        .lyric-text { font-size: 12px; margin: 20px 0; color: #ffffff; line-height: 1.8; }
        .question-counter { font-size: 9px; color: #ff66cc; text-align: center; margin-bottom: 10px; }
        .timer-container { background: #2a2a4a; border: 2px solid #ffcc00; height: 25px; margin: 20px 0; border-radius: 4px; overflow: hidden; }
        .timer-bar { background: linear-gradient(90deg, #00ff66, #ffcc00); width: 100%; height: 100%; transition: width 0.1s linear; }
        .answer-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 30px 0; }
        .answer-btn {
            font-family: 'Press Start 2P', monospace;
            background: #1a1a3a;
            border: 3px solid #ff66cc;
            color: #ff66cc;
            padding: 15px;
            cursor: pointer;
            text-align: left;
            transition: all 0.1s;
            font-size: 9px;
            border-radius: 8px;
        }
        .answer-btn:hover:not(:disabled) { background: #ff66cc; color: #1a1a3a; transform: scale(1.02); }
        .answer-btn.disabled, .answer-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .audio-controls { margin: 20px 0; }
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
            border-radius: 8px;
        }
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Press Start 2P', monospace;
            font-size: 20px;
            padding: 20px 40px;
            background: #0a0a2a;
            border: 4px solid;
            z-index: 1000;
            animation: fadeOut 1s ease forwards;
            text-align: center;
            white-space: nowrap;
            border-radius: 12px;
        }
        .notification.correct { border-color: #00ff66; color: #00ff66; background: #0a2a1a; }
        .notification.wrong { border-color: #ff3366; color: #ff3366; background: #2a0a1a; }
        @keyframes fadeOut {
            0% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            70% { opacity: 1; }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(1.5); visibility: hidden; }
        }
        .shake { animation: shakeAnim 0.3s ease-in-out; }
        @keyframes shakeAnim { 0%,100%{transform:translateX(0);} 25%{transform:translateX(-5px);} 75%{transform:translateX(5px);} }
        .glitch { animation: glitch 0.2s infinite; color: #ff3366 !important; }
        @keyframes glitch { 0%{text-shadow:-2px 0 #ff3366,2px 0 #00ffcc;} 50%{text-shadow:2px 0 #ff3366,-2px 0 #00ffcc;} 100%{text-shadow:-2px 0 #ff3366,2px 0 #00ffcc;} }
        .feedback-box { margin-top: 20px; padding: 15px; background: #1a1a3a; border: 1px solid #ffcc00; display: none; font-size: 9px; border-radius: 8px; }
        .feedback-box.show { display: block; }
        .particle { position: fixed; width: 6px; height: 6px; background: #00ffcc; pointer-events: none; border-radius: 50%; animation: particleFloat 1s ease-out forwards; z-index: 9999; }
        @keyframes particleFloat { 0%{transform:translateY(0) scale(1); opacity:1;} 100%{transform:translateY(-100px) scale(0); opacity:0;} }
        #audioProgress { margin-top: 10px; }
        #audioProgressBar { background: linear-gradient(90deg, #00ffcc, #ffcc00); transition: width 0.1s linear; }
        #playFullAudioBtn { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{transform:scale(1);} 50%{transform:scale(1.05); background:#ffcc00; color:#0a0a2a;} }
        .back-btn { margin-top: 20px; text-align: center; }
        @media (max-width: 768px) {
            .answer-grid { grid-template-columns: 1fr; }
            .notification { font-size: 12px; white-space: normal; width: 80%; }
            .score-panel { font-size: 7px; }
            .answer-btn { font-size: 7px; padding: 10px; }
            .lyric-text { font-size: 9px; }
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="score-panel">
            <div>🎮 SCORE: <span id="score">0</span></div>
            <div>🔥 STREAK: <span id="streak">0</span></div>
            <div>🪙 COINS: <span id="coins"><?= $userCoins ?></span></div>
            <div>⭐ XP: <span id="xp">0</span></div>
            <div>⏱️ TIME: <span id="timeLeft">30</span>s</div>
        </div>
        
        <div class="level-info" id="levelInfo"></div>
        <div class="question-counter" id="questionCounter">📀 SOAL 1 / 10</div>
        <div class="timer-container"><div class="timer-bar" id="timerBar" style="width: 100%"></div></div>
        <div class="question-box">
            <div class="lyric-text" id="lyricText"><span style="color:#ffcc00">"</span>Memuat soal...<span style="color:#ffcc00">"</span></div>
            <div class="audio-controls"><button class="pixel-btn" id="playAudioBtn">🔊 PUTAR AUDIO</button></div>
        </div>
        <div class="answer-grid" id="answerGrid">
            <button class="answer-btn" data-answer="A">A. ...</button>
            <button class="answer-btn" data-answer="B">B. ...</button>
            <button class="answer-btn" data-answer="C">C. ...</button>
            <button class="answer-btn" data-answer="D">D. ...</button>
        </div>
        <div class="feedback-box" id="feedbackBox"></div>
        <div class="back-btn"><button class="pixel-btn" onclick="location.href='dashboard.php'" style="background:#ff3366; border-color:#ff3366;">← KEMBALI KE MENU</button></div>
    </div>
    
    <audio id="bgmAudio" loop><source src="assets/audio/bgm/chiptune.mp3" type="audio/mpeg"></audio>
    <audio id="sfxCorrect"><source src="assets/audio/sfx/correct.wav" type="audio/wav"></audio>
    <audio id="sfxWrong"><source src="assets/audio/sfx/wrong.wav" type="audio/wav"></audio>
    <audio id="sfxCountdown"><source src="assets/audio/sfx/countdown.wav" type="audio/wav"></audio>
    
    <script>
        // =====================================================
        // LEVEL CONFIGURATION
        // =====================================================
        const levelConfig = {
            'Pemula': { name: '🌱 PEMULA', color: '#00ff66', timeLimit: 45, pointsPerCorrect: 10, questionsCount: 8, unlockNext: 'Normal' },
            'Normal': { name: '⚡ NORMAL', color: '#00ffcc', timeLimit: 30, pointsPerCorrect: 12, questionsCount: 8, unlockNext: 'Hard' },
            'Hard': { name: '🔥 HARD', color: '#ff6600', timeLimit: 20, pointsPerCorrect: 15, questionsCount: 6, unlockNext: 'Expert' },
            'Expert': { name: '💀 EXPERT', color: '#ff3366', timeLimit: 15, pointsPerCorrect: 20, questionsCount: 4, unlockNext: null }
        };
        
        let selectedLevel = localStorage.getItem('selectedLevel') || 'Pemula';
        let config = levelConfig[selectedLevel];
        
        // Game state
        let currentQuestion = null;
        let currentScore = 0;
        let currentStreak = 0;
        let currentCoins = <?= $userCoins ?>;
        let currentXP = 0;
        let timeLeft = config.timeLimit;
        let timerInterval = null;
        let currentQuestionIndex = 0;
        let totalQuestions = config.questionsCount;
        let isGameActive = true;
        let answered = false;
        let currentPreviewAudio = null;
        let totalCorrect = 0;
        let bestStreak = 0;
        let levelCleared = false;
        
        // ========== SISTEM SOAL TIDAK BERULANG ==========
        let usedQuestionIds = [];
        
        // ========== VARIABEL UNTUK COUNTDOWN SETELAH MUSIK ==========
        let countdownStarted = false;
        let hasAnswered = false;

        document.getElementById('levelInfo').innerHTML = `<div class="level-badge" style="background: ${config.color}20; border: 2px solid ${config.color}; color: ${config.color}">${config.name} | +${config.pointsPerCorrect} POIN/BENAR | ⏱️ ${config.timeLimit} DETIK</div>`;
        document.getElementById('questionCounter').innerHTML = `📀 SOAL 1 / ${totalQuestions}`;
        document.getElementById('coins').innerText = currentCoins;
        
        // =====================================================
        // HELPER FUNCTIONS
        // =====================================================
        function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
        function formatTime(seconds) { if (isNaN(seconds) || !seconds) return '0:00'; const mins = Math.floor(seconds / 60); const secs = Math.floor(seconds % 60); return `${mins}:${secs.toString().padStart(2, '0')}`; }
        function updateUI() { document.getElementById('score').innerText = currentScore; document.getElementById('streak').innerText = currentStreak; document.getElementById('coins').innerText = currentCoins; document.getElementById('xp').innerText = currentXP; }
        function showNotification(message, type) { const notif = document.createElement('div'); notif.className = `notification ${type}`; notif.innerText = message; document.body.appendChild(notif); setTimeout(() => notif.remove(), 1000); }
        
        function playSfx(type) {
            let audioId = type === 'correct' ? 'sfxCorrect' : 'sfxWrong';
            const audio = document.getElementById(audioId);
            if (audio) { audio.currentTime = 0; audio.play().catch(e => console.log('SFX error:', e)); }
        }
        
        function playCountdownSound() {
            const audio = document.getElementById('sfxCountdown');
            if (audio) { audio.currentTime = 0; audio.play().catch(e => console.log('Countdown error:', e)); }
        }
        
        function createParticles() {
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * window.innerWidth + 'px';
                particle.style.top = window.innerHeight / 2 + (Math.random() - 0.5) * 100 + 'px';
                particle.style.background = `hsl(${Math.random() * 360}, 100%, 50%)`;
                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 1000);
            }
        }
        
        function hideFeedbackBox() {
            const feedbackBox = document.getElementById('feedbackBox');
            feedbackBox.style.display = 'none';
            feedbackBox.innerHTML = '';
        }
        
        function updateTimerDisplay() {
            document.getElementById('timeLeft').innerText = timeLeft;
            const percent = (timeLeft / config.timeLimit) * 100;
            document.getElementById('timerBar').style.width = `${Math.max(0, percent)}%`;
        }
        
        // ========== FUNGSI COUNTDOWN YANG DIMULAI SETELAH MUSIK ==========
        function startCountdown() {
            if (countdownStarted || hasAnswered) return;
            
            countdownStarted = true;
            timeLeft = config.timeLimit;
            updateTimerDisplay();
            
            timerInterval = setInterval(() => {
                if (!isGameActive || answered || hasAnswered) return;
                
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 10 && timeLeft > 0) {
                    document.getElementById('timeLeft').classList.add('glitch');
                    playCountdownSound();
                } else {
                    document.getElementById('timeLeft').classList.remove('glitch');
                }
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    if (!answered && !hasAnswered) {
                        handleWrongAnswer(true);
                    }
                }
            }, 1000);
        }
        
        // ========== FUNGSI PLAY AUDIO DENGAN COUNTDOWN SETELAH SELESAI ==========
        function playAudio() {
            if (currentPreviewAudio) {
                currentPreviewAudio.pause();
                currentPreviewAudio = null;
            }
            
            // Reset status countdown
            if (timerInterval) clearInterval(timerInterval);
            countdownStarted = false;
            hasAnswered = false;
            document.getElementById('timeLeft').innerText = '🎵 Memutar musik...';
            document.getElementById('timeLeft').classList.remove('glitch');
            document.getElementById('timerBar').style.width = '100%';
            
            if (currentQuestion && currentQuestion.audio_preview) {
                currentPreviewAudio = new Audio(`assets/audio/preview/${currentQuestion.audio_preview}`);
                
                // Ketika audio selesai diputar, mulai countdown
                currentPreviewAudio.addEventListener('ended', () => {
                    if (!answered && !hasAnswered) {
                        document.getElementById('timeLeft').innerText = '⏰ Waktu menjawab!';
                        startCountdown();
                    }
                });
                
                currentPreviewAudio.play().catch(e => {
                    console.log('Autoplay blocked:', e);
                    // Jika autoplay diblokir, mulai countdown langsung
                    startCountdown();
                });
            } else {
                // Jika tidak ada audio, mulai countdown langsung
                startCountdown();
            }
        }
        
        // =====================================================
        // LOAD QUESTION - DENGAN SISTEM TIDAK BERULANG
        // =====================================================
        async function loadQuestion() {
            if (!isGameActive) return;
            
            hideFeedbackBox();
            
            // Reset semua status
            if (currentPreviewAudio) {
                currentPreviewAudio.pause();
                currentPreviewAudio = null;
            }
            if (timerInterval) clearInterval(timerInterval);
            countdownStarted = false;
            hasAnswered = false;
            answered = false;
            document.getElementById('timeLeft').innerText = '🎵 Memuat...';
            
            document.querySelectorAll('.answer-btn').forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('disabled');
            });
            
            try {
                let url = `api/get_question.php?level=${selectedLevel}&mode=arcade`;
                if (usedQuestionIds.length > 0) {
                    url += `&exclude=${usedQuestionIds.join(',')}`;
                }
                
                console.log('=== LOADING SOAL ===');
                console.log('URL:', url);
                console.log('Used IDs (sudah keluar):', usedQuestionIds);
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.error || !data.id) {
                    if (usedQuestionIds.length > 0) {
                        console.log('No more unique questions, resetting...');
                        usedQuestionIds = [];
                        const retryResponse = await fetch(`api/get_question.php?level=${selectedLevel}&mode=arcade`);
                        const retryData = await retryResponse.json();
                        if (retryData.error || !retryData.id) {
                            document.getElementById('lyricText').innerHTML = '⚠️ Belum ada soal untuk level ini! Hubungi admin.';
                            return;
                        }
                        currentQuestion = retryData;
                    } else {
                        document.getElementById('lyricText').innerHTML = '⚠️ Belum ada soal untuk level ini! Hubungi admin.';
                        return;
                    }
                } else {
                    currentQuestion = data;
                }
                
                if (usedQuestionIds.includes(currentQuestion.id)) {
                    console.warn('⚠️ DUPLICATE! Mencari soal lain...');
                    loadQuestion();
                    return;
                }
                
                usedQuestionIds.push(currentQuestion.id);
                
                console.log('Loaded Question ID:', currentQuestion.id);
                console.log('Updated Used IDs:', usedQuestionIds);
                
                document.getElementById('lyricText').innerHTML = `"${escapeHtml(currentQuestion.lyric_start)}"`;
                document.getElementById('questionCounter').innerHTML = `📀 SOAL ${currentQuestionIndex + 1} / ${totalQuestions}`;
                
                const answers = [currentQuestion.option_a, currentQuestion.option_b, currentQuestion.option_c, currentQuestion.option_d];
                document.querySelectorAll('.answer-btn').forEach((btn, idx) => {
                    btn.innerHTML = `${String.fromCharCode(65+idx)}. ${escapeHtml(answers[idx])}`;
                });
                
                // Langsung putar audio (countdown akan dimulai setelah audio selesai)
                setTimeout(() => playAudio(), 300);
                
            } catch (err) {
                console.error('Load question error:', err);
                document.getElementById('lyricText').innerHTML = '⚠️ Gagal memuat soal. Refresh halaman.';
            }
        }
        
        // =====================================================
        // SHOW FEEDBACK BOX WITH FULL AUDIO
        // =====================================================
        async function showAnswerWithFullAudio(isCorrect) {
            return new Promise((resolve) => {
                if (currentPreviewAudio) { currentPreviewAudio.pause(); currentPreviewAudio = null; }
                if (timerInterval) clearInterval(timerInterval);
                
                const bgmAudio = document.getElementById('bgmAudio');
                let wasBGMPaused = bgmAudio.paused;
                if (!wasBGMPaused) { bgmAudio.pause(); }
                document.querySelectorAll('.answer-btn').forEach(btn => { btn.disabled = true; });
                
                const feedbackBox = document.getElementById('feedbackBox');
                let correctText = '';
                switch(currentQuestion.correct_answer) {
                    case 'A': correctText = currentQuestion.option_a; break;
                    case 'B': correctText = currentQuestion.option_b; break;
                    case 'C': correctText = currentQuestion.option_c; break;
                    case 'D': correctText = currentQuestion.option_d; break;
                }
                const statusBadge = isCorrect ? '<span style="color:#00ff66;">✅ JAWABAN BENAR!</span>' : '<span style="color:#ff6666;">❌ JAWABAN SALAH!</span>';
                
                feedbackBox.style.display = 'block';
                feedbackBox.innerHTML = `
                    <div style="text-align: center; margin-bottom: 15px;">${statusBadge}</div>
                    <div style="color:#00ff66; margin-bottom: 10px;">📝 JAWABAN BENAR: ${currentQuestion.correct_answer}. ${escapeHtml(correctText)}</div>
                    <div style="color:#ffcc00; margin-bottom: 10px;">🎤 ${escapeHtml(currentQuestion.artist || 'Unknown Artist')} | ${currentQuestion.year || '?'}</div>
                    <div style="color:#aaa; margin-bottom: 15px; font-size: 8px; border-left: 2px solid #ffcc00; padding-left: 10px;">"${escapeHtml(currentQuestion.full_lyric_preview || currentQuestion.lyric_start)}"</div>
                    <div style="text-align: center; margin: 15px 0;"><button id="playFullAudioBtn" class="pixel-btn" style="padding: 8px 16px; font-size: 8px;">🎵 PUTAR LAGU LENGKAP 🎵</button></div>
                    <div id="audioProgress" style="margin-top: 10px; display: none;"><div style="background: #2a2a4a; border-radius: 10px; height: 8px; overflow: hidden;"><div id="audioProgressBar" style="background: #00ffcc; width: 0%; height: 100%; transition: width 0.1s linear;"></div></div><div style="text-align: center; margin-top: 5px; font-size: 7px; color: #aaa;"><span id="currentTime">0:00</span> / <span id="duration">0:00</span></div></div>
                    <div style="text-align: center; margin-top: 15px; font-size: 8px; color: #ffcc00;">🎵 Memutar lagu lengkap... Soal berikutnya akan muncul setelah audio selesai 🎵</div>
                `;
                
                let audioFull = null; let progressInterval = null;
                function cleanup() { if (progressInterval) clearInterval(progressInterval); if (audioFull) { audioFull.pause(); audioFull = null; } if (!wasBGMPaused) { bgmAudio.play().catch(e => console.log('BGM resume error:', e)); } hideFeedbackBox(); resolve(); }
                function updateProgress() { if (audioFull && !audioFull.paused) { const current = audioFull.currentTime; const durasi = audioFull.duration; if (durasi && !isNaN(durasi)) { document.getElementById('audioProgressBar').style.width = (current / durasi) * 100 + '%'; document.getElementById('currentTime').innerText = formatTime(current); } } }
                if (currentQuestion.audio_full && currentQuestion.audio_full !== '') {
                    audioFull = new Audio(`assets/audio/full/${currentQuestion.audio_full}`);
                    audioFull.addEventListener('loadedmetadata', () => { const durasi = audioFull.duration; if (durasi && !isNaN(durasi)) { document.getElementById('duration').innerText = formatTime(durasi); document.getElementById('audioProgress').style.display = 'block'; } });
                    audioFull.addEventListener('ended', () => { cleanup(); });
                    audioFull.addEventListener('error', () => { setTimeout(cleanup, 3000); });
                    audioFull.play().catch(e => { setTimeout(cleanup, 3000); });
                    progressInterval = setInterval(updateProgress, 500);
                } else { setTimeout(cleanup, 3000); }
                setTimeout(() => { const playBtn = document.getElementById('playFullAudioBtn'); if (playBtn) { playBtn.onclick = () => { if (audioFull && audioFull.paused) audioFull.play().catch(e => console.log('Manual play error:', e)); }; } }, 100);
                setTimeout(() => { if (audioFull && !audioFull.ended) cleanup(); }, 30000);
            });
        }
        
        // =====================================================
        // ANSWER HANDLER
        // =====================================================
        async function handleAnswer(selectedAnswer) {
            if (answered || !isGameActive) return;
            
            // Matikan audio preview jika masih diputar
            if (currentPreviewAudio) {
                currentPreviewAudio.pause();
                currentPreviewAudio = null;
            }
            if (timerInterval) clearInterval(timerInterval);
            
            hasAnswered = true;
            answered = true;
            
            const isCorrect = (selectedAnswer === currentQuestion.correct_answer);
            
            if (isCorrect) {
                totalCorrect++;
                let pointsGained = config.pointsPerCorrect;
                currentStreak++;
                if (currentStreak === 3) pointsGained += 5;
                if (currentStreak === 5) pointsGained += 10;
                if (currentStreak === 10) pointsGained += 20;
                if (currentStreak > bestStreak) bestStreak = currentStreak;
                currentScore += pointsGained;
                currentXP += Math.floor(pointsGained / 2);
                currentCoins += 5;
                updateUI();
                showNotification(`✅ CORRECT! +${pointsGained}`, 'correct');
                playSfx('correct');
                createParticles();
                await showAnswerWithFullAudio(true);
                currentQuestionIndex++;
                if (currentQuestionIndex >= totalQuestions) { levelCleared = true; endGame(); } 
                else { loadQuestion(); }
            } else {
                handleWrongAnswer(false);
            }
        }
        
        function handleWrongAnswer(isTimeout = false) {
            if (currentPreviewAudio) { currentPreviewAudio.pause(); currentPreviewAudio = null; }
            if (timerInterval) clearInterval(timerInterval);
            
            hasAnswered = true;
            currentStreak = 0;
            updateUI();
            showNotification(isTimeout ? '⏰ TIME OUT!' : '❌ WRONG!', 'wrong');
            playSfx('wrong');
            document.body.classList.add('shake'); setTimeout(() => document.body.classList.remove('shake'), 300);
            showAnswerWithFullAudio(false).then(() => {
                currentQuestionIndex++;
                if (currentQuestionIndex >= totalQuestions) { endGame(); } 
                else { loadQuestion(); }
            });
        }
        
        // =====================================================
        // END GAME - RESET USED IDS
        // =====================================================
        async function endGame() {
            isGameActive = false;
            if (timerInterval) clearInterval(timerInterval);
            if (currentPreviewAudio) currentPreviewAudio.pause();
            
            usedQuestionIds = [];
            
            const totalWrong = totalQuestions - totalCorrect;
            try {
                const response = await fetch('api/save_score.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        score: currentScore, level: selectedLevel, mode: 'level',
                        correct: totalCorrect, wrong: totalWrong, streak: bestStreak,
                        coins: currentCoins, xp: currentXP,
                        level_cleared: levelCleared, unlock_next: levelCleared ? config.unlockNext : null
                    })
                });
                const result = await response.json();
                localStorage.setItem('finalScore', currentScore);
                localStorage.setItem('totalCorrect', totalCorrect);
                localStorage.setItem('totalWrong', totalWrong);
                localStorage.setItem('bestStreak', bestStreak);
                localStorage.setItem('selectedLevel', selectedLevel);
                localStorage.setItem('levelCleared', levelCleared);
                localStorage.setItem('xpGained', currentXP);
                localStorage.setItem('coinsGained', currentCoins - <?= $userCoins ?>);
                localStorage.setItem('unlockedNewLevel', result.unlocked_new_level || '');
                window.location.href = 'hasil.php';
            } catch(e) { window.location.href = 'hasil.php'; }
        }
        
        // =====================================================
        // EVENT LISTENERS
        // =====================================================
        document.querySelectorAll('.answer-btn').forEach(btn => {
            btn.addEventListener('click', () => { if (!isGameActive || answered) return; handleAnswer(btn.dataset.answer); });
        });
        document.getElementById('playAudioBtn').addEventListener('click', () => { playAudio(); });
        
        let bgmStarted = false;
        document.body.addEventListener('click', function startBGM() {
            if (!bgmStarted) { const bgm = document.getElementById('bgmAudio'); bgm.volume = 0.15; bgm.play().catch(e => console.log('BGM error:', e)); bgmStarted = true; }
        });
        
        loadQuestion();
    </script>
</body>
</html>