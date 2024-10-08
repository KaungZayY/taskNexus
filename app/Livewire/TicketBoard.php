<?php

namespace App\Livewire;

use App\Helpers\PermissionHelper;
use App\Models\Status;
use App\Models\Teammate;
use App\Models\Ticket;
use App\Models\TicketTracker;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Laravel\Jetstream\InteractsWithBanner;

class TicketBoard extends Component
{
    use InteractsWithBanner;

    public $project;
    public $sprint;
    public $pinnedStatuses = [];
    public $editStatusId = null;
    public $editValues = [];
    public $timeTaken = [];

    public function mount($project, $sprint)
    {
        $this->project = $project;
        $this->sprint = $sprint;
    }

    public function render()
    {
        $statuses = $this->project->statuses()->orderBy('position')->get();
        foreach ($statuses as $status) 
        {
            $this->editValues[$status->id] = $status->status;
        }
        $tickets = $this->sprint->tickets()->with('teammates.user')->orderBy('position')->get();
        return view('livewire.ticket-board',compact('statuses','tickets'));
    }

    public function updateStatusOrder($groupedStatuses)
    {
        foreach ($groupedStatuses as $statusOrder) {
            $status = Status::find($statusOrder['value']);
            $status->update(['position' => $statusOrder['order']]);
        }
    }

    public function updateTicketOrder($groupedTickets)
    {
        foreach ($groupedTickets as $group) {
            $statusId = $group['value'];
            foreach ($group['items'] as $ticketOrder) {
                $ticket = Ticket::find($ticketOrder['value']);
                if ($ticket->status_id != $statusId) {
                    $this->trackTicketStatusChange($ticket, $ticket->status_id, $statusId);
                }
                $ticket->update([
                    'status_id' => $statusId,
                    'position' => $ticketOrder['order'],
                ]);
            }
        }
    }

    protected function trackTicketStatusChange($ticket, $prevStatusId, $newStatusId){
        TicketTracker::create([
            'ticket_id' => $ticket->id,
            'prev_status_id' => $prevStatusId,
            'new_status_id' => $newStatusId,
            'updated_by' => auth()->id()
        ]);
    }

    protected $rules = [
        'timeTaken.*' => 'required|integer|min:1',
        'editValues.*' => 'required|string|max:255',
    ];

    protected function messages()
    {
        return [
            'timeTaken.*.required' => 'Fill the time taken field.',
            'timeTaken.*.integer' => 'The time taken must be a valid number.',
            'timeTaken.*.min' => 'The time taken must be at least 1 minutes.',

            'editValues.*.required' => 'Please provide a column name.',
            'editValues.*.string' => 'The column name should be a valid text.',
            'editValues.*.max' => 'The column name is too long.',
        ];
    }

    public function addTimeTaken(Ticket $ticket)
    {
        if($ticket->status->status_type != 1 && $ticket->status->status_type !=2){
            $this->validate([
                'timeTaken.' . $ticket->id => 'required|integer|min:1|max:1000'
            ]);
            $ticketTracker = $ticket->ticket_trackers()->where('new_status_id',$ticket->status->id)->first();
            $totalTimeTaken = $this->timeTaken[$ticket->id] + $ticketTracker->time_taken;
            try {
                $ticketTracker->update([
                    'time_taken' => $totalTimeTaken
                ]);
                $this->banner('Ticket duration updated successfully.');
            } 
            catch (\Exception $e) {
                Log::error($e->getMessage());
                $this->dangerBanner('Failed to update the ticket duration.');
            }
        }
        $this->timeTaken[$ticket->id] = 0;   
    }

    public function togglePinStatus($statusId)
    {
        if (in_array($statusId, $this->pinnedStatuses)) {
            $this->pinnedStatuses = array_values(array_diff($this->pinnedStatuses, [$statusId]));
        } 
        else {
            $this->pinnedStatuses[] = $statusId;
        }
    }

    public function destroy(Status $status,PermissionHelper $pHelper)
    {
        $pHelper->authorizeUser($this->project,'Statuses','Delete');
        if ($status->exists && !$status->tickets()->exists()) {
            $status->delete();
            $this->banner('Column Removed.');
        }
        else{
            $this->dangerBanner('Remove all tickets from this column across all sprints first.');
        }
    }

    public function edit(Status $status,PermissionHelper $pHelper)
    {
        $pHelper->authorizeUser($this->project,'Statuses','Update');
        $this->editStatusId = $status->id;
    }

    public function update(Status $status,PermissionHelper $pHelper)
    {
        $pHelper->authorizeUser($this->project,'Tickets','Update');
        $this->validate([
            'editValues.' . $status->id => 'required|string|max:255',
        ]);
        try {
            $status->update([
                'status' => $this->editValues[$status->id],
            ]);
            $this->banner('Column Updated.');
        } 
        catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dangerBanner('Action Failed.');
        }
        $this->editStatusId = null;
    }

    public function removeAssignee(Ticket $ticket, Teammate $teammate,PermissionHelper $pHelper)
    {
        $pHelper->authorizeUser($this->project,'Tickets','RemoveTeammate');
        $ticket->teammates()->detach($teammate);
        try {

            $ticket->teammates()->detach($teammate);
            $this->banner('Assigned user removed.');

        } 
        catch (\Exception $e) {

            Log::error($e->getMessage());
            $this->banner('Action Failed.');
        }
    }
}
