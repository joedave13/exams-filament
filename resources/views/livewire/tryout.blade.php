<div>
    <div class="container mt-4">
        <div class="row">
            {{-- Question Section --}}
            <div class="col-md-8">
                <div id="question-container">
                    <div class="card question-card">
                        <div class="card-body pt-0">
                            <div class="mb-3 countdown-timer text-success" id="countdown">
                                Time left : <span id="time">00:00:00</span>
                            </div>

                            <h5 class="card-title">Question No. 1</h5>

                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis,
                                magni?</p>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question" value="0">
                                <label class="form-check-label">Lorem</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question" value="1">
                                <label class="form-check-label">Ipsum</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question" value="2">
                                <label class="form-check-label">Dolor</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question" value="3">
                                <label class="form-check-label">Sit</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Question Section --}}

            {{-- Question Navigation --}}
            <div class="col-md-4">
                <div class="card question-navigation">
                    <div class="card-body">
                        <h5 class="card-title">Navigation</h5>

                        <div class="btn-group-flex" role="group">
                            <button type="button" class="btn btn-outline-primary">1</button>
                            <button type="button" class="btn btn-outline-primary">2</button>
                            <button type="button" class="btn btn-outline-primary">3</button>
                            <button type="button" class="btn btn-outline-primary">4</button>
                            <button type="button" class="btn btn-outline-primary">5</button>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary">
                                Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Question Navigation --}}
        </div>
    </div>

    <script>
        function startCountdown(duration, display) {
            let timer = duration,
                minutes, seconds;

            setInterval(() => {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = `${hours}:${minutes}:${seconds}`;

                if (--timer < 0) {
                    timer = 0;
                }
            }, 1000);
        }

        window.onload = function() {
            const duration = 5 * 60;
            const display = document.querySelector('#time');
            startCountdown(duration, display);
        }
    </script>
</div>
