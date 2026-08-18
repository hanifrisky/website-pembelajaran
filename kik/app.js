// State Management
let allCategoriesData = {}; // Raw mapped JSON data
let quizQuestions = [];     // Selected 50 questions for current session
let currentQuestionIndex = 0;
let userAnswers = [];       // Array to store user selections: { selectedText, isCorrect, correctText, questionObj, shuffledOptions }
let activeSelection = false;// Lock to prevent multiple selections per question
let activeStudentName = '';
let activeStudentClass = '';

// DOM Elements
const homeScreen = document.getElementById('home-screen');
const quizScreen = document.getElementById('quiz-screen');
const resultScreen = document.getElementById('result-screen');
const reviewScreen = document.getElementById('review-screen');

// Init
async function initQuiz() {
  try {
    const response = await fetch('soal_kik_mapped.json');
    if (!response.ok) {
      throw new Error('Gagal mengambil data soal.');
    }
    const data = await response.json();
    allCategoriesData = data.materi;
    
    // Enable start button once data is loaded
    document.getElementById('start-btn').disabled = false;
    document.getElementById('start-btn').textContent = 'Mulai Latihan';
  } catch (error) {
    console.error('Error loading questions:', error);
    alert('Gagal memuat soal. Pastikan file JSON tersedia.');
  }
}

