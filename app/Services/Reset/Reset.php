<?php
namespace App\Services\Reset;

use Illuminate\Support\Facades\DB;

class Reset
{
    public function resetTables()
    {
        try {
            DB::beginTransaction();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $tables = [
                'mails', 'comments', 'absences', 'leads', 'projects', 'tasks',
                'contacts', 'industries', 'appointments', 'clients', 'offers',
                'invoices', 'invoice_lines', 'payments'
            ];

            foreach ($tables as $table) {
                DB::table($table)->truncate();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            return ['success' => true, 'message' => 'Les tables ont été réinitialisées avec succès.'];
        }
        catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()];
        }
    }
}
