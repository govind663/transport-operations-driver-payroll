    <?php

    namespace App\Services\Reports\DriverReport;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class DriverReportService
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

                Log::error('DriverReportService Store Failed', [
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

                Log::error('DriverReportService Update Failed', [
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

                Log::error('DriverReportService Delete Failed', [
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }
        }
    }