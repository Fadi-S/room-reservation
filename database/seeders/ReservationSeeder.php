<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "approved_at" => now(),
            "approved_by_id" => 1,
            "is_repeating" => true,
            "user_id" => 1,
        ];

        $rooms = Room::pluck("id", "name");
        $services = Service::pluck("id", "name");

        $daysOfWeek = [
            "sun" => 0,
            "mon" => 1,
            "tue" => 2,
            "wed" => 3,
            "thu" => 4,
            "fri" => 5,
            "sat" => 6,
        ];

        $reservations = [
            /** Saturday **/
            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["B102"],
                "description" => "اعدادى بنات",
                "start" => "17:00",
                "end" => "18:30",
                "day_of_week" => $daysOfWeek["sat"],
            ],

            [
                "service_id" => $services["إعداد خدمة بنات"],
                "room_id" => $rooms["4"],
                "description" => "ابونا إسحق",
                "start" => "17:30",
                "end" => "19:00",
                "day_of_week" => $daysOfWeek["sat"],
            ],

            /** Sunday **/

            [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms["2"],
                "description" => "اجتماع خدمة",
                "start" => "18:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms["2"],
                "description" => "اجتماع خدمة",
                "start" => "18:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["الكشافة بنين"],
                "room_id" => $rooms["C201"],
                "description" => "اجتماع خدمة",
                "start" => "20:00",
                "end" => "22:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["خدمة راكوتى"],
                "room_id" => $rooms["C203"],
                "description" => "اجتماع خدمة",
                "start" => "19:00",
                "end" => "22:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["شباب"],
                "room_id" => $rooms["C208"],
                "description" => "اجتماع خدمة (تحضير)",
                "start" => "19:00",
                "end" => "21:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["قرى أستاذ يسري"],
                "room_id" => $rooms["B202"],
                "description" => "اجتماع خدمة",
                "start" => "18:30",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["الأبن الشاطر"],
                "room_id" => $rooms["C205"],
                "description" => "اجتماع خدمة",
                "start" => "21:00",
                "end" => "23:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["ملايكة"],
                "room_id" => $rooms["4"],
                "description" => "مدارس الأحد",
                "start" => "14:30",
                "end" => "16:00",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["يسوع بيحبك"],
                "room_id" => $rooms["4"],
                "description" => "اجتماع خدمة",
                "start" => "17:30",
                "end" => "19:30",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["إعداد خدمة بنات"],
                "room_id" => $rooms["5"],
                "description" => "",
                "start" => "18:30",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            [
                "service_id" => $services["إعداد خدمة بنات"],
                "room_id" => $rooms["4"],
                "description" => "",
                "start" => "19:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["sun"],
            ],

            /** Monday **/

            [
                "service_id" => $services["قرية (ابونا بيمن)"],
                "room_id" => $rooms["B102"],
                "description" => "اجتماع تحضير",
                "start" => "17:00",
                "end" => "18:30",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            [
                "service_id" => $services["التصوير"],
                "room_id" => $rooms["B102"],
                "description" => "اجتماع خدمة",
                "start" => "18:30",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            [
                "service_id" => $services["حضانة"],
                "room_id" => $rooms["11"],
                "description" => "",
                "start" => "09:00",
                "end" => "15:00",
                "day_of_week" => $daysOfWeek["mon"],
            ],
            [
                "service_id" => $services["حضانة"],
                "room_id" => $rooms["12"],
                "description" => "",
                "start" => "09:00",
                "end" => "15:00",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            [
                "service_id" => $services["ذوى الاحتياجات الخاصة"],
                "room_id" => $rooms["2"],
                "description" => "خدمة",
                "start" => "16:30",
                "end" => "18:30",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            [
                "service_id" => $services["شباب"],
                "room_id" => $rooms["2"],
                "description" => "دراسة انجيل",
                "start" => "19:00",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            [
                "service_id" => $services["ساكبات الطيب"],
                "room_id" => $rooms["1"],
                "description" => "اجتماع",
                "start" => "18:30",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["mon"],
            ],

            /** Tuesday **/

            [
                "service_id" => $services["قرية (ابونا بيمن)"],
                "room_id" => $rooms["C201"],
                "description" => "اجتماع خدمة",
                "start" => "18:30",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["اعدادى بنات"],
                "room_id" => $rooms["C201"],
                "description" => "قبطي",
                "start" => "16:00",
                "end" => "18:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["الكشافة بنات"],
                "room_id" => $rooms["C203"],
                "description" => "اجتماع",
                "start" => "17:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["العائلة المقدسة"],
                "room_id" => $rooms["C207"],
                "description" => "اجتماع خدمة",
                "start" => "20:00",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["اعدادى بنات"],
                "room_id" => $rooms["B204"],
                "description" => "أبونا اسحق",
                "start" => "20:30",
                "end" => "22:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["شباب"],
                "room_id" => $rooms["11"],
                "description" => "اجتماع تحضير",
                "start" => "18:30",
                "end" => "21:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["عام"],
                "room_id" => $rooms["1"],
                "description" => "بانوراما الكتاب المقدس",
                "start" => "18:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            [
                "service_id" => $services["عام"],
                "room_id" => $rooms["2"],
                "description" => "دراسة انجيل م. مجدى انيس",
                "start" => "20:00",
                "end" => "22:00",
                "day_of_week" => $daysOfWeek["tue"],
            ],

            /** Wednesday **/

            [
                "service_id" => $services["إعداد خدمة بنين"],
                "room_id" => $rooms["B204"],
                "description" => "الأسرة الإضافية",
                "start" => "20:00",
                "end" => "22:00",
                "day_of_week" => $daysOfWeek["wed"],
            ],

            [
                "service_id" => $services["عام"],
                "room_id" => $rooms["2"],
                "description" => "كورس الدفاعيات للشباب ابونا داود",
                "start" => "16:00",
                "end" => "18:00",
                "day_of_week" => $daysOfWeek["wed"],
            ],

            /** Thursday **/

            [
                "service_id" => $services["حضانة"],
                "room_id" => $rooms["11"],
                "description" => "",
                "start" => "09:00",
                "end" => "13:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms["B202"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms["B203"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms["C201"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["اعدادى بنين"],
                "room_id" => $rooms["C203"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["اعدادى بنين"],
                "room_id" => $rooms["C207"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["اعدادى بنين"],
                "room_id" => $rooms["C208"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ثانوي بنين"],
                "room_id" => $rooms["B102"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ثانوي بنين"],
                "room_id" => $rooms["12"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms["B204"],
                "description" => "اسرة الانجيل",
                "start" => "17:30",
                "end" => "19:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms["4"],
                "description" => "اسرة الانجيل",
                "start" => "17:30",
                "end" => "19:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms["5"],
                "description" => "اسرة الانجيل",
                "start" => "17:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms["8"],
                "description" => "اسرة الانجيل",
                "start" => "19:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["اعدادى بنات"],
                "room_id" => $rooms["2"],
                "description" => "اسرة الانجيل",
                "start" => "18:00",
                "end" => "19:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["العائلة المقدسة"],
                "room_id" => $rooms["2"],
                "description" => "اجتماع الأسرة",
                "start" => "19:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["شباب"],
                "room_id" => $rooms["1"],
                "description" => "اجتماع",
                "start" => "19:00",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["حديثي الزواج"],
                "room_id" => $rooms["11"],
                "description" => "اجتماع خدمة",
                "start" => "19:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["C201"],
                "description" => "الحان ابتدائى بنين",
                "start" => "18:00",
                "end" => "19:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["C203"],
                "description" => "الحان ابتدائى بنين",
                "start" => "18:00",
                "end" => "19:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["C205"],
                "description" => "الحان ابتدائى بنين",
                "start" => "18:00",
                "end" => "19:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["C207"],
                "description" => "الحان بنات",
                "start" => "17:30",
                "end" => "18:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["C201"],
                "description" => "الحان بنات",
                "start" => "20:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["11"],
                "description" => "الحان بنات",
                "start" => "18:00",
                "end" => "19:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["9"],
                "description" => "الحان بنات",
                "start" => "19:00",
                "end" => "20:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["إعداد خدمة بنات"],
                "room_id" => $rooms["B204"],
                "description" => "أسرة",
                "start" => "19:30",
                "end" => "21:30",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            [
                "service_id" => $services["إعداد خدمة بنات"],
                "room_id" => $rooms["4"],
                "description" => "أسرة",
                "start" => "19:00",
                "end" => "21:00",
                "day_of_week" => $daysOfWeek["thu"],
            ],

            /** Friday **/

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["B204"],
                "description" => "ألحان إعدادى بنات",
                "start" => "10:00",
                "end" => "11:00",
                "day_of_week" => $daysOfWeek["fri"],
            ],

            [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms["B102"],
                "description" => "ألحان إعدادى بنات",
                "start" => "11:00",
                "end" => "12:00",
                "day_of_week" => $daysOfWeek["fri"],
            ],

            [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms["4"],
                "description" => "اجتماع الخدمة",
                "start" => "13:00",
                "end" => "14:00",
                "day_of_week" => $daysOfWeek["fri"],
            ],
        ];

        foreach (["C207", "C208", "C205", "B202", "B203"] as $room) {
            $reservations[] = [
                "service_id" => $services["قرية (ابونا بيمن)"],
                "room_id" => $rooms[$room],
                "description" => "خدمة",
                "start" => "16:00",
                "end" => "18:30",
                "day_of_week" => $daysOfWeek["tue"],
            ];
        }

        foreach (["C201", "C205", "1", "2"] as $room) {
            $reservations[] = [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms[$room],
                "description" => "افتتاحية مدارس الأحد",
                "start" => "11:00",
                "end" => "12:00",
                "day_of_week" => $daysOfWeek["fri"],
            ];
        }
        foreach (["11", "12", "8", "9"] as $room) {
            $reservations[] = [
                "service_id" => $services["ابتدائي بنات"],
                "room_id" => $rooms[$room],
                "description" => "مدارس الأحد",
                "start" => "12:00",
                "end" => "13:00",
                "day_of_week" => $daysOfWeek["fri"],
            ];
        }

        foreach (
            ["C201", "C205", "C208", "C108", "11", "12", "4", "5", "8", "9"]
            as $room
        ) {
            $reservations[] = [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms[$room],
                "description" => "ألحان بنات",
                "start" => "10:00",
                "end" => "11:00",
                "day_of_week" => $daysOfWeek["fri"],
            ];
        }

        foreach (["C203", "C207", "B202", "B203"] as $room) {
            $reservations[] = [
                "service_id" => $services["مدرسة الشاروبيم"],
                "room_id" => $rooms[$room],
                "description" => "ألحان بنين",
                "start" => "10:00",
                "end" => "11:00",
                "day_of_week" => $daysOfWeek["fri"],
            ];
        }

        foreach (
            [
                "C201",
                "C203",
                "C207",
                "C208",
                "C205",
                "B202",
                "B203",
                "B204",
                "1",
                "2",
            ]
            as $room
        ) {
            $reservations[] = [
                "service_id" => $services["ابتدائي بنين"],
                "room_id" => $rooms[$room],
                "description" => "مدارس الأحد",
                "start" => "16:00",
                "end" => "18:00",
                "day_of_week" => $daysOfWeek["sun"],
            ];
        }

        foreach ($reservations as $reservation) {
            Reservation::create(array_merge($data, $reservation));
        }
    }
}
