<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            "فوق مكتبة الطفل" => [
                "C201" => "أول غرفة يمين",
                "C203" => "ثاني غرفة يمين",
                "C205" => "أول غرفة شمال",
                "C207" => "ثالث غرفة يمين",
                "C208" => "في المواجهة",
                "11" => "الجديدة",
                "12" => "الجديدة",
            ],
            "فوق الكانتين" => [
                "B102" => "الدور الأول",
                "B202" => "الدور الثاني ثاني غرفة",
                "B203" => "الدور الثاني ثالث غرفة",
                "B204" => "الدور الثاني في المواجهة",
            ],
            "الدور الرابع" => [
                "4" => null,
                "5" => null,
                "8" => null,
                "9" => null,
            ],
            "ذوي الأحتياجات" => [
                "C108" => null,
            ],
            "الكنيسة" => [
                "1" => null,
            ],
            "القاعة الكبيرة" => [
                "2" => null,
            ],
        ];

        foreach ($locations as $locationName => $rooms) {
            $location = Location::query()->create(["name" => $locationName]);

            foreach ($rooms as $name => $description) {
                $location->rooms()->save(
                    new Room([
                        "name" => $name,
                        "description" => $description,
                    ]),
                );
            }
        }
    }
}
