<?php

namespace App\Livewire;

use App\Models\Package;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TryoutOnline extends Component
{
    public $package;
    public $packageQuestions;
    public $currentPackageQuestion;
    public $tryout;
    public $timeLeft;

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

    public function submitAnswer($questionId, $questionOptionId)
    {
        $this->calculateTimeLeft();
    }
}
