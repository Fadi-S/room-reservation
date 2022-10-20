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
            "ملايكة" => "#5ae3ed",
            "ابتدائي بنين" => "#328cfa",
            "ابتدائي بنات" => "#fcfc53",
            "اعدادى بنين" => "#49e651",
            "اعدادى بنات" => "#b75fe3",
            "ثانوي بنين" => "#100775",
            "ثانوي بنات" => "#ab2c85",
            "شباب" => "#960000",
            "خريجين" => "#b00750",
            "حديثي الزواج" => "#eaed21",
            "المقبلين على الزواج" => "#b31b85",
            "الكشافة بنين" => "#4f2505",
            "الكشافة بنات" => "#ffd036",
            "مدرسة الشاروبيم" => "#eb6357",
            "الأسرة الصغيرة" => "#07b0aa",
            "العائلة المقدسة" => "#0753b0",
            "ساكبات الطيب" => "#af6ded",
            "إعداد خدمة بنين" => "#6dede0",
            "إعداد خدمة بنات" => "#ad6ded",
            "يسوع بيحبك" => "#6ded94",
            "قرية (ابونا بيمن)" => "#ed6d71",
            "قرى أستاذ يسري" => "#753235",
            "الأبن الشاطر" => "#753235",
            "ذوى الاحتياجات الخاصة" => "#5d3275",
            "حتة من السماء" => "#32a87d",
            "التصوير" => "#753235",

            "عام" => "#ffffff",

            "حضانة" => "#ed5ad4",

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
