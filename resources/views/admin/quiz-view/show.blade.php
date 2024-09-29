@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
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
        #chart {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
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

        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                alert('Time is up!');
                // Optionally: disable answers here
            }
        }, 1000);

        function selectAnswer(answer) {
            alert(`You selected: ${answer}`);
            // Handle answer selection and show the chart
            showChart();
        }

        function showChart() {
            const ctx = document.getElementById('chart').getContext('2d');
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
    </script>
@endsection
