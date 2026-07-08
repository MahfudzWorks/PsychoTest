let current = 1;

const questionBox = document.getElementById("questionBox");
const currentNumber = document.getElementById("currentNumber");
const numberBtns = document.querySelectorAll(".numberBtn");

async function loadQuestion(number) {

    const response = await fetch(
        `../api/get_question.php?test_id=${testId}&number=${number}`
    );

    const data = await response.json();

    if (!data.success) {
        return;
    }

    const q = data.question;

    currentNumber.innerHTML = number;

    questionBox.innerHTML = `
        <h2 class="text-xl font-semibold mb-8">
            ${q.question}
        </h2>

        <div class="space-y-4">

            <label class="block border rounded-lg p-4 cursor-pointer hover:bg-blue-50">
                <input type="radio" name="answer" value="A">
                <b>A.</b> ${q.option_a}
            </label>

            <label class="block border rounded-lg p-4 cursor-pointer hover:bg-blue-50">
                <input type="radio" name="answer" value="B">
                <b>B.</b> ${q.option_b}
            </label>

            <label class="block border rounded-lg p-4 cursor-pointer hover:bg-blue-50">
                <input type="radio" name="answer" value="C">
                <b>C.</b> ${q.option_c}
            </label>

            <label class="block border rounded-lg p-4 cursor-pointer hover:bg-blue-50">
                <input type="radio" name="answer" value="D">
                <b>D.</b> ${q.option_d}
            </label>

            <label class="block border rounded-lg p-4 cursor-pointer hover:bg-blue-50">
                <input type="radio" name="answer" value="E">
                <b>E.</b> ${q.option_e}
            </label>

        </div>
    `;

    numberBtns.forEach(btn => {
        btn.classList.remove("bg-blue-600", "text-white");
    });

    numberBtns[number - 1].classList.add("bg-blue-600", "text-white");

}

loadQuestion(current);

document.getElementById("nextBtn").onclick = function () {

    if (current < totalQuestions) {
        current++;
        loadQuestion(current);
    }

};

document.getElementById("prevBtn").onclick = function () {

    if (current > 1) {
        current--;
        loadQuestion(current);
    }

};

numberBtns.forEach(btn => {

    btn.onclick = function(){

        current = parseInt(this.innerText);

        loadQuestion(current);

    };

});

// =======================
// TIMER
// =======================

let totalSeconds = testDuration * 60;

const timer = document.getElementById("timer");

const interval = setInterval(function () {

    let h = Math.floor(totalSeconds / 3600);

    let m = Math.floor((totalSeconds % 3600) / 60);

    let s = totalSeconds % 60;

    timer.innerHTML =
        String(h).padStart(2, "0") + ":" +
        String(m).padStart(2, "0") + ":" +
        String(s).padStart(2, "0");

    if (totalSeconds <= 300) {

        timer.classList.remove("text-red-600");

        timer.classList.add("text-red-700","animate-pulse");

    }

    if (totalSeconds <= 0) {

        clearInterval(interval);

        alert("Waktu habis.");

        window.location.href="finish.php";

    }

    totalSeconds--;

},1000);

const finishBtn = document.getElementById("finishBtn");

finishBtn.addEventListener("click", async function () {

    if (!confirm("Yakin ingin menyelesaikan tes?")) {
        return;
    }

    await fetch("../api/finish_test.php", {
        method: "POST"
    });

    window.location.href = "finish.php";
});