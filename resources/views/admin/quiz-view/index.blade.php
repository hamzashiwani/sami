@extends('admin.layouts.app')

@section('content')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <style>
        /* body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        } */
        #timer {
            font-size: 2em;
            color: red;
        }
        .question {
            margin: 20px 0;
            font-size: 1.5em;
        }
        .answers {
            list-style: none;
            padding: 0;
        }
        .answers li {
            margin: 10px 0;
            cursor: pointer;
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .answers li:hover {
            background: #ddd;
        }
        .button-container {
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        #chart {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            display: none; /* Initially hidden */
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                            <div id="timer">30</div>
                                <div class="question">What is the capital of France?</div>
                                <ul class="answers">
                                    <li onclick="selectAnswer('A')">A. Berlin</li>
                                    <li onclick="selectAnswer('B')">B. Madrid</li>
                                    <li onclick="selectAnswer('C')">C. Paris</li>
                                    <li onclick="selectAnswer('D')">D. Rome</li>
                                </ul>
                                <div class="button-container">
                                    <button id="nextButton" onclick="nextQuestion()">Next</button>
                                    <button id="finishButton" onclick="finishQuiz()">Finish</button>
                                </div>
                                <canvas id="chart"></canvas>
                            </div>
                        </div>
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

        function startTimer() {
            countdown = setInterval(() => {
                timeLeft--;
                timerElement.innerText = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    alert('Time is up!');
                    showChart();
                }
            }, 1000);
        }

        function selectAnswer(answer) {
            alert(`You selected: ${answer}`);
            // Disable further selections
            const answers = document.querySelectorAll('.answers li');
            answers.forEach(item => item.style.pointerEvents = 'none');
            document.getElementById('nextButton').disabled = false;
        }

        function nextQuestion() {
            // Logic to go to the next question (for now just resetting the timer)
            timeLeft = 30;
            timerElement.innerText = timeLeft;
            startTimer();
            // Hide the chart if it was shown
            chartElement.style.display = 'none';
        }

        function finishQuiz() {
            clearInterval(countdown);
            showChart();
        }

        function showChart() {
            const ctx = chartElement.getContext('2d');
            chartElement.style.display = 'block'; // Show the chart
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['A', 'B', 'C', 'D'],
                    datasets: [{
                        label: 'Answers',
                        data: [1, 0, 1, 0], // Example data
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Start the timer on page load
        startTimer();
    </script>
@endsection
