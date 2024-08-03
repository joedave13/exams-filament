<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 bg-white shadow-md rounded-lg p-6">
        @if ($tryout->finished_at === null)
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold text-primary-600">Time Left</h2>
                <div class="text-gray-700 text-lg" id="time">00:00:00</div>
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-4">{{ $package->name }}</h2>

        <p class="text-gray-700">{!! $currentPackageQuestion->question->question !!}</p>

        <div class="mx-4 my-2">
            @foreach ($currentPackageQuestion->question->questionOptions as $item)
                @php
                    $answer = $tryoutAnswers->where('question_id', $currentPackageQuestion->question->id)->first();
                    $selected = $answer ? $answer->option_id == $item->id : false;
                @endphp

                <label class="flex items-center mb-2 gap-3">
                    <input type="radio" id="option_{{ $currentPackageQuestion->question->id }}_{{ $item->id }}"
                        wire:model="selectedAnswers.{{ $item->question_id }}"
                        wire:click="submitAnswer({{ $item->question_id }}, {{ $item->id }})"
                        value="{{ $item->id }}"
                        {{ $tryoutAnswers->isEmpty() || !$tryoutAnswers->contains('question_option_id', $item->id) ? '' : 'checked' }}
                        {{ $timeLeft <= 0 ? 'disabled' : '' }}>
                    {!! $item->option_text !!}
                </label>
            @endforeach
        </div>
    </div>

    <div class="md:col-span-1 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Navigation</h2>

        @foreach ($packageQuestions as $item)
            @php
                $isQuestionAnswered =
                    isset($selectedAnswers[$item->question->id]) && !is_null($selectedAnswers[$item->question->id]);
                $isQuestionActive = $currentPackageQuestion->question->id === $item->question->id;
            @endphp

            <x-filament::button wire:click="navigateQuestion({{ $item->id }})" class="mb-2"
                color="{{ $isQuestionActive ? 'warning' : ($isQuestionAnswered ? 'info' : 'gray') }}">
                {{ $loop->iteration }}
            </x-filament::button>
        @endforeach

        @if ($tryout->finished_at == null)
            <div class="mt-6">
                <x-filament::button class="w-full" color="success" wire:click="submitExam()"
                    wire:confirm="Are you sure you want to submit this exam?">
                    Submit Exam
                </x-filament::button>
            </div>
        @endif
    </div>

    @if ($tryout->finished_at != null)
        <div class="mt-6 p-4 mb-4 text-sm text-white rounded-lg bg-primary-500" role="alert">
            <span class="font-bold">Exam has been submitted successfully.</span> <a href="{{ url('tryouts') }}">Get
                Result</a>
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
