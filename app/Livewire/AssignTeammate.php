<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\Teammate;
use Livewire\Component;

class AssignTeammate extends Component
{

    public $ticket;
    public $project;
    public $team_id;
    public $teammate_id;
    public $teammates = [];

    protected $rules = [
        'team_id' => 'required|exists:teams,id',
        'teammate_id' => 'required|exists:teammates,id'
    ];

    protected $messages = [
        'team_id.required' => 'The Team cannot be empty.',
        'team_id.exists' => 'Select a valid Team.',
        'teammate_id.required' => 'Select Assignee.',
        'teammate_id.exists' => 'Select a valid Assignee.'
    ];

    public function mount($ticket, $project)
    {
        $this->ticket = $ticket;
        $this->project = $project;
    }

    public function render()
    {
        $teams = $this->project->teams;
        return view('livewire.assign-teammate',compact('teams'));
    }
    

    public function updatedTeamId(Team $team)
    {
        return $this->teammates = $team->teammates;
    }

    public function submit()
    {
        $this->validate();
        $teammate = Teammate::findOrFail($this->teammate_id);
        try {
            $this->ticket->teammates()->save($teammate,['assigned_by'=>auth()->id()]);

            session()->flash('flash.banner', 'User Assigned successfully.');
            session()->flash('flash.bannerStyle', 'success');

        } catch (\Exception $e) {

            session()->flash('flash.banner', 'Cannot Assign User to the Ticket.');
            session()->flash('flash.bannerStyle', 'danger');
            
        }

        return redirect()->route('tickets', ['project' => $this->project,'sprint' => $this->ticket->sprint_id]);
    }
}