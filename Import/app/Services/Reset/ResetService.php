<?php

namespace App\Services\Reset;
use Illuminate\Support\Facades\DB;
class ResetService{


    private function disableForeignKeyChecks()
    {
        DB::statement('SET foreign_key_checks = 0;');
    }

    
    private function enableForeignKeyChecks()
    {
        DB::statement('SET foreign_key_checks = 1;');
    }

    
    public function resetDatabase()
    {
        $this->disableForeignKeyChecks();

        
        DB::table('crm2.leads')->truncate();
        DB::table('crm2.comments')->truncate();
        DB::table('crm2.mails')->truncate();
        DB::table('crm2.tasks')->truncate();
        DB::table('crm2.projects')->truncate();
        DB::table('crm2.absences')->truncate();
        DB::table('crm2.contacts')->truncate();
        DB::table('crm2.invoice_lines')->truncate();
        DB::table('crm2.appointments')->truncate();
        DB::table('crm2.payments')->truncate();
        DB::table('crm2.invoices')->truncate();
        DB::table('crm2.offers')->truncate();
        DB::table('crm2.clients')->truncate();
        DB::table('crm2.products')->truncate();


        // $adminUserIds = DB::table('crm2.role_user')
        //     ->join('crm2.roles', 'crm2.role_user.role_id', '=', 'crm2.roles.id')
        //     ->where('crm2.roles.name', '=', 'administrator')
        //     ->pluck('crm2.role_user.user_id')
        //     ->toArray();
            
        // dump($adminUserIds);
        
        // DB::table('crm2.users')
        //     ->whereNotIn('id', $adminUserIds)
        //     ->delete();

        
        // DB::table('crm2.role_user')
        //     ->whereNotIn('user_id', $adminUserIds)
        //     ->delete();

        
        // DB::table('crm2.department_user')
        //     ->whereNotIn('user_id', $adminUserIds)
        //     ->delete();

        // Suppression optionnelle de certaines tables
        // DB::table('crm2.products')->truncate();
        // DB::table('crm2.subscriptions')->truncate();
        // DB::table('crm2.activities')->truncate();

        $this->enableForeignKeyChecks();
    }
}