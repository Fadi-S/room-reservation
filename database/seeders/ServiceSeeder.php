<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            "خدمة ملائكة" => "#5ae3ed",
            "خدمة ابتدائي بنين" => "#328cfa",
            "خدمة ابتدائي بنات" => "#fcfc53",
            "خدمة اعدادى بنين" => "#49e651",
            "خدمة اعدادى بنات" => "#b75fe3",
            "خدمة ثانوي بنين" => "#100775",
            "خدمة ثانوي بنات" => "#ab2c85",
            "خدمة شباب" => "#960000",
            "خدمة خريجين" => "#b00750",
            "خدمة الكشافة بنين" => "#4f2505",
            "خدمة الكشافة بنات" => "#ffd036",
            "خدمة مدرسة الشاروبيم" => "#eb6357",
            "خدمة ألأسرة الصغيرة" => "#07b0aa",
            "خدمة العائلة المقدسة" => "#0753b0",
            "خدمة السيدات اجتماع ساكبات الطيب" => "#af6ded",
            "خدمة إعداد خدمة بنين" => "#6dede0",
            "خدمة إعداد خدمة بنات" => "#ad6ded",
            "خدمة يسوع بيحبك" => "#6ded94",
            "خدمة قرى بنين" => "#ed6d71",
            "خدمة قرى بنات" => "#753235",
            "خدمة ذوى الاحتياجات الخاصة" => "#5d3275",

            "خدمة مركز الكمبيوتر" => "#323675",
            "خدمة وسائل الإيضاح" => "#754232",
            "خدمة راكوتى" => "#7c6ded",
            "خدمة مكتبة الطفل بى كوجي" => "#593275",
        ];

        foreach ($services as $name => $color) {
            Service::query()->create([
                "name" => $name,
                "code" => Str::replace(" ", "-", $name),
                "color" => $color,
            ]);
        }
    }
}
