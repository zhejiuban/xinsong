<?php

namespace App\Console\Commands;

use App\Assessment;
use App\AssessmentRule;
use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:assessment
                            {--company= : The ID of company}
                            {--rule= : The ID of assessment rule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'auto check assessment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = Carbon::now()->subDay()->toDateString();
        //获取分部所有用户
        if($this->option('company')){
            $user = get_company_user($this->option('company'), 'id');
        }
        //获取某日需上传日志的所有用户
        $needAddDynamicTask = Task::needAddDynamic($start, null, isset($user) ? $user : null)
            ->doesntHaveDynamic($start)->get()->pluck('leader')->all();
        $rule = AssessmentRule::find($this->option('rule'));
        if ($needAddDynamicTask && $rule) {
            foreach ($needAddDynamicTask as $task) {
                Assessment::create([
                    'user_id' => $task,
                    'assessment_rule_id' => $rule->id,
                    'score' => $rule->score
                ]);
            }
        }
        $this->info('auto check assessment success ' . count($needAddDynamicTask));
    }
}
