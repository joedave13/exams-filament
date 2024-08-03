<?php

namespace App\Livewire;

use App\Models\Package;
use App\Models\QuestionOption;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TryoutOnline extends Component
{
    public $package;
    public $packageQuestions;
    public $currentPackageQuestion;
    public $tryout;
    public $timeLeft;
    public $tryoutAnswers;
    public $selectedAnswers = [];

    public function mount($id)
    {
        $this->package = Package::with(['packageQuestions.question.questionOptions'])->find($id);

        if ($this->package) {
            $this->packageQuestions = $this->package->packageQuestions;

            if ($this->packageQuestions->isNotEmpty()) {
                $this->currentPackageQuestion = $this->packageQuestions->first();

                $this->tryout = Tryout::query()->where('user_id', auth()->id())
                    ->where('package_id', $this->package->id)
                    ->whereNull('finished_at')
                    ->first();

                if (!$this->tryout) {
                    DB::transaction(function () {
                        $this->tryout = Tryout::query()->create([
                            'user_id' => auth()->id(),
                            'package_id' => $this->package->id,
                            'duration' => $this->package->duration * 60,
                            'started_at' => Carbon::now('Asia/Jakarta')
                        ]);

                        foreach ($this->packageQuestions as $item) {
                            TryoutAnswer::query()->create([
                                'tryout_id' => $this->tryout->id,
                                'question_id' => $item->question->id
                            ]);
                        }
                    });
                }

                $this->getTryoutAnswers();

                foreach ($this->tryoutAnswers as $answer) {
                    $this->selectedAnswers[$answer->question_id] = $answer->question_option_id;
                }

                $this->calculateTimeLeft();
            }
        }
    }

    public function render()
    {
        return view('livewire.tryout-online');
    }

    public function navigateQuestion($packageQuestionId)
    {
        $this->currentPackageQuestion = $this->packageQuestions->where('id', $packageQuestionId)->first();
        $this->calculateTimeLeft();
        $this->getTryoutAnswers();
    }

    public function previousQuestion($packageQuestionId)
    {
        $previousPackageQuestion = $this->packageQuestions
            ->where('id', '<', $packageQuestionId)
            ->sortByDesc('id')
            ->first();

        $this->currentPackageQuestion = $previousPackageQuestion ? $previousPackageQuestion : $this->packageQuestions->where('id', $packageQuestionId)->first();

        $this->calculateTimeLeft();
        $this->getTryoutAnswers();
    }

    public function nextQuestion($packageQuestionId)
    {
        $nextPackageQuestion = $this->packageQuestions
            ->where('id', '>', $packageQuestionId)
            ->sortBy('id')
            ->first();

        $this->currentPackageQuestion = $nextPackageQuestion ? $nextPackageQuestion : $this->packageQuestions->where('id', $packageQuestionId)->first();

        $this->calculateTimeLeft();
        $this->getTryoutAnswers();
    }

    protected function calculateTimeLeft()
    {
        if ($this->tryout->finished_at) {
            $this->timeLeft = 0;
            return;
        }

        $now = Carbon::now('Asia/Jakarta')->timestamp;
        $startedAt = Carbon::parse($this->tryout->started_at, 'Asia/Jakarta')->timestamp;
        $currentTimeLeft = $now - $startedAt;

        if ($currentTimeLeft < 0) {
            $this->timeLeft = 0;
        } else {
            $this->timeLeft = max(0, $this->tryout->duration - $currentTimeLeft);
        }

        $this->timeLeft = $currentTimeLeft < 0 ? 0 : max(0, $this->tryout->duration - $currentTimeLeft);
    }

    protected function getTryoutAnswers()
    {
        $this->tryoutAnswers = TryoutAnswer::query()->where('tryout_id', $this->tryout->id)->get();
    }

    public function submitAnswer($questionId, $questionOptionId)
    {
        $questionOption = QuestionOption::query()->find($questionOptionId);
        $score = $questionOption->score ?? 0;

        $tryoutAnswer = TryoutAnswer::query()->where('tryout_id', $this->tryout->id)
            ->where('question_id', $questionId)
            ->first();

        if ($tryoutAnswer) {
            $tryoutAnswer->update([
                'question_option_id' => $questionOption->id,
                'score' => $score
            ]);
        }

        $this->calculateTimeLeft();
        $this->getTryoutAnswers();
    }

    public function submitExam()
    {
        $this->tryout->update([
            'finished_at' => Carbon::now('Asia/Jakarta')
        ]);

        $this->calculateTimeLeft();

        Notification::make()
            ->success()
            ->title('Success!')
            ->body('Exam has been submitted successfully.')
            ->send();
    }
}
