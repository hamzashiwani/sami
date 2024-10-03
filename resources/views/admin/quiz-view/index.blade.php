@extends('admin.layouts.app')

@section('content')
    <style>
        #timer {
            font-size: 2.5em;
            color: #ff5733;
            font-weight: bold;
        }
        .question {
            margin: 20px 0;
            font-size: 1.8em;
            font-weight: 600;
            color: #333;
        }
        .answers {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .answers li {
            position: relative;
            margin: 10px 0;
            cursor: pointer;
            background: #e7f3fe;
            padding: 15px;
            border-radius: 8px;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .answers li:hover {
            background: #d1e8ff;
            transform: scale(1.02);
        }
        .correct {
            background-color: #28a745 !important; /* Green background for the correct answer */
            color: white;
        }
        .wrong {
            background-color: #dc3545; /* Red background for wrong answers */
            color: white;
        }
        .answer-total {
            margin-top: 5px;
            font-size: 0.9em;
            color: #666;
        }
        .button-container {
            margin: 30px 0;
        }
        button {
            padding: 12px 25px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1.1em;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        #chart {
            margin: 20px auto;
            width: 90%;
            max-width: 600px;
            display: none; /* Initially hidden */
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body text-center">
                        <div id="timer">30</div>
                        <div class="question">What is the capital of France?</div>
                        <ul class="answers">
                            <li onclick="selectAnswer('A', false)">A. Berlin</li>
                            <li onclick="selectAnswer('B', false)">B. Madrid</li>
                            <li onclick="selectAnswer('C', true)">C. Paris</li>
                            <li onclick="selectAnswer('D', false)">D. Rome</li>
                        </ul>
                        <div class="button-container">
                            <button id="nextButton" onclick="nextQuestion()" disabled>Next</button>
                            <button id="finishButton" onclick="finishQuiz()">Finish</button>
                        </div>
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let timeLeft = 30;
        const timerElement = document.getElementById('timer');
        const chartElement = document.getElementById('chart');
        let countdown;
        const correctAnswer = 'C'; // The correct answer

        function startTimer() {
            countdown = setInterval(() => {
                timeLeft--;
                timerElement.innerText = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    showResults(); // Call the results function when time is up
                }
            }, 1000);
        }

        function selectAnswer(answer, isCorrect) {
            // Disable further selections
            const answers = document.querySelectorAll('.answers li');
            answers.forEach(item => {
                item.style.pointerEvents = 'none'; // Disable further selections
            });
            document.getElementById('nextButton').disabled = false; // Enable the next button
        }

        function nextQuestion() {
            timeLeft = 30;
            timerElement.innerText = timeLeft;
            startTimer();
            // Reset answers display
            const answers = document.querySelectorAll('.answers li');
            answers.forEach(item => {
                item.classList.remove('correct', 'wrong');
                item.querySelector('.answer-total')?.remove(); // Remove totals
            });
            chartElement.style.display = 'none'; // Hide the chart
        }

        function finishQuiz() {
            clearInterval(countdown);
            showResults();
        }

        function showResults() {
            const answers = document.querySelectorAll('.answers li');

            // Highlight the correct answer
            answers.forEach(item => {
                if (item.textContent.includes('C. Paris')) {
                    item.classList.add('correct'); // Highlight correct answer
                } else {
                    item.classList.add('wrong'); // Highlight wrong answers
                }
                const total = document.createElement('div');
                total.classList.add('answer-total');
                total.innerText = 'Total Answers: ' + (Array.from(answers).indexOf(item) + 1); // Example logic for total answers
                item.appendChild(total);
            });
        }

        // Start the timer on page load
        startTimer();
    </script>
@endsection
