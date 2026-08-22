    <?php

    namespace App\Services\Reports\DutyReport;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class DutyReportService
    {
        public function store(array $data)
        {
            DB::beginTransaction();

            try {

                // TODO: Implement store logic

                DB::commit();
                return true;

            } catch (\Throwable $e) {
                DB::rollBack();

                Log::error('DutyReportService Store Failed', [
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }
        }

        public function update($model, array $data)
        {
            DB::beginTransaction();

            try {

                // TODO: Implement update logic

                DB::commit();
                return true;

            } catch (\Throwable $e) {
                DB::rollBack();

                Log::error('DutyReportService Update Failed', [
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }
        }

        public function delete($model)
        {
            DB::beginTransaction();

            try {

                // TODO: Implement delete logic

                DB::commit();
                return true;

            } catch (\Throwable $e) {
                DB::rollBack();

                Log::error('DutyReportService Delete Failed', [
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }
        }
    }