// Fisher-Yates Shuffle Utility
function shuffleArray(array) {
  const arr = [...array];
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

// Build 50 questions: 6 from each of 8 categories + 2 random remaining
function generateQuestionPool() {
  const selectedPool = [];
  const categoryNames = Object.keys(allCategoriesData);
  const remainingPool = [];

  categoryNames.forEach(catName => {
    const catQuestions = allCategoriesData[catName].daftar_soal;
    // Store category name in each question object for easy tracking later
    catQuestions.forEach(q => q.category = catName);

    // Shuffle questions within this category
    const shuffledCat = shuffleArray(catQuestions);
    
    // Take first 6 questions
    const selected = shuffledCat.slice(0, 6);
    selectedPool.push(...selected);

    // The rest go into the global remaining pool
    const remaining = shuffledCat.slice(6);
    remainingPool.push(...remaining);
  });

  // Pick 2 more questions randomly from the remaining pool
  const shuffledRemaining = shuffleArray(remainingPool);
  const additional = shuffledRemaining.slice(0, 2);
  selectedPool.push(...additional);

  // Return fully shuffled 50 questions
  return shuffleArray(selectedPool);
}

// Prepare questions with stable shuffled options
function prepareQuizSet(questions) {
  return questions.map(q => {
    const correctText = q.options[q.answer];
    
    // Map options to array of objects
    const optionArray = Object.keys(q.options).map(key => ({
      key: key,
      text: q.options[key]
    }));

    // Shuffle options
    const shuffledOptions = shuffleArray(optionArray);

    return {
      ...q,
      correctText: correctText,
      shuffledOptions: shuffledOptions
    };
  });
}

// Start Quiz Session
function startQuiz() {
  const name = document.getElementById('student-name-input').value.trim();
  const studentClass = document.getElementById('student-class-input').value.trim();
  const validationMsg = document.getElementById('home-validation-msg');

  if (!name || !studentClass) {
    validationMsg.textContent = 'Harap isi Nama Lengkap dan Kelas sebelum memulai latihan!';
    validationMsg.style.display = 'block';
    return;
  }
  validationMsg.style.display = 'none';

  // Store student identity
  activeStudentName = name;
  activeStudentClass = studentClass;

  quizQuestions = prepareQuizSet(generateQuestionPool());
  currentQuestionIndex = 0;
  userAnswers = [];
  activeSelection = false;

  // Show Quiz Screen
  homeScreen.classList.add('hidden');
  resultScreen.classList.add('hidden');
  reviewScreen.classList.add('hidden');
  quizScreen.classList.remove('hidden');

  renderQuestion();
}

// Render Current Question
function renderQuestion() {
  activeSelection = false;
  const currentQuestion = quizQuestions[currentQuestionIndex];

  // Hide next button initially
  document.getElementById('next-btn').classList.add('hidden');

  // Update Progress
  const progressPercent = ((currentQuestionIndex) / quizQuestions.length) * 100;
  document.getElementById('progress-bar').style.width = `${progressPercent}%`;
  document.getElementById('question-number').textContent = `Soal ${currentQuestionIndex + 1} dari ${quizQuestions.length}`;

  // Update Category Badge
  const categoryBadge = document.getElementById('category-badge');
  categoryBadge.textContent = currentQuestion.category;
  categoryBadge.title = currentQuestion.category;

  // Update Question Text
  document.getElementById('question-text').textContent = currentQuestion.question;

  // Render Options
  const optionsContainer = document.getElementById('options-container');
  optionsContainer.innerHTML = '';

  currentQuestion.shuffledOptions.forEach((option, idx) => {
    const optionBtn = document.createElement('button');
    optionBtn.className = 'option-btn fade-in';
    optionBtn.style.animationDelay = `${idx * 0.05}s`;
    
    // Set text and letter labels (A, B, C, D, E dynamically based on shuffled index)
    const displayLetter = String.fromCharCode(65 + idx); // A, B, C, D, E
    
    optionBtn.innerHTML = `
      <span class="option-letter">${displayLetter}</span>
      <span class="option-text">${option.text}</span>
    `;

    optionBtn.addEventListener('click', () => handleOptionSelect(optionBtn, option.text, currentQuestion));
    optionsContainer.appendChild(optionBtn);
  });
}

// Option Selection Logic (Instant Feedback)
function handleOptionSelect(selectedBtn, selectedText, question) {
  if (activeSelection) return; // Prevent multiple clicks
  activeSelection = true;

  const optionsContainer = document.getElementById('options-container');
  const optionButtons = optionsContainer.querySelectorAll('.option-btn');
  const isCorrect = selectedText === question.correctText;

  // Record Answer
  userAnswers.push({
    questionObj: question,
    selectedText: selectedText,
    correctText: question.correctText,
    isCorrect: isCorrect,
    shuffledOptions: question.shuffledOptions
  });

  // Apply visual feedback
  optionButtons.forEach(btn => {
    btn.disabled = true; // Disable further selections
    const btnText = btn.querySelector('.option-text').textContent;

    if (btnText === question.correctText) {
      btn.classList.add('correct'); // Highlight correct answer
    } else if (btnText === selectedText && !isCorrect) {
      btn.classList.add('incorrect'); // Highlight incorrect user selection
    }
  });

  // Show "Next" button or "Finish" button
  const nextBtn = document.getElementById('next-btn');
  nextBtn.classList.remove('hidden');
  if (currentQuestionIndex === quizQuestions.length - 1) {
    nextBtn.textContent = 'Lihat Hasil';
  } else {
    nextBtn.textContent = 'Selanjutnya';
  }
}

// Next Button Handler
function handleNextQuestion() {
  if (currentQuestionIndex < quizQuestions.length - 1) {
    currentQuestionIndex++;
    renderQuestion();
  } else {
    showResults();
  }
}

// Show Result Screen with Detailed Category Analysis
function showResults() {
  quizScreen.classList.add('hidden');
  resultScreen.classList.remove('hidden');

  // Display Student Identity on results card
  document.getElementById('display-student-name').textContent = activeStudentName;
  document.getElementById('display-student-class').textContent = activeStudentClass;

  // Reset Save Button State
  document.getElementById('save-score-btn').disabled = false;
  document.getElementById('save-status-msg').style.display = 'none';

  const totalQuestions = quizQuestions.length;
  const correctAnswersCount = userAnswers.filter(ans => ans.isCorrect).length;
  const scorePercent = Math.round((correctAnswersCount / totalQuestions) * 100);

  // Render Score Circle
  document.getElementById('score-number').textContent = correctAnswersCount;
  document.getElementById('score-percent').textContent = `${scorePercent}% Benar`;

  // Status & Message based on performance
  let statusText = '';
  let statusDesc = '';
  let circleColor = '';

  if (scorePercent >= 80) {
    statusText = 'Luar Biasa! 🎉';
    statusDesc = 'Anda menguasai materi Kreativitas dan Kewirausahaan dengan sangat baik. Pertahankan prestasimu!';
    circleColor = '#10b981'; // Emerald
  } else if (scorePercent >= 60) {
    statusText = 'Cukup Baik 👍';
    statusDesc = 'Anda memahami dasar-dasar materi dengan baik. Pelajari lagi materi yang masih lemah untuk hasil maksimal.';
    circleColor = '#fbbf24'; // Amber
  } else {
    statusText = 'Butuh Belajar Lagi 📚';
    statusDesc = 'Jangan berkecil hati. Mari pelajari kembali materi-materi berikut dan lakukan latihan lagi.';
    circleColor = '#ef4444'; // Red
  }

  document.getElementById('performance-status').textContent = statusText;
  document.getElementById('performance-desc').textContent = statusDesc;
  document.getElementById('score-circle').style.borderColor = circleColor;
  document.getElementById('score-circle').style.boxShadow = `0 0 30px ${circleColor}40`;

  // Detailed performance analysis by category
  const performanceGrid = document.getElementById('performance-grid');
  performanceGrid.innerHTML = '<h3>Analisis Performa Per Materi</h3>';

  // Group scores by category
  const categoryStats = {};
  quizQuestions.forEach(q => {
    if (!categoryStats[q.category]) {
      categoryStats[q.category] = { correct: 0, total: 0 };
    }
    categoryStats[q.category].total++;
  });

  userAnswers.forEach(ans => {
    const cat = ans.questionObj.category;
    if (ans.isCorrect) {
      categoryStats[cat].correct++;
    }
  });

  // Render bars
  Object.keys(categoryStats).forEach(cat => {
    const stats = categoryStats[cat];
    const percent = Math.round((stats.correct / stats.total) * 100);
    
    let progressClass = 'low';
    if (percent >= 80) progressClass = 'high';
    else if (percent >= 50) progressClass = 'mid';

    const item = document.createElement('div');
    item.className = 'performance-item';
    item.innerHTML = `
      <div class="perf-info">
        <span class="perf-category">${cat}</span>
        <span class="perf-score">${stats.correct}/${stats.total} (${percent}%)</span>
      </div>
      <div class="perf-progress-container">
        <div class="perf-progress-bar ${progressClass}" style="width: ${percent}%"></div>
      </div>
    `;
    performanceGrid.appendChild(item);
  });
}

// Show Review Screen
function showReview() {
  resultScreen.classList.add('hidden');
  reviewScreen.classList.remove('hidden');

  const reviewList = document.getElementById('review-list');
  reviewList.innerHTML = '';

  userAnswers.forEach((ans, idx) => {
    const item = document.createElement('div');
    item.className = `review-item ${ans.isCorrect ? 'correct' : 'incorrect'} fade-in`;
    item.style.animationDelay = `${idx * 0.03}s`;

    const statusIcon = ans.isCorrect 
      ? '<span class="review-status-icon correct">✓ Benar</span>' 
      : '<span class="review-status-icon incorrect">✗ Salah</span>';

    let optionsHtml = '';
    ans.shuffledOptions.forEach((opt, oIdx) => {
      const letter = String.fromCharCode(65 + oIdx);
      let optClass = '';
      
      if (opt.text === ans.correctText) {
        optClass = 'correct-choice';
      } else if (opt.text === ans.selectedText && !ans.isCorrect) {
        optClass = 'incorrect-choice';
      }

      optionsHtml += `
        <div class="review-option ${optClass}">
          <strong>${letter}.</strong> ${opt.text}
        </div>
      `;
    });

    item.innerHTML = `
      <div class="review-item-header">
        <div class="review-meta">
          <span class="badge">Soal ${idx + 1}</span>
          <span class="badge category-badge">${ans.questionObj.category}</span>
        </div>
        ${statusIcon}
      </div>
      <div class="review-question">${ans.questionObj.question}</div>
      <div class="review-options">
        ${optionsHtml}
      </div>
    `;

    reviewList.appendChild(item);
  });
}

// Exit Review, return to results
function closeReview() {
  reviewScreen.classList.add('hidden');
  resultScreen.classList.remove('hidden');
}

// Restart Home (clear inputs)
function resetQuiz() {
  document.getElementById('student-name-input').value = '';
  document.getElementById('student-class-input').value = '';
  document.getElementById('home-validation-msg').style.display = 'none';

  homeScreen.classList.remove('hidden');
  resultScreen.classList.add('hidden');
  quizScreen.classList.add('hidden');
  reviewScreen.classList.add('hidden');
}

async function handleSaveScore() {
  const statusMsg = document.getElementById('save-status-msg');
  
  const totalQuestions = quizQuestions.length;
  const correctAnswersCount = userAnswers.filter(ans => ans.isCorrect).length;
  const scorePercent = Math.round((correctAnswersCount / totalQuestions) * 100);

  const payload = {
    nama: activeStudentName,
    kelas: activeStudentClass,
    nilai: correctAnswersCount,
    total_soal: totalQuestions,
    persentase: scorePercent,
    timestamp: new Date().toLocaleString('id-ID')
  };

  try {
    statusMsg.style.color = '#a7f3d0';
    statusMsg.textContent = 'Mengirim data...';
    statusMsg.style.display = 'block';
    
    const response = await fetch('save.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    const result = await response.json();
    if (result.status === 'success') {
      statusMsg.style.color = '#10b981';
      statusMsg.textContent = 'Berhasil! Nilai Anda telah disimpan di server.';
      document.getElementById('save-score-btn').disabled = true;
    } else {
      throw new Error(result.message);
    }
  } catch (error) {
    statusMsg.style.color = '#ef4444';
    statusMsg.textContent = 'Gagal menyimpan. Pastikan server aktif (save.php).';
    console.error(error);
  }
}

// Add Event Listeners
document.getElementById('start-btn').addEventListener('click', startQuiz);
document.getElementById('next-btn').addEventListener('click', handleNextQuestion);
document.getElementById('review-btn').addEventListener('click', showReview);
document.getElementById('close-review-btn').addEventListener('click', closeReview);
document.getElementById('retry-btn').addEventListener('click', startQuiz);
document.getElementById('home-btn').addEventListener('click', resetQuiz);
document.getElementById('save-score-btn').addEventListener('click', handleSaveScore);

// Initialize on page load
window.addEventListener('DOMContentLoaded', initQuiz);
