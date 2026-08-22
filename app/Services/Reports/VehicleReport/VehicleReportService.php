    <?php

    namespace App\Services\Reports\VehicleReport;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class VehicleReportService
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

                Log::error('VehicleReportService Store Failed', [
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

                Log::error('VehicleReportService Update Failed', [
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

                Log::error('VehicleReportService Delete Failed', [
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }
        }
    }