<div>
    <style>
        .active-question {
            border: 3px solid cyan;
        }

        .no-hover:hover {
            background-color: transparent !important;
            color: inherit !important;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            <h4>{{ $package->name }}</h4>

            {{-- Question Section --}}
            <div class="col-md-8">
                <div id="question-container">
                    <div class="card question-card">
                        <div class="card-body pt-0">
                            @if ($tryout->finished_at === null)
                                <div class="mb-3 countdown-timer text-success" id="countdown">
                                    Time left : <span id="time">00:00:00</span>
                                </div>
                            @endif

                            <p class="card-text">{!! $currentPackageQuestion->question->question !!}</p>

                            @foreach ($currentPackageQuestion->question->questionOptions as $item)
                                <div class="form-check">
                                    <input wire:model="selectedAnswers.{{ $item->question_id }}"
                                        wire:click="submitAnswer({{ $item->question_id }}, {{ $item->id }})"
                                        class="form-check-input" type="radio" value="{{ $item->id }}"
                                        {{ $tryoutAnswers->isEmpty() || !$tryoutAnswers->contains('question_option_id', $item->id) ? '' : 'checked' }}
                                        {{ $timeLeft <= 0 ? 'disabled' : '' }}>
                                    <label class="form-check-label">{!! $item->option_text !!}</label>
                                </div>
                            @endforeach
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
                            @foreach ($packageQuestions as $item)
                                @php
                                    $isQuestionAnswered =
                                        isset($selectedAnswers[$item->question->id]) &&
                                        !is_null($selectedAnswers[$item->question->id]);
                                    $isQuestionActive = $currentPackageQuestion->question->id === $item->question->id;
                                @endphp

                                <button type="button" wire:click="navigateQuestion({{ $item->id }})"
                                    class="btn {{ $isQuestionAnswered ? 'btn-primary' : 'btn-outline-primary no-hover' }} {{ $isQuestionActive ? 'active-question' : '' }}"
                                    {{ $timeLeft <= 0 ? 'disabled' : '' }}>{{ $loop->iteration }}</button>
                            @endforeach
                        </div>

                        <div class="d-grid mt-3">
                            <button type="button" wire:click="submitExam()"
                                wire:confirm="Are you sure you want to submit this exam?" class="btn btn-primary">
                                Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Question Navigation --}}
        </div>

        @if (Session::has('message'))
            <div class="alert alert-info text-center">
                {{ Session::get('message') }} <a href="{{ url('tryouts') }}">Get Result</a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = "{{ $timeLeft }}";
            startCountdown(timeLeft, document.querySelector('#time'));
        });

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
    </script>
</div>
