<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Poll;

class CreatePoll extends Component
{
    public $title;
    public $options = ['First'];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'options' => 'required|array|min:1|max:10',
        'options.*' => 'required|min:1|max:255'
    ];

    protected $messages = [
        'options.*' => 'The option can\'t be empty.'
    ];


    public function render()
    {
        return view('livewire.create-poll');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function addOption()
    {
        $this->options[] = '';
    }
    
    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }
    public function createPoll()
    {
        $this->validate();

        // Method 1
        // $poll = Poll::create([
        //     'title' => $this->title
        // ]);

        // foreach ($this->options as $optionName) {
        //     $poll->options()->create(['name' => $optionName]);
        // }

        // Method 2
        $poll = Poll::create([
            'title' => $this->title
        ])->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
        );
        $this->reset(['title', 'options']);
        $this->emit('pollCreated');
    }

}