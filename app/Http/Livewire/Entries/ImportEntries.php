<?php

namespace App\Http\Livewire\Entries;

use App\Models\Todo;
use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportEntries extends Component
{
    use WithFileUploads;
    use InteractsWithModal;
    use InteractsWithToast;

    public $csvFile;

    public string $modalTitle = '';
    public bool $loading = false;
    public string $loadingMessage = '';

    public function import()
    {
        $this->modalTitle = 'Import Entries';

        $this->clearValidation();
        $this->openModal();
    }

    public function save()
    {
        try {
            $this->validate([
                'csvFile' => 'required|mimes:csv,txt',
            ]);

            $this->csvFile->storeAs('csv', $this->csvFile->getClientOriginalName());

            $rows = array_map('str_getcsv', file($this->csvFile->getRealPath()));

            // remove first header row
            array_shift($rows);

            $data = [];
            foreach ($rows as $row) {

                $exists = Todo::query()
                    ->where('dated', date('Y-m-d', strtotime($row[0])))
                    ->where('description', $row[1])
                    ->where('time_start', $row[2])
                    ->where('time_end', $row[3])
                    ->exists();

                if (!$exists) {
                    $data[] = [
                        'user_id' => auth()->id(),
                        'project_id' => '',
                        'todolist_id' => '',
                        'todo_id' => '',
                        'dated' => date('Y-m-d', strtotime($row[0])),
                        'description' => $row[1],
                        'time_start' => $row[2],
                        'time_end' => $row[3],
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($data)) {
                Todo::query()->insert($data);

                Storage::delete('csv/' . $this->csvFile->getClientOriginalName());
                $this->csvFile = '';

                $this->closeModal();

                $this->emit('refreshLivewireDatatable');
                $this->emit('event-entries-updated');

                $this->success('Entry Imported Successfully!');
            } else {
                $this->closeModal();
                $this->danger('No new entries to import!');
            }
        } catch (Exception $e) {
            $this->danger($e->getMessage());
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.entries.import-entries');
    }
}
