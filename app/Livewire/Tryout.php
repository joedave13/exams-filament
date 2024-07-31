<?php

namespace App\Livewire;

use App\Models\Package;
use Livewire\Component;

class Tryout extends Component
{
    public $package;
    public $packageQuestions;
    public $currentQuestion;

    public function mount($id)
    {
        $this->package = Package::with(['packageQuestions.question.questionOptions'])->find($id);

        if ($this->package) {
            $this->packageQuestions = $this->package->packageQuestions;

            if ($this->packageQuestions->isNotEmpty()) {
                $this->currentQuestion = $this->packageQuestions->first();
            }
        }
    }

    public function render()
    {
        return view('livewire.tryout');
    }

    public function navigateQuestion($index)
    {
        $this->currentQuestion = $this->packageQuestions[$index - 1];
    }
}
