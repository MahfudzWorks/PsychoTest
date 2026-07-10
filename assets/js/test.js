/* ==========================================
   DATA
========================================== */

let currentQuestion = 0;

// Ambil jawaban yang sudah pernah disimpan
let answers = { ...savedAnswers };

/* ==========================================
   ELEMENT
========================================== */

const questionBox = document.getElementById("questionBox");
const currentNumber = document.getElementById("currentNumber");

const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

const finishBtn = document.getElementById("finishBtn");

const answeredCount = document.getElementById("answeredCount");
const unansweredCount = document.getElementById("unansweredCount");

const numberButtons =
    document.querySelectorAll(".numberBtn");

/* ==========================================
   RENDER SOAL
========================================== */

function renderQuestion() {

    const q = questions[currentQuestion];

    currentNumber.innerText = currentQuestion + 1;

    questionBox.innerHTML = `

        <h2 class="text-2xl font-bold mb-6">

            ${currentQuestion + 1}. ${q.question}

        </h2>

        <div class="space-y-4">

            ${renderOption(q,"A",q.option_a)}

            ${renderOption(q,"B",q.option_b)}

            ${renderOption(q,"C",q.option_c)}

            ${renderOption(q,"D",q.option_d)}

            ${renderOption(q,"E",q.option_e)}

        </div>

    `;

    updateSidebar();

    updateStatistic();

    prevBtn.disabled = currentQuestion === 0;

    nextBtn.disabled =
        currentQuestion === totalQuestions - 1;

}

/* ==========================================
   OPTION
========================================== */

function renderOption(question, letter, text) {

    const checked =
        answers[question.id] === letter
            ? "checked"
            : "";

    return `

    <label
        class="flex items-center gap-4 border rounded-xl p-4 hover:bg-blue-50 cursor-pointer transition">

        <input

            type="radio"

            name="answer"

            value="${letter}"

            ${checked}

            onchange="saveAnswer(${question.id},'${letter}')"

            class="w-5 h-5">

        <span>

            <strong>${letter}.</strong>

            ${text}

        </span>

    </label>

    `;

}

/* ==========================================
   UPDATE SIDEBAR
========================================== */

function updateSidebar() {

    numberButtons.forEach((btn,index)=>{

        btn.classList.remove(

            "bg-blue-600",
            "text-white",
            "bg-green-600"

        );

        if(index===currentQuestion){

            btn.classList.add(

                "bg-blue-600",
                "text-white"

            );

        }else{

            const q = questions[index];

            if(answers[q.id]){

                btn.classList.add(

                    "bg-green-600",
                    "text-white"

                );

            }

        }

    });

}

/* ==========================================
   STATISTIK
========================================== */

function updateStatistic(){

    let totalAnswered = 0;

    questions.forEach((q)=>{

        if(answers[q.id]){

            totalAnswered++;

        }

    });

    answeredCount.innerText = totalAnswered;

    unansweredCount.innerText =
        totalQuestions-totalAnswered;

}

/* ==========================================
   NAVIGASI
========================================== */

nextBtn.addEventListener("click", function () {

    if (currentQuestion < totalQuestions - 1) {

        currentQuestion++;

        renderQuestion();

    }

});

prevBtn.addEventListener("click", function () {

    if (currentQuestion > 0) {

        currentQuestion--;

        renderQuestion();

    }

});

/* ==========================================
   KLIK NOMOR SOAL
========================================== */

numberButtons.forEach((button) => {

    button.addEventListener("click", function () {

        currentQuestion = parseInt(this.dataset.index);

        renderQuestion();

    });

});

/* ==========================================
   SIMPAN JAWABAN
========================================== */

function saveAnswer(questionId, answer) {

    // Simpan di browser
    answers[questionId] = answer;

    updateSidebar();

    updateStatistic();

    // Kirim ke server
    fetch("../api/save_answer.php", {

        method: "POST",

        headers: {

            "Content-Type": "application/x-www-form-urlencoded"

        },

        body:

            "user_test_id=" + encodeURIComponent(userTestId) +

            "&question_id=" + encodeURIComponent(questionId) +

            "&answer=" + encodeURIComponent(answer)

    })

    .then(response => response.text())

    .then(result => {

        console.log("Jawaban tersimpan :", result);

    })

    .catch(error => {

        console.error("Gagal menyimpan jawaban :", error);

    });

}

/* ==========================================
   MULAI
========================================== */

renderQuestion();

/* ==========================================
   TIMER
========================================== */

let timeLeft = remainingTime;

function formatTime(seconds) {

    let hour = Math.floor(seconds / 3600);

    let minute = Math.floor((seconds % 3600) / 60);

    let second = seconds % 60;

    return String(hour).padStart(2, "0") + ":" +
           String(minute).padStart(2, "0") + ":" +
           String(second).padStart(2, "0");

}

function updateTimer() {

    document.getElementById("timer").innerHTML = formatTime(timeLeft);

}

updateTimer();

/* ==========================================
   HITUNG MUNDUR
========================================== */

const countdown = setInterval(function () {

    if (timeLeft <= 0) {

        clearInterval(countdown);

        alert("Waktu pengerjaan telah habis.");

        finishTest();

        return;

    }

    timeLeft--;

    updateTimer();

}, 1000);

/* ==========================================
   SYNC TIMER KE DATABASE
   (setiap 30 detik)
========================================== */

setInterval(function () {

    fetch("../api/timer.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body:

            "user_test_id=" + encodeURIComponent(userTestId) +

            "&remaining_time=" + encodeURIComponent(timeLeft)

    });

}, 30000);

/* ==========================================
   SELESAI TES
========================================== */

finishBtn.addEventListener("click", function () {

    let answered = 0;

    questions.forEach(function (q) {

        if (answers[q.id]) {

            answered++;

        }

    });

    let confirmText =
        "Anda telah menjawab " +
        answered +
        " dari " +
        totalQuestions +
        " soal.\n\nYakin ingin menyelesaikan tes?";

    if (confirm(confirmText)) {

        finishTest();

    }

});

/* ==========================================
   FINISH TEST
========================================== */

function finishTest() {

    fetch("../api/finish_test.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body:

            "user_test_id=" + encodeURIComponent(userTestId)

    })

    .then(response => response.text())

    .then(result => {

        window.location =
            "finish.php?id=" + userTestId;

    })

    .catch(error => {

        console.log(error);

        alert("Terjadi kesalahan.");

    });

}

/* ==========================================
   BLOK REFRESH
========================================== */

window.addEventListener("beforeunload", function (e) {

    e.preventDefault();

    e.returnValue = "";

});