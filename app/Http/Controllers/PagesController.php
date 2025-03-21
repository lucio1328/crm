<?php
namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class PagesController extends Controller
{
    /**
     * Dashobard view
     * @return mixed
     */
    public function dashboard()
    {
        $startDate = today()->subDays(14);
        $endDate = today()->endOfDay();

        $tasks = Task::whereBetween('created_at', [$startDate, $endDate])->get();
        $leads = Lead::whereBetween('created_at', [$startDate, $endDate])->get();

        $datasheet = [];
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');
            $datasheet[$dateKey] = ["monthly" => ["tasks" => 0, "leads" => 0]];
        }

        foreach ($tasks as $task) {
            $dateKey = $task->created_at->format('Y-m-d');
            if (isset($datasheet[$dateKey])) {
                $datasheet[$dateKey]["monthly"]["tasks"]++;
            }
        }

        foreach ($leads as $lead) {
            $dateKey = $lead->created_at->format('Y-m-d');
            if (isset($datasheet[$dateKey])) {
                $datasheet[$dateKey]["monthly"]["leads"]++;
            }
        }
        if (!auth()->user()->can('absence-view')) {
            $absences = [];
        } else {
            $absences = Absence::with('user')->groupBy('user_id')->where('start_at', '>=', today())->orWhere('end_at', '>', today())->get();
        }

        return view('pages.dashboard')
            ->withUsers(User::with(['department'])->get())
            ->withDatasheet($datasheet)
            ->withTotalTasks(Task::count())
            ->withTotalLeads(Lead::count())
            ->withTotalProjects(Project::count())
            ->withTotalClients(Client::count())
            ->withSettings(Setting::first())
            ->withAbsencesToday($absences);
    }
}
