<?php

namespace App\Livewire;

use App\Models\Package;
use Livewire\Component;

class TryoutOnline extends Component
{
    public $package;
    public $packageQuestions;
    public $currentPackageQuestion;

    public function mount($id)
    {
        $this->package = Package::with(['packageQuestions.question.questionOptions'])->find($id);

        if ($this->package) {
            $this->packageQuestions = $this->package->packageQuestions;

            if ($this->packageQuestions->isNotEmpty()) {
                $this->currentPackageQuestion = $this->packageQuestions->first();
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
    }

    protected function calculateTimeLeft()
    {
        //
    }

    public function submitAnswer($questionId, $questionOptionId)
    {
        //
    }
}
